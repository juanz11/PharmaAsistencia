<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
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
        
        Paginator::useBootstrapFive();
    }
}
