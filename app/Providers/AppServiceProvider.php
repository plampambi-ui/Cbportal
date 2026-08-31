<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_START,
            fn (): View => view('announcements'),
        );

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
            $panelSwitch->panels(['project', 'app']);
        });

        Select::configureUsing(function (Select $select): void {
            $select->native(false);
        });

        CreateAction::configureUsing(function (CreateAction $action): void {
            $action->icon('heroicon-o-plus')->slideOver();
        });

        TableCreateAction::configureUsing(function (TableCreateAction $action): void {
            $action->icon('heroicon-o-plus')->slideOver();
        });

        EditAction::configureUsing(function (EditAction $action): void {
            $action->icon('heroicon-o-pencil')->slideOver();
        });
    }
}
