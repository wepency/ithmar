<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Sector;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SectorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        if(is_admin())
        {
          $rows = Sector::orderBy('id', 'DESC')->paginate();
          $users = User::where('role', '!=', 'sector')->get();
        }else{
          $rows = Sector::where('user_id',auth()->id())->orderBy('id', 'DESC')->paginate();
          $users = [];
        }

        $settings = Setting::first();

        return view('admin.sector.index',[
          'rows' => $rows,
          'users' => $users,
          'settings' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
          'sector_name' => 'required',
          'name' => 'required',
          'password' => 'required',
          'phonenumber' => 'required|unique:users',
          'email' => 'required|unique:users',
          'percentage' => 'required',
          'price' => 'nullable|numeric',
          'vat' => 'nullable|numeric',
          'total' => 'nullable|numeric'
        ]);

        return DB::transaction(function () use ($request, $data){

            $user = User::create([
                'name' => $request->name,
                'phonenumber' => $request->phonenumber,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'role' => 'sector'
            ]);

            $data['user_id'] = $user->id;

            $sector = Sector::create($data);

            $user->role_id = $sector->id;
            $user->save();

            $user->syncRoles(['mdyr-ktaaa']);

            History::create([
                'hismodel_id' => $sector->id,
                'hismodel_type' => 'App\Models\Sector',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return back()->withSuccess("عملية ناجحة");
        });
    }

    public function update(Request $request,  $sector)
    {
      $sector = Sector::findOrFail($sector);
      $user = User::select('id')->where('role_id', $sector->id)->first();

      $data = $request->validate([
        'sector_name' => 'required',
        'name' => 'required',
        'password' => 'nullable',
        'phonenumber' => [
            'required',
            Rule::unique('users')->ignore($user->id)
        ],
        'email' => [
                    Rule::unique('users')->ignore($user->id)
                  ],
                  'percentage' => 'required',
                  'price' => 'nullable|numeric',
                  'vat' => 'nullable|numeric',
                  'total' => 'nullable|numeric'
                ]);
      $user = User::findOrFail($sector->user_id);

      $user->name = $request->name;
      $user->email = $request->email;
      $user->phonenumber = $request->phonenumber;

      if ($request->password != ''){
          $user->password = Hash::make($request->password);
      }

      $user->save();

      $sector->update($data);

        History::create([
            'hismodel_id' => $sector->id,
            'hismodel_type' => 'App\Models\Sector',
            'type' => 'update',
            'user_id' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => ''
        ]);

      return back()->withSuccess("عملية ناجحة");
    }

    public function destroy( $id)
    {
        $sector = Sector::findOrFail($id);
        $user = User::find($sector->user_id);

        $user->update([
            'role' => 'investor',
            'role_id' => ''
        ]);

        $sector->beach()->delete();
        $sector->delete();
        return back()->withSuccess("عملية ناجحة");
    }

    public function show($id){
        $page_title = 'سجل القطاع';
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Sector', $id, 'sectors');
        return view('admin.history', compact('history', 'page_title'));
    }
}
