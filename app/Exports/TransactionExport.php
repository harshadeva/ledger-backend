<?php

namespace App\Exports;

use App\Enums\TransactionTypeEnum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithColumnFormatting, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions->map(function ($item,$index) {
            $row['id'] = $index + 1;
            $row['date'] = $item->date;
            $row['type'] = strtoupper($item->type);
            $row['account'] = strtoupper($item->account->name);
            $row['ref'] = $item->ref;
            $row['income'] =  strtoupper($item->type) == TransactionTypeEnum::INCOME ? $item->amount : null;
            $row['expense'] =  strtoupper($item->type) == TransactionTypeEnum::EXPENSE ? $item->amount : null;
            $row['project'] = $item->project->name  ?? '';
            $row['stakeholder'] = $item->stakeholder->name;
            $row['description'] = $item->out_description != null ? $item->out_description :  $item->description;
            return $row;
        });
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0',
            'G' => '#,##0'
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Type',
            'Account',
            'Reference',
            'Income',
            'Expense',
            'Project',
            'Stakeholder',
            'Description'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }
}
