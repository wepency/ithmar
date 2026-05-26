<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContractDraftController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'FrontEnd'])->except('showDraft');
    }

    public function draft($code){
        $page_title = 'مسودة العقد';
        $code = contractMixDecode($code);

        $contract = Contract::where('code', $code)->first();

        if (is_null($contract))
            abort(404);

        if (!checkUserContractCode($contract) && !ContractExists($contract))
            abort(404);

        return view('contracts.draft', compact('page_title', 'contract'));
    }

    public function showDraft($code, $token){
        $contract = Contract::with('unit', 'beach', 'sector', 'user', 'cars', 'companions', 'services')
            ->where('code', $code)
            ->where('token', $token)
            ->first();

        if (is_null($contract))
            abort(404);

        return $this->renderDraftView($contract);
    }

    private function renderDraftView($contract)
    {
        $settings = Setting::first();
        $services = $contract->services ? unserialize($contract->services->service_data) : [];

        $tenant_barcode = $this->getDraftImage($contract->attachment_1);
        $with_tenant_barcode = $this->getDraftImage($contract->attachment_2);

        $is_cancelled = (bool) $contract->is_cancelled;
        $draft_share_link = getDraftLink($contract);
        $tenant_title = trans('admin.' . $contract->tenant_title);
        $with_tenant_title = trans('admin.' . $contract->with_tenant_title);

        $beach_terms = $contract->beach ? $contract->beach->terms : collect();

        return view('contracts.show-draft-print', compact(
            'contract', 'settings', 'services',
            'tenant_barcode', 'with_tenant_barcode',
            'is_cancelled', 'draft_share_link',
            'tenant_title', 'with_tenant_title',
            'beach_terms'
        ));
    }

    private function getDraftImage($file_name)
    {
        if ($file_name && file_exists(public_path('uploads/' . $file_name))) {
            return asset('uploads/' . $file_name);
        }
        return false;
    }
}
