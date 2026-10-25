<?php

namespace App\Filament\Site\Pages;

use App\Livewire\ActiveSessions;
use App\Models\UserSession;
use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class EditProfile extends BaseEditProfile
{
    /**
     * @var array{currentPassword: string}
     */
    public array $terminateSessions = [
        'currentPassword' => '',
    ];

    public static function getLabel(): string
    {
        return 'My profile';
    }

    public function getHeading(): string
    {
        return 'Manage your account';
    }

    public function getSubheading(): string
    {
        return 'Update your profile details and keep your password secure.';
    }

    public function getMaxContentWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)
            ->inlineLabel(false);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
                ...Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
                Section::make('Active sessions')
                    ->description('Devices currently signed in to your account.')
                    ->icon(Heroicon::OutlinedComputerDesktop)
                    ->schema([
                        Form::make([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('currentPassword')
                                        ->label('Current password')
                                        ->password()
                                        ->revealable()
                                        ->autocomplete('current-password')
                                        ->placeholder('Confirm your current password')
                                        ->required()
                                        ->rule('current_password:web'),
                                    Actions::make([
                                        Action::make('terminateOtherSessionsButton')
                                            ->label('Terminate other sessions')
                                            ->color('danger')
                                            ->icon('heroicon-o-arrow-right-on-rectangle')
                                            ->submit('terminate-sessions-form'),
                                    ])
                                        ->fullWidth()
                                        ->verticalAlignment('end'),
                                ]),
                        ])
                            ->id('terminate-sessions-form')
                            ->statePath('terminateSessions')
                            ->livewireSubmitHandler('terminateOtherSessions'),
                        Livewire::make(ActiveSessions::class),
                    ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account information')
                    ->description('Update your profile information and password.')
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Profile information')
                            ->columns(1)
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                            ]),
                        Fieldset::make('Password')
                            ->columns(1)
                            ->schema([
                                $this->getCurrentPasswordFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),
                    ]),
            ]);
    }

    public function terminateOtherSessions(): void
    {
        /** @var Form $terminationForm */
        $terminationForm = $this->getSchema('content')->getComponent('terminateSessions');
        $data = $terminationForm->getChildSchemas()['default']->getState();
        $currentPassword = $data['currentPassword'];
        $currentSessionId = session()->getId();
        $userId = Auth::id();

        Auth::logoutOtherDevices($currentPassword);

        session()->put([
            'password_hash_'.Filament::getAuthGuard() => Auth::user()->getAuthPassword(),
        ]);

        UserSession::query()
            ->where('user_id', $userId)
            ->whereKeyNot($currentSessionId)
            ->delete();

        $this->terminateSessions['currentPassword'] = '';

        Notification::make()
            ->title('Other sessions terminated')
            ->success()
            ->send();
    }
}
