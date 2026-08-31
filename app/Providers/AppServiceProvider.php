<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_START,
            fn(): View => view('announcements'),
        );

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
            $panelSwitch->panels([
                'project',
                'app',
            ]);
        });

        $this->customizeFilamentActions();
    }

    private function customizeFilamentActions(): void
    {
        Select::configureUsing(function (Select $select): void {
            $select->native(false);
        });

        CreateAction::configureUsing(function (CreateAction $action): void {
            $action->icon('heroicon-o-plus');
            $action->slideOver();
            $action->modalHeading(__('New entry'));
            $action->modalIcon('heroicon-o-plus');
        });

        \Filament\Tables\Actions\EditAction::configureUsing(function (\Filament\Tables\Actions\EditAction $action): void {
            $action->icon('heroicon-o-pencil');
            $action->slideOver();
            $action->modalHeading(__('Edit entry'));
            $action->modalIcon('heroicon-o-pencil');
        });

        TableCreateAction::configureUsing(function (TableCreateAction $action): void {
            $action->icon('heroicon-o-plus');
            $action->slideOver();
        });
    }
}
