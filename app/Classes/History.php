<?php

namespace App\Classes;

use App\Http\Controllers\API\validateContract;
use App\Models\Contract;
use App\Models\History as HistoryModel;
use Illuminate\Database\Eloquent\Builder;

class History
{
    private $type = [
        'create'     => 'انشاء',
        'update'     => 'تعديل',
        'view'       => 'عرض',
        'closed'     => 'غلق',
        'reset'      => 'فتح',
        'accepted'   => 'قبول',
        'delete'     => 'حذف',
        'reject'     => 'رفض',
        'blocked'    => 'حظر',
        'unblocked'  => 'رفع الحظر',
        'terminated' => 'شطب'
    ];

    protected $morph = [
        'permissions' => [
            'name' => 'مجموعة الصلاحيات',
            'model' => 'App/Models/Role'
        ],
        'contracts' => [
            'name' => 'العقد',
            'model' => 'App/Models/Contract'
        ],
        'attachments' => [
            'name' => 'مرفق الوحدة',
            'model' => 'App/Models/Attachment'
        ]
    ];

    public $models = array(
        'Spatie\Permission\Models\Role' => [
            'name' => 'الصلاحيات',
            'column' => 'code',
            'id' => 'code',
            'link' => ''
        ],
        'App\Models\Beach' => [
            'name' => 'شاطئ',
            'column' => 'beach',
            'id' => '',
            'link' => ''
        ],
        'App\Models\Contract' => [
            'name' => 'عقد',
            'column' => 'code',
            'id' => 'code',
            'link' => 'contract/show'
        ],
        'App\Models\Sector' => [
            'name' => 'قطاع',
            'column' => 'sector_name',
            'id' => '',
            'link' => ''
        ],
        'App\Models\Unit' => [
            'name' => 'وحدة',
            'column' => 'unit_number',
            'id' => '',
            'link' => ''
        ],
        'App\Models\User' => [
            'name' => 'مستخدم',
            'column' => 'name',
            'id' => '',
            'link' => ''
        ],
        'App\Models\Bond' => [
            'name' => 'سند',
            'column' => 'id',
            'id' => '',
            'link' => ''
        ],
        'App\Models\Wallet' => [
            'name' => 'محفظة',
            'column' => 'id',
            'id' => '',
            'link' => ''
        ],
        'App\Models\Attachment' => [
            'name' => 'مرفق',
            'column' => 'id',
            'id' => '',
            'link' => ''
        ]
    );

    public function __construct()
    {

    }

    public function historyArray(){
        return [
            'permissions' => [
                'name' => 'الصلاحيات',
                'model' => 'App/Models/Role',
                'link' => ''
            ],
            'sectors' => [
                'name' => 'القطاعات',
                'model' => 'App/Models/Sector',
                'link' => ''
            ],
            'bonds' => [
                'name' => 'السندات',
                'model' => 'App/Models/Bond',
                'link' => ''
            ],
            'beaches' => [
                'name' => 'الشواطئ',
                'model' => 'App/Models/Role',
                'link' => ''
            ],
            'units' => [
                'name' => 'الصلاحيات',
                'model' => 'App/Models/Role',
                'link' => ''
            ],
            'contracts' => [
                'name' => 'الصلاحيات',
                'model' => 'App/Models/Role',
                'link' => ''
            ],
            'clients' => [
                'name' => 'الصلاحيات',
                'model' => 'App/Models/Role',
                'link' => ''
            ]
        ];
    }

    public function getAllHistory($model, $id, $name = ''){
//        auth()->user()->givePermissionTo(29);
        $output = '';
        $histories = HistoryModel::select('id', 'user_id', 'type', 'created_at')->with('user')->where('hismodel_id', $id)->where('hismodel_type', $model)->orderBy('created_at', 'ASC')->paginate(15);

        return $this->render($histories, $name);
    }

    protected function getModelName($name){
        if (isset($this->morph[$name]))
            return $this->morph[$name]['name'];
    }

    protected function getUserName($history){
        if ($history->user->name){
            if ($history->user_id == auth()->id())
                return 'لقد قمت انت';

            return ' لقد قام '.($history->user->name ?? '');

        }
        return 'غير معروف';
    }

    protected function getDate($date){
        if (!is_null($date)){
            return $date->format('d / m / Y') . ' في الساعة '. $date->format('H:i:s');
        }

        return '';
    }

    public function getUserHistory($request, $id){

        $type = ['create','update','accepted'];
        $units = ['closed', 'reset'];
        $attachments = ['view'];

        $requests = ['phonenumber', 'from', 'to'];

        $histories = HistoryModel::select('id', 'user_id', 'hismodel_type', 'hismodel_id','type', 'created_at')->with('hismodel', 'user')->where('user_id', $id);

        if ($request->type != ''){
            if (in_array($request->type, $type)) {
                $histories = $histories->where('hismodel_type', 'App\Models\Contract')->where('type', $request->type);
            }

            if (in_array($request->type, $units)) {
                $histories = $histories->where('hismodel_type', 'App\Models\Unit')->where('type', $request->type);
            }

            if (in_array($request->type, $attachments)) {
                $histories = $histories->where('hismodel_type', 'App\Models\Attachment')->where('type', $request->type);
            }
        }

        if ($request->phonenumber != '' || $request->from != '' || $request->to != '') {
            $histories->wherehasmorph('hismodel', [Contract::class], function (Builder $builder) use ($request){
                if ($request->phonenumber != ''){
                    $builder->whereHas('user', function (Builder $b) use ($request){
                        $b->where('phonenumber', 'like', '%'.$request->phonenumber.'%');
                    });
                }

                if ($request->from != '') {
                    $builder->whereDate('from', '>=', $request->from);
                }

                if ($request->to != '') {
                    $builder->whereDate('to', '<=', $request->to);
                }
            });
        }

        $histories = $histories->orderBy('created_at', 'DESC')->paginate(15);
        return $this->fullRender($histories);
    }

    private function fullRender($histories){
        $output = '';
        $output .= '<div class="table-responsive">';
        $output .= '<table id="example1" class="table table-bordered table-striped mt-0">';
        $output .= '<thead>';
        $output .= '<tr>';
//        $output .= '<th>'.$this->getModelName($name).'</th>';
        $output .= '<th></th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        foreach ($histories as $history){
            $model = $this->models[$history->hismodel_type];

            $output .= '<tr><td>';
            $output .= 'لقد قام المستخدم بـ';
            $output .= $this->type[$history->type]."\t";
            $output .= $model['name'] ?? '';
            $output .= ":\t";

            $obj_id = $history->hismodel->{$model['id']} ?? '';

            if (isset($model['link']) && !empty($model['link']) && $obj_id != '' && $model['id'] != ''){
                $output .= "<a href='".admin_url($model['link'].'/'.$obj_id)."'>";
            }

            if ($history->hismodel_type == "App\Models\Attachment"){
                $unit = @$history?->hismodel?->attachmentable?->unit_number ?? '';

                $output .= "<a href='".admin_url("units?unit_number=".$unit)."'>";
                $output .= $unit;
                $output .= "</a>";
            }else
                $output .= @$history->hismodel->{$model['column']} ?? ' غير معروف ';

            if (isset($model['link']) && !is_null($model['link']) && $obj_id != '' && $model['id'] != ''){
                $output .= "</a>";
            }

            $output .= " في {$this->getDate($history->created_at)}";

            $output .= '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';
        $output .= $histories->withQueryString();

        return $output;
    }

    private function render($histories, $name, $type = 'model'){
        $output = '';
        $output .= '<div class="table-responsive">';
        $output .= '<table id="example1" class="table table-bordered table-striped mt-0">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>'.$this->getModelName($name).'</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        foreach ($histories as $history){
            $output .= '<tr><td>';

            if ($type == 'model')
                $output .= "{$this->getUserName($history)} ب{$this->type[$history->type]} {$this->getModelName($name)} في {$this->getDate($history->created_at)}";
            else
                $output .= "";

            $output .= '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';
        $output .= $histories->links();

        return $output;
    }
}
