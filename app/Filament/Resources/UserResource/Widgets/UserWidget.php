<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\LineChartWidget;


class UserWidget extends LineChartWidget
{
    protected static ?string $heading = 'Line Chart Widget';
    // protected static string $view = 'filament.widgets.custom-dashboard-widget';


    protected function getData(): array
    {
        return [
            'count' => \App\Models\User::count(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
