<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AjaxController extends Controller
{
    public function setRolePermission(Request $request) {
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->perm_id);

        $checked = filter_var($request->checked, FILTER_VALIDATE_BOOLEAN);

        if($checked){
            $role->givePermissionTo($permission->name);
        }else{
            $role->revokePermissionTo($permission->name);
        }
    }

    public function searchCustomer(Request $request)
    {
        $q = trim((string) $request->search);

        if ($q === '') {
            return response()->json([
                'success' => false,
                'message' => 'axtarış boşdur'
            ]);
        }

        $patients = Patient::query()->where('user_id', auth()->id());

        if (preg_match('/^\d+$/', $q)) {
            $patients->where('mobile', $q);
        } else {
            $parts = preg_split('/\s+/', $q, 2);

            if (count($parts) === 2) {
                [$name, $surname] = $parts;
                $patients->where('name', 'like', "%{$name}%")
                    ->where('surname', 'like', "%{$surname}%");
            } else {
                $patients->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('surname', 'like', "%{$q}%");
                });
            }
        }

        $list = $patients->orderBy('id', 'desc')->limit(20)->get(['id','name','surname','mobile']);

        if ($list->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Pasient tapılmadı'
            ]);
        }

        if ($list->count() === 1) {
            $p = $list->first();
            return response()->json([
                'success' => true,
                'url' => '/admin/crm/' . $p->id
            ]);
        }

        return response()->json([
            'success' => true,
            'items' => $list->map(fn($p) => [
                'full_name' => trim($p->name.' '.$p->surname),
                'mobile' => $p->mobile,
                'url' => '/admin/crm/' . $p->id
            ])->values()
        ]);
    }

    public function searchCustomerForReservation(Request $request)
    {
        $search = $request->search;

        $patients = Patient::where('user_id', auth()->id())
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(name, ' ', surname) like ?", ["%{$search}%"])
                    ->orWhere('mobile', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'surname', 'mobile'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'fullname' => trim(($item->name ?? '') . ' ' . ($item->surname ?? '')),
                    'mobile' => $item->mobile,
                ];
            })
            ->values();

        return response()->json($patients);
    }

}
