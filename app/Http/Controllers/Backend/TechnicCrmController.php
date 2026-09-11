<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Technician\TechnicianDoctor;
use App\Models\Technician\TechnicianDoctorBalance;
use App\Models\Technician\TechnicianDoctorLedger;
use App\Models\Technician\TechnicianJob;
use App\Models\Technician\TechnicianJobItem;
use App\Models\Technician\TechnicianJobItemLocation;
use App\Models\ServiceLocation;
use App\Services\Technician\TechnicianDoctorInfoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TechnicCrmController extends Controller
{
    public function index()
    {
        return view('admin.tcrm.index');
    }
    public function info($id, TechnicianDoctorInfoService $doctorInfoService)
    {
        $data = $doctorInfoService->getData(
            (int) $id,
            auth()->id()
        );

        $data['locations'] = ServiceLocation::where('type', 'tooth_map')
            ->get()
            ->keyBy('code');

        return view('admin.tcrm.doctor', $data);
    }

    public function addJob(Request $request, $id)
    {
        $request->validate([
            'patient_name' => 'nullable|string|max:255',
            'received_type' => 'required|in:doctor,technician',
            'received_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:received_at',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.location_ids' => 'required|array|min:1',
            'items.*.location_ids.*' => 'required|exists:service_locations,id',
        ]);

        $doctor = TechnicianDoctor::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        DB::transaction(function () use ($request, $doctor) {
            $totalAmount = 0;

            $job = TechnicianJob::create([
                'user_id' => auth()->id(),
                'doctor_id' => $doctor->id,
                'patient_name' => $request->patient_name,
                'received_at' => $request->received_at,
                'due_at' => $request->due_at,
                'status' => 'active',
                'received_type' => $request->received_type,
                'total_amount' => 0,
                'note' => $request->note,
            ]);

            foreach ($request->items as $itemData) {
                $locationIds = array_values(array_unique($itemData['location_ids']));
                $quantity = count($locationIds);
                $price = (float) $itemData['price'];
                $totalPrice = $price * $quantity;

                $item = TechnicianJobItem::create([
                    'job_id' => $job->id,
                    'service_id' => $itemData['service_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                ]);

                foreach ($locationIds as $locationId) {
                    TechnicianJobItemLocation::create([
                        'job_id' => $job->id,
                        'item_id' => $item->id,
                        'location_id' => $locationId,
                    ]);
                }

                $totalAmount += $totalPrice;
            }

            $job->update([
                'total_amount' => $totalAmount,
            ]);

            TechnicianDoctorLedger::create([
                'doctor_id' => $doctor->id,
                'user_id' => auth()->id(),
                'job_id' => $job->id,
                'type' => 'job',
                'amount' => $totalAmount,
                'note' => $request->note,
                'created_at' => now(),
            ]);

            $balance = TechnicianDoctorBalance::firstOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'balance' => 0,
                    'updated_at' => now(),
                ]
            );

            $balance->balance += $totalAmount;
            $balance->updated_at = now();
            $balance->save();
        });

        return redirect()
            ->route('admin.tcrm.info', $doctor->id)
            ->with('success', 'İş uğurla yaradıldı.');
    }

    public function jobServices($id)
    {
        $job = TechnicianJob::with([
            'items.service',
            'items.locations.location',
        ])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $usedLocations = $job->items
            ->flatMap(function ($item) {
                return $item->locations->pluck('location_id');
            })
            ->values();

        return response()->json([
            'job_id' => $job->id,
            'items' => $job->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'service' => $item->service?->name,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'note' => $item->note,
                    'locations' => $item->locations->map(function ($row) {
                        return [
                            'id' => $row->location_id,
                            'code' => $row->location?->code,
                        ];
                    })->values(),
                ];
            })->values(),
            'used_locations' => $usedLocations,
        ]);
    }

    public function addJobServices(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.location_ids' => 'required|array|min:1',
            'items.*.location_ids.*' => 'required|exists:service_locations,id',
        ]);

        $job = TechnicianJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $doctor = TechnicianDoctor::where('id', $job->doctor_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        DB::transaction(function () use ($request, $job, $doctor) {
            $addedAmount = 0;

            foreach ($request->items as $itemData) {
                $locationIds = array_values(array_unique($itemData['location_ids']));

                $alreadyUsed = TechnicianJobItemLocation::where('job_id', $job->id)
                    ->whereIn('location_id', $locationIds)
                    ->exists();

                if ($alreadyUsed) {
                    abort(422, 'Seçilən dişlərdən biri artıq bu işə əlavə olunub.');
                }

                $quantity = count($locationIds);
                $price = (float) $itemData['price'];
                $totalPrice = $price * $quantity;

                $item = TechnicianJobItem::create([
                    'job_id' => $job->id,
                    'service_id' => $itemData['service_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                ]);

                foreach ($locationIds as $locationId) {
                    TechnicianJobItemLocation::create([
                        'job_id' => $job->id,
                        'item_id' => $item->id,
                        'location_id' => $locationId,
                    ]);
                }

                $addedAmount += $totalPrice;
            }

            $job->total_amount += $addedAmount;
            $job->save();

            TechnicianDoctorLedger::where('job_id', $job->id)
                ->where('type', 'job')
                ->delete();

            TechnicianDoctorLedger::create([
                'doctor_id' => $doctor->id,
                'user_id' => auth()->id(),
                'job_id' => $job->id,
                'type' => 'job',
                'amount' => $job->total_amount,
                'note' => 'İş',
                'created_at' => now(),
            ]);

            $balance = TechnicianDoctorBalance::firstOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'balance' => 0,
                    'updated_at' => now(),
                ]
            );

            $balance->balance += $addedAmount;
            $balance->updated_at = now();
            $balance->save();
        });

        return redirect()
            ->route('admin.tcrm.info', $job->doctor_id)
            ->with('success', 'Xidmətlər uğurla əlavə edildi.');
    }

    public function editJob($id)
    {
        $job = TechnicianJob::with([
            'items.service',
            'items.locations.location',
        ])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'id' => $job->id,
            'patient_name' => $job->patient_name,
            'received_type' => $job->received_type,
            'received_at' => $job->received_at?->format('Y-m-d\TH:i'),
            'due_at' => $job->due_at?->format('Y-m-d\TH:i'),
            'note' => $job->note,
            'total_amount' => $job->total_amount,

            'items' => $job->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'service_id' => $item->service_id,
                    'service' => $item->service?->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,

                    'locations' => $item->locations->map(function ($row) {
                        return [
                            'id' => $row->location_id,
                            'code' => $row->location?->code,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    public function updateJob(Request $request, $id)
    {
        $request->validate([
            'patient_name' => 'nullable|string|max:255',
            'received_type' => 'required|in:doctor,technician',
            'received_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:received_at',
            'note' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.location_ids' => 'required|array|min:1',
            'items.*.location_ids.*' => 'required|exists:service_locations,id',
        ]);

        $job = TechnicianJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        /*
         * Eyni diş iki ayrı xidmət qrupunda ola bilməz.
         */
        $allLocationIds = [];

        foreach ($request->items as $itemData) {
            foreach ($itemData['location_ids'] as $locationId) {
                $allLocationIds[] = (int) $locationId;
            }
        }

        if (count($allLocationIds) !== count(array_unique($allLocationIds))) {
            return back()
                ->withInput()
                ->withErrors([
                    'items' => 'Eyni diş bir neçə xidmət qrupuna əlavə edilə bilməz.',
                ]);
        }

        DB::transaction(function () use ($request, $job) {

            $oldTotal = (float) $job->total_amount;

            /*
             * Köhnə item location-ları silirik.
             */
            TechnicianJobItemLocation::where('job_id', $job->id)->delete();

            /*
             * Köhnə xidmət qruplarını silirik.
             */
            TechnicianJobItem::where('job_id', $job->id)->delete();

            $newTotal = 0;

            /*
             * Yeni qrupları yaradırıq.
             */
            foreach ($request->items as $itemData) {

                $locationIds = array_values(
                    array_unique($itemData['location_ids'])
                );

                $quantity = count($locationIds);
                $price = (float) $itemData['price'];
                $totalPrice = $price * $quantity;

                $item = TechnicianJobItem::create([
                    'job_id' => $job->id,
                    'service_id' => $itemData['service_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                ]);

                foreach ($locationIds as $locationId) {
                    TechnicianJobItemLocation::create([
                        'job_id' => $job->id,
                        'item_id' => $item->id,
                        'location_id' => $locationId,
                    ]);
                }

                $newTotal += $totalPrice;
            }

            /*
             * Job məlumatlarını yeniləyirik.
             */
            $job->patient_name = $request->patient_name;
            $job->received_type = $request->received_type;
            $job->received_at = $request->received_at;
            $job->due_at = $request->due_at;
            $job->note = $request->note;
            $job->total_amount = $newTotal;
            $job->save();

            /*
             * Balans yalnız fərq qədər dəyişməlidir.
             *
             * 590 -> 650 = +60
             * 590 -> 500 = -90
             */
            $difference = $newTotal - $oldTotal;

            if ($difference != 0) {
                $balance = TechnicianDoctorBalance::firstOrCreate(
                    [
                        'doctor_id' => $job->doctor_id,
                        'user_id' => auth()->id(),
                    ],
                    [
                        'balance' => 0,
                        'updated_at' => now(),
                    ]
                );

                $balance->balance += $difference;
                $balance->updated_at = now();
                $balance->save();
            }

            /*
             * Bu job üçün ledger-də yalnız BİR job sətri qalır.
             */
            TechnicianDoctorLedger::where('job_id', $job->id)
                ->where('type', 'job')
                ->delete();

            TechnicianDoctorLedger::create([
                'doctor_id' => $job->doctor_id,
                'user_id' => auth()->id(),
                'job_id' => $job->id,
                'type' => 'job',
                'amount' => $newTotal,
                'note' => 'İş',
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.tcrm.info', $job->doctor_id)
            ->with('success', 'İş uğurla yeniləndi.');
    }

    public function completeJob($id)
    {
        $job = TechnicianJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($job->status !== 'active') {
            return back()->with('error', 'Bu iş artıq aktiv deyil.');
        }

        $job->status = 'completed';
        $job->save();

        return back()->with('success', 'İş tamamlandı.');
    }

    public function deleteJob($id)
    {
        $job = TechnicianJob::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($job->status === 'completed') {
            return back()->with('error', 'Tamamlanmış işi silmək olmaz.');
        }

        DB::transaction(function () use ($job) {
            $amount = (float) $job->total_amount;

            TechnicianJobItemLocation::where('job_id', $job->id)->delete();
            TechnicianJobItem::where('job_id', $job->id)->delete();

            TechnicianDoctorLedger::where('job_id', $job->id)->delete();

            $balance = TechnicianDoctorBalance::where('doctor_id', $job->doctor_id)
                ->where('user_id', auth()->id())
                ->first();

            if ($balance) {
                $balance->balance -= $amount;
                $balance->updated_at = now();
                $balance->save();
            }

            $job->delete();
        });

        return redirect()
            ->route('admin.tcrm.info', $job->doctor_id)
            ->with('success', 'İş silindi.');
    }
}
