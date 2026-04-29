<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Services;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('doctor_id', auth()->id())
            ->orderByRaw("
        case
            when status = 'pending' then 0
            when status = 'done' then 1
            else 2
        end
    ")
            ->orderBy('date', 'asc')
            ->orderBy('hour', 'asc')
            ->paginate(20);

        return view('admin.reservations.list', [
            'reservations' => $reservations,
        ]);
    }

    public function add(Request $request,$patient_id)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer',
            'date'       => 'required|date',
            'hour'       => 'required|string',
            'note'       => 'nullable|string',
        ], [
            'service_id.required' => 'Xidmət seç',
            'date.required'       => 'Tarix seç',
            'date.date'           => 'Tarix düzgün deyil',
            'hour.required'       => 'Saat seç',
        ]);

        $doctorId = Auth::id();

        $date     = $validated['date'];
        $hour     = $validated['hour'];

        $reservationDateTime = Carbon::parse($date . ' ' . $hour);

        if ($reservationDateTime->lt(now())) {
            return back()->withInput()->with('error', 'Keçmiş tarix və saata rezervasiya yaratmaq olmaz');
        }

        $exists = Reservation::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where('hour', $hour)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Seçilən tarix və saat artıq tutulub');
        }

        Reservation::create([
            'doctor_id'  => $doctorId,
            'patient_id' => $patient_id,
            'service_id' => $validated['service_id'],
            'date'       => $date,
            'hour'       => $hour,
            'note'       => $request->note,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Rezervasiya əlavə edildi');
    }

    public function edit($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('doctor_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $reservations = Reservation::whereDate('date', $reservation->date)
            ->where('doctor_id', $reservation->doctor_id)
            ->where('status', 'pending')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->hour)->format('H:i');
            });

        $services = Services::all();

        $slots = [];
        $start = Carbon::createFromTime(10, 0);
        $end   = Carbon::createFromTime(20, 0);

        while ($start <= $end) {
            $time = $start->format('H:i');
            $slotId = $start->format('Hi');

            $slot = [
                'time' => $time,
                'id' => $slotId,
                'type' => 'free',
                'url' => null,
                'selected' => false,
            ];

            if (isset($reservations[$time])) {
                $busyReservation = $reservations[$time];
                $slot['type'] = 'busy';

                if ($busyReservation->patient_id) {
                    $slot['url'] = route('admin.crm.info', $busyReservation->patient_id);
                } else {
                    $slot['url'] = null;
                }

                if ($busyReservation->id == $reservation->id) {
                    $slot['type'] = 'selected';
                    $slot['selected'] = true;
                    $slot['url'] = null;
                }
            }

            $slots[] = $slot;
            $start->addMinutes(30);
        }

        return view('admin.reservations.edit', [
            'reservation' => $reservation,
            'services'    => $services,
            'slots'       => $slots
        ]);
    }

    public function delete($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('doctor_id', auth()->id())
            ->firstOrFail();

        $reservation->delete();

        return redirect()->route('admin.reservations.list')
            ->with('success', 'Rezervasiya silindi');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer',
            'date'       => 'required|date',
            'hour'       => 'required|string',
            'note'       => 'nullable|string',
            'patient_id' => 'nullable|exists:patients,id'
        ], [
            'service_id.required' => 'Xidmət seç',
            'date.required'       => 'Tarix seç',
            'date.date'           => 'Tarix düzgün deyil',
            'hour.required'       => 'Saat seç',
        ]);

        $doctorId = Auth::id();

        $reservation = Reservation::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$reservation) {
            return back()->with('error', 'Rezervasiya tapılmadı');
        }

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Yalnız gözləyən rezervasiya yenilənə bilər');
        }

        $reservationDateTime = Carbon::parse($validated['date'] . ' ' . $validated['hour']);

        if ($reservationDateTime->lt(now())) {
            return back()->withInput()->with('error', 'Keçmiş tarix və saata rezervasiya yeniləmək olmaz');
        }

        $exists = Reservation::where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->where('id', '!=', $reservation->id)
            ->whereDate('date', $validated['date'])
            ->where('hour', $validated['hour'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Seçilən tarix və saata başqa rezervasiya var');
        }

        $updateData = [
            'service_id' => $validated['service_id'],
            'date'       => $validated['date'],
            'hour'       => $validated['hour'],
            'note'       => $validated['note'] ?? null,
        ];

        if ($request->has('patient_id')) {
            $updateData['patient_id'] = $request->filled('patient_id') ? $request->patient_id : null;
        }

        $reservation->update($updateData);

        return redirect()->route('admin.reservations.list')
            ->with('success', 'Rezervasiya yeniləndi');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:done,cancelled'
        ]);

        $reservation = Reservation::where('id', $id)
            ->where('doctor_id', auth()->id())
            ->first();

        if (!$reservation) {
            return back()->with('error', 'Rezervasiya tapılmadı və ya sizə aid deyil');
        }

        if ($reservation->status != 'pending') {
            return back()->with('error', 'Artıq dəyişmək olmaz');
        }

        $reservation->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status yeniləndi');
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer',
            'date'       => 'required|date',
            'hour'       => 'required|string',
            'note'       => 'nullable|string',
            'patient_id' => 'nullable|exists:patients,id',
        ], [
            'service_id.required' => 'Xidmət seç',
            'date.required'       => 'Tarix seç',
            'date.date'           => 'Tarix düzgün deyil',
            'hour.required'       => 'Saat seç',
        ]);

        $doctorId = Auth::id();

        $date     = $validated['date'];
        $hour     = $validated['hour'];

        $reservationDateTime = Carbon::parse($date . ' ' . $hour);

        if ($reservationDateTime->lt(now())) {
            return back()->withInput()->with('error', 'Keçmiş tarix və saata rezervasiya yaratmaq olmaz');
        }

        $exists = Reservation::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where('hour', $hour)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Seçilən tarix və saat artıq tutulub');
        }

        Reservation::create([
            'doctor_id'  => $doctorId,
            'service_id' => $validated['service_id'],
            'patient_id' => $validated['patient_id'],
            'date'       => $date,
            'hour'       => $hour,
            'note'       => $request->note,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Rezervasiya əlavə edildi');
    }

    public function hours(Request $request)
    {
        $date = $request->date;
        $doctorId = $request->doctor;

        if (!$date) {
            return '';
        }

        $reservations = Reservation::whereDate('date', $date)
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->hour)->format('H:i');
            });

        $start = \Carbon\Carbon::createFromTime(10, 0);
        $end   = \Carbon\Carbon::createFromTime(20, 0);

        $html = '<div class="d-flex flex-wrap gap-2">';

        while ($start <= $end) {
            $time = $start->format('H:i');
            $id   = $start->format('Hi');

            if (isset($reservations[$time])) {
                $reservation = $reservations[$time];

                if ($reservation->patient_id) {
                    $url = route('admin.crm.info', ['id' => $reservation->patient_id]);
                    $html .= '<button type="button" class="btn btn-danger" onclick="window.open(\''.$url.'\', \'_blank\')">'.$time.'</button>';
                } else {
                    $html .= '<button type="button" class="btn btn-danger" disabled>'.$time.'</button>';
                }
            } else {
                $html .= '<button type="button" class="btn btn-success" id="'.$id.'" onclick="setTime(\''.$id.'\',\''.$time.'\')">'.$time.'</button>';
            }

            $start->addMinutes(30);
        }

        $html .= '</div>';

        return $html;
    }
}
