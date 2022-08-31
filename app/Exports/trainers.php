<?php

namespace App\Exports;

use Carbon\Carbon;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

use App\Domains\Auth\Models\User;

class trainers implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\FromQuery
     * @return \Illuminate\Support\Collection
     * @return Builder
     */
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
        return $this;
    }

    public function headings(): array
    {
        return [
            'E-mail',
            'Name',
            'Classes',
            'payouts',
            'Created',

        ];
    }



    use Exportable;

    public function query(): Builder
    {
        // $array = json_decode(json_encode($this->id), true);
        // $total_results = $this->id->toArray();
        // return Scan::query()->whereIn('id', $total_results);
        // dd($this->id);
        return $this->id;
    }

    public function map($row): array
    {

        return [
            $row->email,
            $row->name,
            'yes',
            $row->payouts,
            $row->created_at,
        ];
    }
}
