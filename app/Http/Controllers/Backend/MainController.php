<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientServiceSession;
use App\Models\Reservation;
use App\Models\Services;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->account_type === 'technician') {
            return $this->technicianDashboard();
        }

        return $this->doctorDashboard();
    }

    private function doctorDashboard()
    {
        $patients = Patient::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $sessions = PatientServiceSession::where('user_id', auth()->id())
            ->whereDate('date', Carbon::today())
            ->orderBy('id', 'desc')
            ->get();

        $services = Services::all();

        $todaySlots = $this->buildReservationSlots(Carbon::today(), auth()->id());
        $tomorrowSlots = $this->buildReservationSlots(Carbon::tomorrow(), auth()->id());

        $reservations = Reservation::with(['patient', 'service'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->whereDate('date', '>=', Carbon::today())
            ->orderByRaw("
            case
                when date = curdate() then 0
                when date = curdate() + interval 1 day then 1
                else 2
            end asc
        ")
            ->orderBy('date', 'asc')
            ->orderBy('hour', 'asc')
            ->get();

        return view('admin.pages.index', compact(
            'patients',
            'sessions',
            'reservations',
            'services',
            'todaySlots',
            'tomorrowSlots'
        ));
    }

    private function technicianDashboard()
    {
        return view('admin.technician.index');
    }

    private function buildReservationSlots($date, $doctorId)
    {
        $doctor = User::find($doctorId);

        [$workStart, $workEnd] = explode('-', $doctor->doctorProfile->work_hours ?? '10:00-20:00');

        $reservations = Reservation::whereDate('date', $date->format('Y-m-d'))
            ->where('user_id', $doctorId)
            ->where('status', 'pending')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->hour)->format('H:i');
            });

        $slots = [];

        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $workStart);
        $end   = Carbon::parse($date->format('Y-m-d') . ' ' . $workEnd);

        if ($end->lte($start)) {
            $end->addDay();
        }

        while ($start < $end) {
            $time = $start->format('H:i');
            $slotDateTime = $start->copy();

            $slot = [
                'time' => $time,
                'type' => 'free',
                'url'  => null,
            ];

            if ($date->isToday() && $slotDateTime->lte(now())) {
                $slot['type'] = 'past';
            }

            if (isset($reservations[$time])) {
                $reservation = $reservations[$time];

                if ($reservation->patient_id) {
                    $slot['type'] = 'busy_patient';
                    $slot['url'] = route('admin.crm.info', ['id' => $reservation->patient_id]);
                } else {
                    $slot['type'] = 'busy_empty';
                    $slot['url'] = null;
                }
            }

            $slots[] = $slot;
            $start->addMinutes(30);
        }

        return $slots;
    }

    public function settings()
    {
        $user = auth()->user();
        $user->load('doctorProfile');

        return view('admin.pages.settings', compact('user'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'nullable|string|max:500',
            'work_hours' => [
                'nullable',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d-(?:[01]\d|2[0-3]):[0-5]\d$/',
            ],
        ], [
            'clinic_name.required' => 'Klinika adı mütləqdir.',
            'clinic_name.max' => 'Klinika adı maksimum 255 simvol ola bilər.',
            'clinic_address.max' => 'Klinika ünvanı maksimum 500 simvol ola bilər.',
            'work_hours.regex' => 'İş saatı xx:xx-xx:xx formatında olmalıdır. Məsələn: 10:00-20:00',
        ]);

        auth()->user()->doctorProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Ayarlar yeniləndi');
    }
}
