<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['code','name','category','description','kind','quantity_total','status'];
    }

    public function array(): array
    {
        return [
            ['CAM-01','Kamera Mirrorless','Kamera','-', 'loanable',10,'active'],
            ['MIC-01','Mic Wireless','Audio','', 'loanable',5,'active'],
        ];
    }
}
