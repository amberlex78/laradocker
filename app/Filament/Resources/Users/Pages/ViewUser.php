<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public ?string $previousUrl = null;

    public function mount(int|string $record): void
    {
        $this->previousUrl = url()->previous();

        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            EditAction::make(),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getInfolistContentComponent(),
                Actions::make([
                    Action::make('cancel')
                        ->label('Cancel')
                        ->color('gray')
                        ->url($this->previousUrl ?? UserResource::getUrl('index')),
                ]),
                $this->getRelationManagersContentComponent(),
            ]);
    }
}
