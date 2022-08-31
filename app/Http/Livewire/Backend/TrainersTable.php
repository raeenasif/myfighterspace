<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\trainers as trainers;

/**
 * Class UsersTable.
 */
class TrainersTable extends DataTableComponent
{
    /**
     * @var
     */
    public $status;

    /**
     * @var array|string[]
     */

    public array $filterNames = [
        'type' => 'User Type',
        'verified' => 'E-mail Verified',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];

    public function query(): Builder
    {
        $query = User::where('type', 'trainer');
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        } elseif ($this->status === 'deactivated') {
            $query = $query->onlyDeactivated();
        } else {
            $query = $query->onlyActive();
        }

        return $query
            ->when($this->getFilter('search'), fn ($query, $term) => $query->search($term))
            ->when($this->getFilter('active'), fn ($query, $active) => $query->where('active', $active === 'yes'))
            ->when($this->getFilter('verified'), fn ($query, $verified) => $verified === 'yes' ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at'));
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            'active' => Filter::make('Active')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
            'verified' => Filter::make('E-mail Verified')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
        ];
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('E-mail'), 'email')
                ->sortable(),
            Column::make(__('Name'))
                ->sortable(),
            Column::make(__('Classes '), 'Classes')
                ->sortable(),
            Column::make(__('payouts'), 'payouts')
                ->sortable(),
            Column::make(__('Created'), 'created')
                ->sortable(),
            Column::make(__('Status'), 'status')
                ->sortable(),
            Column::make(__('Actions')),
        ];
    }

    /**
     * @return string
     */
    public function rowView(): string
    {
        return 'backend.trainers.includes.row';
    }

    public function exportSelected()
    {
        // Do something with the selected rows.
        if ($this->selectedRowsQuery->count() > 0) {
            //return (new ScansExport($this->selectedRowsQuery))->download($this->tableName.'.xlsx');
            return Excel::download(new trainers($this->selectedRowsQuery), 'report.xlsx');
        }

        // Not included in package, just an example.
        //$this->notify(__('You did not select any users to export.'), 'danger');
    }
}
