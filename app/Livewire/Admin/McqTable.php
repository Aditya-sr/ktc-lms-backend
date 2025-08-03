<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Mcq\McqQuestion;

class McqTable extends DataTableComponent
{
    protected $model = McqQuestion::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Standard", "standard_id")
                ->searchable(),
            Column::make("Section", "section_id")
                ->searchable(),
            Column::make("Chapter", "chapter_id")
                ->searchable(),
            Column::make("Topic", "topic_id")
                ->searchable(),
            Column::make("Created by", "created_by")
                ->searchable(),
            Column::make("Question text", "question_text")
                ->searchable(),
            Column::make("Time limit", "time_limit")
                ->searchable(),
            Column::make("active", "is_active")
                ->searchable(),
            Column::make("Date", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
