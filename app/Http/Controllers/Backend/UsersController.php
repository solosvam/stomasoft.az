<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserCreateRequest;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('admin.users.list',[
            'users'    => $users,
            'roles'     => $roles
        ]);
    }

    public function create(UserCreateRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);
        $user->syncRoles([$request->role_name]);
        return redirect()->back()->with('success', 'Əməkdaş uğurla yaradıldı!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit',[
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|min:2|max:50',
            'surname'    => 'required|string|min:2|max:50',
            'login'      => 'required|string|min:3|max:50|unique:user,login,'.$request->id,
            'mobile'     => 'required|regex:/^[0-9]{12}$/|unique:user,mobile,'.$request->id,
            'password'   => 'nullable|min:6',
            'role_name'  => 'required|exists:roles,name',
        ],[
            'name.required'       => 'Ad yaz',
            'name.min'            => 'Ad minimum 2 hərf olmalıdır',
            'surname.required'    => 'Soyad yaz',
            'surname.min'         => 'Soyad minimum 2 hərf olmalıdır',
            'login.required'      => 'Login boş ola bilməz',
            'login.unique'        => 'Bu login artıq istifadə olunur',
            'mobile.required'     => 'Mobil nömrə yaz',
            'mobile.regex'        => 'Mobil nömrə 994 ilə başlamalı və 12 rəqəm olmalıdır',
            'mobile.unique'       => 'Bu nömrə artıq mövcuddur',
            'password.min'        => 'Şifrə minimum 6 simvol olmalıdır',
            'role_name.required'  => 'Rol seç',
            'role_name.exists'    => 'Rol tapılmadı',
        ]);

        $user = User::findOrFail($request->id);

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->login = $request->login;
        $user->mobile = $request->mobile;
        $user->is_active = $request->is_active;
        $user->is_doctor = $request->is_doctor;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles([$request->role_name]);

        return redirect(route('admin.list'))->with('success', 'Əməkdaş məlumatları yeniləndi!');
    }

    public function subscription($id)
    {
        $user = User::findOrFail($id);
        $payments = SubscriptionPayment::where('user_id', $id)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'html' => view('admin.users.subscription_modal_content', compact('user','payments'))->render()
        ]);
    }

    public function subscriptionPayment(Request $request,$id)
    {
        $user = User::find($id);
        $validated = $request->validate([
            'subscription_ends_at' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        $oldEnd = $user->subscription_ends_at ?? now()->toDateString();

        $user->update([
            'subscription_ends_at' => $validated['subscription_ends_at'],
        ]);

        SubscriptionPayment::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'period_from' => $oldEnd,
            'period_to' => $validated['subscription_ends_at'],
            'paid_at' => now(),
            'status' => 1,
        ]);

        return redirect(route('admin.list'))->with('success', 'Ödəniş əlavə edildi!');
    }
}
