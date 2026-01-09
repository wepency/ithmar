<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Unit;
use App\Models\User;
use App\Models\Sector;
use App\Models\Beach;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    public function index(Request $request)
    {
        $rows = new User;
        $beaches = new Beach;

        if ($request->type != '' && is_admin()){
            $rows = $rows->where('role', $request->type);
        }

        if (is_sector_admin()){
            $units = Unit::select('user_id')->where('sector_id', auth()->user()->role_id)->where('status', 1)->get()->pluck('user_id')->toArray();
            $arrays = array_unique($units);

            $rows = $rows->with(['unitOrder' => function ($q){
                $q->where('sector_id', auth()->user()->role_id);
            }])->whereIn('id', $arrays);

            $beaches = $beaches->where('sector_id', auth()->user()->role_id);
        }

        $sectors = Sector::all();
        $beaches = $beaches->get();

        if ($request->beach != ''){
            $rows = $rows->whereHas('unit', function (Builder $q) use ($request){
                $q->where('beach_id', $request->beach);
            });
        }

        if ($request->unit != ''){
//            return $request->unit;
            $rows = $rows->whereHas('unit', function (Builder $q) use ($request){
                $q->where('id', $request->unit);
            });
        }

        if ($request->beach != '' || $request->unit != ''){
            $rows = $rows->where('role', 'investor');
        }

        if ($request->phonenumber != '')
            $rows = $rows->where('phonenumber', 'like', '%'.$request->phonenumber.'%');

        $rows = $rows->orderBy('id', 'DESC')->paginate();

        $roles = Role::all();

        return view('admin.users.index',[
          'rows' => $rows,
          'beaches' => $beaches,
          'sectors' => $sectors,
          'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
      // investor, sector
        $data = $request->validate([
          "name" => "required",
          "phonenumber" => "required|unique:users",
          "email" => "required|unique:users",
          "password" => "required"
        ]);

        $data['role'] = 'investor';

        $user_payment = $request->user_payment;

        $data['user_free'] = $user_payment == 'user_free' ? 1 : 0;
        $data['user_exempt'] = $user_payment == 'user_exempt' ? 1 : 0;

        if ($request->role) {
            if ($request->role == 'investor'){
                $data['role'] = 'investor';
                $data['role_id'] = '';
            }else {
                $data['role'] = 'admin';
                $data['role_id'] = '';
            }
        }

        $data['password'] = Hash::make($request->password);

//        $user = User::create([
//            'name' => $request->name,
//            'phonenumber' => $request->phonenumber,
//            'email' => $request->email,
//            'password' => Hash::make($request->password),
//            'role' => 'investor'
//        ]);

        $user = User::create($data);

        if ($request->role) {
            if ($request->role != 'investor'){
                $user->syncRoles([$request->role]);
            }
        }

        History::create([
            'hismodel_id' => $user->id,
            'hismodel_type' => 'App\Models\User',
            'type' => 'create',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        return back()->withSuccess("عملية ناجحة");
    }

    public function show($id)
    {
        $page_title = 'سجل المستثمر';
        $history = (new \App\Classes\History)->getAllHistory('App\Models\User', $id, 'users');
        return view('admin.history', compact('history', 'page_title'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            "name" => "required",
            "phonenumber" => [
                'required',
                Rule::unique('users')->ignore($id)
            ],
            "email" => [
                'nullable',
                Rule::unique('users')->ignore($id)
            ]
        ], [
            'email' => 'البريد الإلكتروني'
        ]);

        $user_payment = $request->user_payment;

        $data['user_free'] = $user_payment == 'user_free' ? 1 : 0;
        $data['user_exempt'] = $user_payment == 'user_exempt' ? 1 : 0;
//        $data['user_free'] = $request->user_free == 'on';
//        $data['user_exempt'] = $request->user_exempt == 'on';

        History::create([
            'hismodel_id' => $user->id,
            'hismodel_type' => 'App\Models\User',
            'type' => 'update',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

        if ($request->password != ''){
            $data['password'] = Hash::make($request->password);
        }else {
            unset($data['password']);
        }

        if ($request->role) {
            if ($request->role == 'investor'){
                $data['role'] = 'investor';
                $data['role_id'] = '';

                $user->syncRoles([]);
            }else {
                $data['role'] = 'admin';
                $data['role_id'] = '';

//                return $request->role;
                $user->syncRoles([$request->role]);
            }
        }

        $user->update($data);

        return back()->withSuccess("عملية ناجحة");
    }

    public function switchBlock (Request $request,  $user)
    {
      $user = User::findOrFail($user);
      $block = !$user->blocked;

      $user->update([
        'blocked_note' => $request->note,
        'blocked' => $block,
      ]);

        History::create([
            'hismodel_id' => $user->id,
            'hismodel_type' => 'App\Models\User',
            'type' => $block ? 'blocked' : 'unblocked',
            'extra' => $request->note,
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

      return back()->withSuccess("عملية ناجحة");
    }

    public function history(Request $request, $user_id){
        $page_title = 'سجل كامل للمستخدم';
        $history = (new \App\Classes\History)->getUserHistory($request, $user_id);
        return view('admin.users.full-history', compact('history', 'user_id', 'page_title'));
    }

    public function unitStatus(Request $request, $id)
    {

        $user = User::findOrFail($id);

        return DB::transaction(function () use ($request, $user){
            $units = $request->units;
            $notes = $request->notes;

//            return $units;
            $reset = array(
                'status' => '1',
                'note' => ''
            );

            if (!is_null($user->unit) && !is_null($units)){
                foreach ($user->unit as $key => $unit){
                    if (key_exists($unit->id, $units)){
                        $unit->update([
                            'status' => '2',
                            'note' => $notes[$unit->id] ?? ''
                        ]);

                        History::create([
                            'hismodel_id' => $unit->id,
                            'hismodel_type' => 'App\Models\Unit',
                            'type' => 'closed',
                            'user_id' => auth()->id(),
                            'created_at' => Carbon::now(),
                            'updated_at' => ''
                        ]);

                    }else{
                        $unit->update($reset);

                        History::create([
                            'hismodel_id' => $unit->id,
                            'hismodel_type' => 'App\Models\Unit',
                            'type' => 'reset',
                            'user_id' => auth()->id(),
                            'created_at' => Carbon::now(),
                            'updated_at' => ''
                        ]);
                    }
                }
            }else {
                foreach ($user->unit as $key => $unit){
                    $unit->update($reset);

                    History::create([
                        'hismodel_id' => $unit->id,
                        'hismodel_type' => 'App\Models\Unit',
                        'type' => 'reset',
                        'user_id' => auth()->id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => ''
                    ]);
                }
            }

            return redirect()->back()->withSuccess('تم التعديل بنجاح.');
        });
    }
}
