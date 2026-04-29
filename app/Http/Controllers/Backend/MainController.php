<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientServiceSession;
use App\Models\Reservation;
use App\Models\Services;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $patients = Patient::where('user_id',auth()->id())->orderBy('id', 'desc')->limit(20)->get();
        $sessions = PatientServiceSession::where('user_id',auth()->id())->whereDate('date',Carbon::today())->orderBy('id','desc')->get();
        $services = Services::all();
        $todaySlots = $this->buildReservationSlots(Carbon::today(), auth()->id());
        $tomorrowSlots = $this->buildReservationSlots(Carbon::tomorrow(), auth()->id());
        $reservations = Reservation::with(['patient', 'service'])
            ->where('doctor_id', auth()->id())
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

        return view('admin.pages.index',compact('patients','sessions','reservations','services','todaySlots','tomorrowSlots'));
    }

    private function buildReservationSlots($date, $doctorId)
    {
        $reservations = Reservation::whereDate('date', $date->format('Y-m-d'))
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->hour)->format('H:i');
            });

        $slots = [];
        $start = Carbon::createFromTime(10, 0);
        $end   = Carbon::createFromTime(20, 0);

        while ($start <= $end) {
            $time = $start->format('H:i');
            $slotDateTime = Carbon::parse($date->format('Y-m-d').' '.$time);

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
                $slot['type'] = 'busy';
                $slot['url'] = $reservation->patient_id
                    ? route('admin.crm.info', ['id' => $reservation->patient_id])
                    : null;
            }

            $slots[] = $slot;
            $start->addMinutes(30);
        }

        return $slots;
    }

    public function settings()
    {
        $user = auth()->user();

        return view('admin.pages.settings', compact('user'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($validated);

        return back()->with('success','Ayarlar yeniləndi');
    }
}
