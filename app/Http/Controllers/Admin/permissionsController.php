<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Permissions;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class permissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $rows = Role::paginate();
        $permissions = Permissions::attributes();
        return view('admin.permissions', compact('rows', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'permission' => 'required|array'
        ]);

        $role_name = Str::slug($request->role);
        $main_name = $request->role;

        $count = @Role::where('name', $role_name)->first();

        if (!is_null($count))
            return redirect()->back()->with('error', 'هذه المجموعة موجودة مسبقا ، برجاء اختيار اسم أخر.');

        return DB::transaction(function () use ($role_name, $main_name, $request){
            $role = Role::create(['name' => $role_name, 'main_name' => $main_name]);

            foreach ($request->permission as $permission){
                $role->givePermissionTo($permission);
            }

            History::create([
                'hismodel_id' => $role->id,
                'hismodel_type' => 'Spatie\Permission\Models\Role',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return redirect()->back()->withSuccess('تم إضافة الصلاحيات بنجاح.');
        });
    }

    public function edit($id){
        $page_title = 'تعديل مجموعة الصلاحيات';
        $permissions = Permissions::attributes();
        $role = Role::findOrFail($id);
        $permissionsDB = $role->permissions()->get()->pluck('name')->toArray();
        return view('admin.permissions.edit', compact('permissions', 'page_title', 'role', 'permissionsDB'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required',
            'permission' => 'required|array'
        ]);

        $role_name = Str::slug($request->role);
        $main_name = $request->role;

        return DB::transaction(function () use ($role_name, $main_name, $request, $id){
            $role = Role::findById($id);

            $role->update(['name' => $role_name, 'main_name' => $main_name]);

            foreach ($role->permissions as $permission){
                $role->revokePermissionTo($permission);
            }

            foreach ($request->permission as $permission){
                $role->givePermissionTo($permission);
            }

            History::create([
                'hismodel_id' => $role->id,
                'hismodel_type' => 'Spatie\Permission\Models\Role',
                'type' => 'update',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return redirect()->back()->withSuccess('تم تعديل الصلاحيات بنجاح.');
        });
    }

    public function show($id){
        $history = (new \App\Classes\History)->getAllHistory('Spatie\Permission\Models\Role', $id, 'permissions');
        return view('admin.permissions.show', compact('history'));
    }

    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();

        return redirect()->back()->with('success', 'تم حذف الصلاحيات بنجاح.');
    }
}
