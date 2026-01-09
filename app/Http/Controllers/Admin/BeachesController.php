<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beach;
use App\Models\BeachTerm;
use App\Models\History;
use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeachesController extends Controller
{
    public function __construct()
    {
        $this->middleware('sectorAdmin');
    }

    public function index()
    {

        if(is_admin())
        {
            $rows = new Beach;

            if(request()->sector != ''){
                $rows = $rows->where('sector_id', request()->sector);
            }

          $rows = $rows->with('sector')->paginate();

          $sectors = Sector::all();
        }else{
          $sectors = [];
          $rows = Beach::with('sector')->where('sector_id', auth()->user()->role_id)->paginate();
        }

        return view('admin.beachs.index',[
          'rows' => $rows,
          'sectors' => $sectors
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
          'name' => 'required',
          'sector_id' => 'required',
          'allowed_cars' => 'required|numeric|max:10'
        ]);

        return DB::transaction(function () use ($data, $request){
            $data['beach'] = $data['name'];
            unset($data['name']);
            $data['allowed_cars'] = $request->allowed_cars;

            $beach = Beach::create($data);
            $terms = [];

            if(!empty($request->term)){
                foreach ($request->term as $key => $term){
                    $terms[$key]['beach_id'] = $beach->id;
                    $terms[$key]['term'] = $term['term'];
                    $terms[$key]['term_content'] = $term['term_content'];
                }

                BeachTerm::insert($terms);
            }

            History::create([
                'hismodel_id' => $beach->id,
                'hismodel_type' => 'App\Models\Beach',
                'type' => 'create',
                'user_id' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => ''
            ]);

            return back()->withSuccess("عملية ناجحة");
        });
    }

    public function update(Request $request,  $beach)
    {
      $beach = Beach::findOrFail($beach);

      $data = $request->validate([
        'name' => 'required',
        'sector_id' => 'required',
        'allowed_cars' => 'required|numeric|max:10'
      ]);

//      dd($request->all());
      return DB::transaction(function () use ($data, $request, $beach){
          $data['beach'] = $data['name'];
          unset($data['name']);
          $data['allowed_cars'] = $request->allowed_cars;

          $beach->update($data);

          if(!empty($request->term)){
              foreach ($request->term as $key => $term){
                  $terms[$key]['beach_id'] = $beach->id;
                  $terms[$key]['term'] = $term['term'];
                  $terms[$key]['term_content'] = $term['term_content'];
              }


              BeachTerm::where('beach_id', $beach->id)->delete();
              BeachTerm::insert($terms);
          }

          History::create([
              'hismodel_id' => $beach->id,
              'hismodel_type' => 'App\Models\Beach',
              'type' => 'update',
              'user_id' => auth()->id(),
              'created_at' => Carbon::now(),
              'updated_at' => ''
          ]);

          return back()->withSuccess("عملية ناجحة");
      });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Beach  $beach
     * @return \Illuminate\Http\Response
     */
    public function destroy( $beach)
    {
        $beach = Beach::findOrFail($beach);
        $beach->delete();
        return back()->withSuccess("عملية ناجحة");
    }

    public function show($id){
        $page_title = 'سجل الشاطئ';
        $history = (new \App\Classes\History)->getAllHistory('App\Models\Beach', $id, 'beaches');
        return view('admin.history', compact('history', 'page_title'));
    }
}
