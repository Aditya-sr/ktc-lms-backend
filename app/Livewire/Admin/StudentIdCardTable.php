<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\StudentIdCard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StudentIdCardTable extends DataTableComponent
{
    protected $model = StudentIdCard::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setBulkActions([
                'generatePdf' => 'Generate PDF',
                'renewCards' => 'Renew Selected Cards',
                'deactivateCards' => 'Deactivate Selected',
            ]);
        $this->setThAttributes(function ($column) {
            return ['default' => false, 'class' => 'px-4 py-3 text-left text-xs font-medium whitespace-normal text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400'];
        });

        $this->setTdAttributes(function ($column, $row, $colIndex) {
            $classes = ['px-4 py-4 whitespace-normal font-medium dark:text-white text-xs'];

            return [
                'default' => false,
                'class' => implode(' ', $classes)
            ];
        });
        $this->setSearchFieldAttributes(StyleConstants::SEARCH);
    }

    public function builder(): Builder
    {
        return StudentIdCard::query()
            ->with(['student', 'organization'])
            ->where('organization_id', Auth::user()->organization_id);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("User id", "user_id")
                ->searchable(),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Student detail id", "student_detail_id")
                ->searchable()->hideIf(1),
            Column::make("Card number", "card_number")
                ->searchable(),
            Column::make("Issue date", "issue_date")
                ->searchable(),
            Column::make("Expiry date", "expiry_date")
                ->searchable(),
            Column::make("Status", "status")
                ->format(
                    fn($value) => '<span class="px-2 py-1 text-xs rounded-full ' . ($value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') . '">' . ucfirst($value) . '</span>'
                )
                ->html()
                ->sortable(),
            Column::make("Qr code", "qr_code")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }

    public function generatePdf()
    {
        if (count($this->getSelected()) > 0) {
            $cards = StudentIdCard::with(['student', 'organization'])
                ->whereIn('id', $this->getSelected())
                ->get();

            $pdf = Pdf::loadView('admin.id-cards.bulk-pdf', [
                'cards' => $cards,
                'organization' => Auth::user()->organization
            ]);

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'id-cards-' . now()->format('Y-m-d') . '.pdf'
            );
        }
    }

    public function renewCards()
    {
        if (count($this->getSelected()) > 0) {
            StudentIdCard::whereIn('id', $this->getSelected())
                ->update([
                    'expiry_date' => now()->addYears(2),
                    'status' => 'active'
                ]);

            $this->dispatch('toast', message: 'Selected cards renewed successfully!');
        }
    }

    public function deactivateCards()
    {
        if (count($this->getSelected()) > 0) {
            StudentIdCard::whereIn('id', $this->getSelected())
                ->update(['status' => 'inactive']);

            $this->dispatch('toast', message: 'Selected cards deactivated!');
        }
    }
}
