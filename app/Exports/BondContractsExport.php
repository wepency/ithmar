<?php

namespace App\Exports;

use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BondContractsExport implements FromView
{
    private $contracts;
    private $contracts_total;
    private $contracts_count;
    private $sector_total;
    private $ithmar_total;

    public function __construct($contracts, $contracts_total, $contracts_count, $sector_total, $ithmar_total)
    {
        $this->contracts = $contracts;
        $this->contracts_total = $contracts_total;
        $this->contracts_count = $contracts_count;
        $this->sector_total = $sector_total;
        $this->ithmar_total = $ithmar_total;
    }

    public function view(): View
    {
        return view('admin.bonds.sheet', [
            'contracts' => $this->contracts,
            'contracts_total' => $this->contracts_total,
            'contracts_count' => $this->contracts_count,
            'sector_total' => $this->sector_total,
            'ithmar_total' => $this->ithmar_total
        ]);
    }
}
