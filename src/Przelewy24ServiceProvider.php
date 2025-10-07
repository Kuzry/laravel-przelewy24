<?php

namespace Kuzry\Przelewy24;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Kuzry\Przelewy24\Api\Data\BasicAuthData;
use Kuzry\Przelewy24\Api\Przelewy24Connector;
use Kuzry\Przelewy24\Data\Przelewy24ConfigData;
use Kuzry\Przelewy24\Facades\Przelewy24 as Przelewy24Facade;

class Przelewy24ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/przelewy24.php' => config_path('przelewy24.php'),
        ], 'przelewy24-config');

        if (class_exists(Inertia::class) && config('przelewy24.auto_register_frontend_data')) {
            Inertia::share('przelewy24', fn () => Przelewy24Facade::getFrontendData());
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/przelewy24.php',
            'przelewy24'
        );

        $this->app->singleton(Przelewy24ConfigData::class, fn () => Przelewy24ConfigData::fromConfig());

        $this->app->bind(Przelewy24::class, function ($app, $params) {
            $posKey = $params['pos_key'] ?? 'default';

            /** @var Przelewy24ConfigData $config */
            $config = $app->make(Przelewy24ConfigData::class);

            if (! isset($config->pos[$posKey])) {
                throw new \InvalidArgumentException("POS '{$posKey}' not found in configuration");
            }

            return new Przelewy24(
                $config,
                new Przelewy24Connector(
                    $config->environment,
                    BasicAuthData::from([
                        'username' => $config->pos[$posKey]->posId,
                        'password' => $config->pos[$posKey]->reportKey,
                    ])
                )
            );
        });
    }
}
