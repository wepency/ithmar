<?php

namespace App\Http\Controllers\Admin;

use App\Models\History;
use App\Http\Controllers\Controller;
use App\Models\Beach;
use App\Models\Contract;
use App\Models\Unit;
use App\Notifications\ContractNotifications;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    private function filter($q)
    {
        if(request('beach'))
        {
          return $q->where('beach_id', request('beach'))->get();
        }
    }

    public function __invoke(){

//        auth()->user()->assignRole(7);
      $today = Carbon::now()->toDateString();
      $today = str_replace('-','/',$today);
      $EnterLeaveTimeRange = [Carbon::parse('00:00:00'), Carbon::parse('23:59:00')];

      if(auth()->user()->role == 'admin')
      {
        $rows = Beach::all();

        // حجوزات جديدة
//        $ContractDayCount = Contract::where('created_at', '>', Carbon::parse('-24 hours'))
        $ContractDayCount = Contract::whereBetween('created_at', [Carbon::today() , Carbon::tomorrow()->subMinute()])
                    ->valid()
                    ->where(function ($q){
                        $this->filter($q);
                    })->count();

              $contactEnterTodayCount = Contract::where(function ($q){
                  $this->filter($q);
              })->whereBetween('from', $EnterLeaveTimeRange)->where('status', 1)->count();

              // مغادرة اليوم
              $contactLeaveTodayCount = Contract::where(function ($q){
                  $this->filter($q);
              })->whereBetween('to', $EnterLeaveTimeRange)->valid()->count();
//          }
      }else{
        $rows = Beach::where('sector_id', auth()->user()->role_id)->get();

        $ContractDayCount = Contract::whereBetween('created_at', [Carbon::today() , Carbon::tomorrow()->subMinute()])
                                        ->where('sector_id', auth()->user()->role_id)
                                        ->where(function ($q){
                                          $this->filter($q);
                                        })->valid()->count();

//        if (Carbon::now() > Carbon::parse('00:00:00')){
//            $contactEnterTodayCount = 0;
//            $contactLeaveTodayCount = 0;
//        }else{
            $contactEnterTodayCount = Contract::where(function ($q){
                $this->filter($q);
            })->whereBetween('from', $EnterLeaveTimeRange)->where('sector_id', auth()->user()->role_id)->valid()->count();

            $contactLeaveTodayCount = Contract::where(function ($q){
                $this->filter($q);
            })->where('sector_id', auth()->user()->role_id)->whereBetween('to', $EnterLeaveTimeRange)->where('sector_id', auth()->user()->role_id)->valid()->count();
//        }
      }

      $units = Unit::where(function ($q){
          if(request('beach'))
          {
              $q = $q->where('beach_id', request('beach'));
          }

          return $q->where('sector_id', auth()->user()->role_id);
      })->count();

      $empty    = $units - $contactEnterTodayCount;
      $empty    = $empty > 0 ? $empty : 0;
      $reserved = $contactEnterTodayCount;
      $reserved    = $reserved > 0 ? $reserved : 0;

//      event(new NewContract('This is test'));

        return view('admin.dashboard',[
              'rows' => $rows,
              'contactEnterTodayCount' => $contactEnterTodayCount,
              'ContractDayCount' => $ContractDayCount,
              'contactLeaveTodayCount' => $contactLeaveTodayCount,
              'empty' => $empty,
              'reserved' => $reserved
        ]);
    }

    public function requests()
    {
      $rows = Unit::where('status', 0)->get();

        if (request()->not != ''){
            auth()->user()->unreadNotifications->where('id', request()->not)->markAsRead();
        }

      return view('admin.units.index',[
        'rows' => $rows,
      ]);
    }

    public function terminate($unit_id)
    {
        $unit = Unit::findOrFail($unit_id);

        $unit->status = 2;
        $unit->is_terminated = 1;

        if ($unit->save()){
            History::create([
                'hismodel_id' => $unit_id,
                'hismodel_type' => 'App\Models\Unit',
                'type' => 'terminated',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return back()->withSuccess('تم شطب الوحدة بنجاح.');
        }

        return back()->with('error', 'هناك خطأ حدث عندما حاولنا شطب الوحدة ، برجاء المحاولة لاحقاََ.');
    }

    public function request_status($type, $id)
    {
      $row = Unit::findOrFail($id);

      $valid_to = $row->type == 'investor' ? Carbon::parse(request()->valid_to)->format('Y-m-d') : '';

      $row_data['status'] = $type;

      if (!$row->status)
        $row_data['valid_to'] = $valid_to;

      $row->update($row_data);

      $status_history = $type == 1 ? 'accepted' : 'reject';

      History::create([
          'hismodel_id' => $row->id,
          'hismodel_type' => 'App\Models\Unit',
          'type' => $status_history,
          'user_id' => auth()->id(),
          'created_at' => Carbon::now(),
          'updated_at' => ''
      ]);

      $user = User::find($row->user_id);

      if (!is_null($user)){
          $user->update([
              'blocked' => 0,
              'blocked_note' => ''
          ]);
      }

      return back()->withSuccess('عملية ناجحة');
    }
}
