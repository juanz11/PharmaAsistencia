<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class DateTimeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Configurar la zona horaria predeterminada para Venezuela
        date_default_timezone_set('America/Caracas');
        Carbon::setLocale('es');
        
        // Configurar el formato predeterminado de Carbon
        Carbon::macro('venezuelaFormat', function () {
            return $this->format('g:i A');
        });
        
        Carbon::macro('venezuelaDateTime', function () {
            return $this->format('d/m/Y g:i A');
        });
        
        Carbon::macro('venezuelaDate', function () {
            return $this->format('d/m/Y');
        });
    }

    public function register()
    {
        //
    }
}
