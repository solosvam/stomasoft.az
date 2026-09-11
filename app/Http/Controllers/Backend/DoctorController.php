<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DoctorCreateRequest;
use App\Models\Technician\TechnicianDoctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctors = TechnicianDoctor::where('user_id',$user->id)->orderByDesc('id')->get();

        return view('admin.doctor.list',[
            'doctors'  => $doctors
        ]);
    }

    public function create(DoctorCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        TechnicianDoctor::create($data);
        return redirect()->back();
    }

    public function edit($id)
    {
        $doctor = TechnicianDoctor::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('admin.doctor.edit', [
            'doctor' => $doctor
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $doctor = TechnicianDoctor::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->lockForUpdate()
                    ->firstOrFail();

                $hasOperation =
                    $doctor->jobs()->exists()
                    || $doctor->balances()->exists()
                    || $doctor->ledgers()->exists();

                if ($hasOperation) {
                    throw new \Exception('həkim üzrə əməliyyat olduğu üçün silinə bilməz');
                }

                $doctor->delete();
            });

            return redirect()
                ->route('admin.doctor.list')
                ->with('success', 'Həkim silindi');
        } catch (\Throwable $e) {
            return back()->withErrors([
                'delete' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $doctor = TechnicianDoctor::findOrFail($id);

        $data = $request->only([
            'name',
            'surname',
            'address',
            'mobile',
        ]);

        $doctor->update($data);

        return redirect()->back()->with('success','Düzəliş edildi');
    }

    public function listData()
    {
        $user = Auth::user();
        $doctors = TechnicianDoctor::where('user_id',$user->id)->orderByDesc('id')->get()->map(function ($p) {
            return [
                'id'       => $p->id,
                'fullname' => $p->name . ' ' . $p->surname,
                'mobile'   => $p->mobile,
                'balance'  => $p->total_balance.' AZN',
            ];
        });


        return response()->json($doctors);
    }

    public function debtors()
    {
        $debtors = TechnicianDoctor::where('user_id', auth()->id())
            ->whereHas('balance', function ($query) {
                $query->where('balance', '>', 0);
            })
            ->with('balance')
            ->orderBy('name')
            ->get();

        return view('admin.doctor.debtors', compact('debtors'));
    }

    public function activeJobs()
    {
        $doctors = TechnicianDoctor::where('user_id', auth()->id())
            ->whereHas('jobs', function ($query) {
                $query->where('status', 'active');
            })
            ->withCount([
                'jobs as active_jobs_count' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->with([
                'jobs' => function ($query) {
                    $query->where('status', 'active')
                        ->orderBy('due_at');
                }
            ])
            ->orderBy('name')
            ->get();

        return view('admin.doctor.active-jobs', compact('doctors'));
    }

}
