<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\Reservation;
use App\Models\Services;
use App\Models\User;
use App\Services\Telegram;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagesController extends Controller
{
    public function home()
    {
        return view('frontend.main');
    }

    public function doctors()
    {
        return view('frontend.doctors');
    }

    public function patients()
    {
        $doctors = User::whereNotNull('clinic_name')
            ->where('clinic_name', '!=', '')
            ->where('name','!=','demo')
            ->with([
                'services' => fn($q) => $q->withoutGlobalScope('doctor')->where('active', 1)->where('visible',1)
            ])
            ->orderBy('clinic_name')
            ->get();

        return view('frontend.patients', compact('doctors'));
    }

    public function reservationHours(Request $request)
    {
        $date     = $request->date;
        $doctorId = $request->doctor;

        if (!$date || !$doctorId) {
            return '';
        }

        $busy = Reservation::whereDate('date', $date)
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->pluck('hour')
            ->map(fn($hour) => Carbon::parse($hour)->format('H:i'))
            ->toArray();

        $start   = Carbon::createFromTime(10, 0);
        $end     = Carbon::createFromTime(20, 0);
        $now     = now(); // ← cari vaxt
        $isToday = Carbon::parse($date)->isToday(); // ← seçilən gün bugündürmü?

        $html  = '<label>Saat seçin</label>';
        $html .= '<div class="d-flex flex-wrap gap-2 mt-2">';

        while ($start <= $end) {
            $time = $start->format('H:i');
            $id   = $start->format('Hi');

            // Bugündirsə və saat artıq keçibsə — passiv et
            $isPast = $isToday && $start->lt($now);
            $isBusy = in_array($time, $busy);

            if ($isPast || $isBusy) {
                $html .= '<button type="button" class="slot-btn btn-danger" disabled>' . $time . '</button>';
            } else {
                $html .= '<button type="button" class="slot-btn btn-success" id="' . $id . '" onclick="setFrontTime(\'' . $id . '\',\'' . $time . '\')">' . $time . '</button>';
            }

            $start->addMinutes(30);
        }

        $html .= '</div>';

        return $html;
    }

    public function reservation(Request $request)
    {
        $validated = $request->validate([
            'fullname'  => 'required|string|min:5|max:50',
            'mobile'    => 'required|string|min:7|max:13',
            'doctor_id' => 'required|exists:user,id',
            'service_id' => 'required|integer',
            'date'      => 'required|date',
            'hour'      => 'required|string',
        ], [
            'service_id.required' => 'Xidmət seç',

            'fullname.required'  => 'Ad soyad yaz',
            'fullname.min'       => 'Ad soyad minimum 5 hərf olmalıdır',
            'fullname.max'       => 'Ad soyad maksimum 50 hərf olmalıdır',

            'mobile.required'    => 'Mobil nömrə yaz',
            'mobile.min'         => 'Mobil nömrə minimum 7 simvol olmalıdır',
            'mobile.max'         => 'Mobil nömrə maksimum 13 simvol olmalıdır',

            'doctor_id.required' => 'Həkim seç',
            'doctor_id.exists'   => 'Seçilən həkim tapılmadı',

            'date.required'      => 'Tarix seç',
            'date.date'          => 'Tarix düzgün deyil',

            'hour.required'      => 'Saat seç',
        ]);

        $date = $validated['date'];
        $hour = $validated['hour'];

        $reservationDateTime = Carbon::parse($date . ' ' . $hour);

        if ($reservationDateTime->lt(now())) {
            return response()->json(['success' => false, 'message' => 'Keçmiş tarix və saata rezervasiya yaratmaq olmaz'], 422);
        }

        $exists = Reservation::where('doctor_id', $validated['doctor_id'])
            ->where('date', $date)
            ->where('hour', $hour)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Seçilən tarix və saat artıq tutulub'], 422);
        }

        Reservation::create([
            'doctor_id'   => $validated['doctor_id'],
            'customer_id' => null,
            'service_id'  => $validated['service_id'],
            'date'        => $date,
            'hour'        => $hour,
            'note'        => $validated['fullname'].' - '.$validated['mobile'],
            'status'      => 'pending',
        ]);


        return response()->json(['success' => true, 'message' => 'Rezervasiya göndərildi']);
    }
}
