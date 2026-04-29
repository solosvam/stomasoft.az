<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PasswordUpdate;
use App\Http\Requests\Backend\ProfileUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $permissions = $admin->getAllPermissions();
        return view('admin.pages.authentication.profile',compact('permissions','admin'));
    }

    public function update(ProfileUpdate $request)
    {
        $admin = Auth::user();
        $admin->update([
            'name'     => $request->name,
            'surname'  => $request->surname,
            'mobile'   => $request->mobile,
        ]);

        return redirect()->back()->with('success', 'Məlumatlar yeniləndi!');
    }

    public function updatepassword(PasswordUpdate $request)
    {
        $admin = Auth::user();

        $admin->password = Hash::make($request->new_password);

        $admin->save();

        return redirect()->back()->with('success', 'Şifrə yeniləndi!');
    }
}
