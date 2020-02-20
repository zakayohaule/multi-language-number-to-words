<?php

namespace ZakayoHaule\N2W;

use Illuminate\Support\ServiceProvider;

class N2WServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__. "/config/n2w.php", 'n2w');
        $this->publishes([
            __DIR__."/config/n2w.php" => config_path("n2w.php")
        ]);
    }

    public function register()
    {
    }
}