<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Task;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;

final class TaskBoard extends BoardPage
{
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Task Board';

    protected static ?string $title = 'Task Board';

    public function board(Board $board): Board
    {
        return $board
            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('title')
            ->columnIdentifier('status')
            ->positionIdentifier('position') // Enable drag-and-drop with position field
            ->columns([
                Column::make('todo')->label('To Do')->color('gray'),
                Column::make('in_progress')->label('In Progress')->color('blue'),
                Column::make('completed')->label('Completed')->color('green'),
            ])
            ->cardSchema(fn (Schema $schema) => $schema->components([
                TextEntry::make('description')
                    ->hiddenLabel()
                    ->limit(72),
            ]))
            ->cardActions([
                EditAction::make()
                    ->model(Task::class)
                    ->schema(function (Schema $schema): Schema {
                        return $schema
                            ->components([
                                TextInput::make('title')
                                    ->maxLength(255)
                                    ->required(),
                                Textarea::make('description')
                                    ->maxLength(65535),
                            ]);
                    }),
                DeleteAction::make()->model(Task::class),
            ])
            ->columnActions([
                CreateAction::make()
                    ->model(Task::class)
                    ->iconButton()
                    ->icon('heroicon-o-plus')
                    ->schema(function (Schema $schema): Schema {
                        return $schema
                            ->components([
                                TextInput::make('title')
                                    ->maxLength(255)
                                    ->required(),
                                Textarea::make('description')
                                    ->maxLength(65535),
                            ]);
                    })
                    ->mutateDataUsing(function (array $data, array $arguments): array {
                        if (isset($arguments['column'])) {
                            $data['status'] = $arguments['column'];
                            $data['position'] = $this->getBoardPositionInColumn($arguments['column']);
                        }

                        return $data;
                    }),
            ]);
    }

    public function getEloquentQuery(): Builder
    {
        return Task::query();
    }
}
