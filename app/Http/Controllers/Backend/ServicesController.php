<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Services::orderbydesc('id')->get();
        return view('admin.services.list',[
            'services' => $services,
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'price'    => 'required',
        ],[
            'name.*'    => 'Xidmət adı minimum 3 hərfdən ibarət olmalıdır !'
        ]);

        $validated['doctor_id'] = auth()->id();

        Services::create($validated);

        return redirect()->back()->with('success', 'Xidmət əlavə edildi !');
    }

    public function edit($id)
    {
        $service = Services::where('doctor_id', auth()->id())
            ->findOrFail($id);
        return view('admin.services.edit',[
            'service' => $service
        ]);
    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'active'    => 'required',
            'price'    => 'required',
        ],[
            'name.*'    => 'Xidmət adı minimum 3 hərfdən ibarət olmalıdır !'
        ]);

        $service = Services::where('doctor_id', auth()->id())
            ->findOrFail($id);

        $service->update($validated);

        return redirect()->route('admin.services.list')->with('success', 'Düzəliş olundu !');
    }
}
