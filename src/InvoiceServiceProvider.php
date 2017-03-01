<?php

namespace Paytech\Invoice\Core;

use Illuminate\Support\ServiceProvider;
use Paytech\Invoice\Core\Model\Invoice;
use Paytech\Invoice\Core\Service\InvoiceBuilder;

/**
 * Számlázás szolgáltató
 * publikálás:
 * php artisan vendor:publish --provider="Paytech\Invoice\Core\InvoiceServiceProvider" --tag=config --force
 *
 * Függőség: php 7.0, laravel 5.3, laravel-dompdf
 * - A dompdf fonts könyvtárába kell másolni a DejaVu Condensed fontokat
 * @package Paytech\Invoice\Core
 * @author Sáray Csaba <csaba.saray@paytech.hu>
 * @licence http://paytech.hu All rights reserved
 */
class InvoiceServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/translation', 'invoice');
        $this->loadViewsFrom(__DIR__.'/view', 'invoice');

        $this->publishes([
            __DIR__.'/invoice.php' => config_path('invoice.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Paytech\Invoice\Core\Contract\InvoiceManager', function ($app) {
            return new InvoiceBuilder();
        });
    }

    public function provides()
    {
        return ['Paytech\Invoice\Core\Contract\InvoiceManager'];
    }
}
