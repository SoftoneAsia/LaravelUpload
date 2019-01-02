<?php

namespace SoftoneAsia\LaravelUpload;

use Illuminate\Support\ServiceProvider;

class SOUploadProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Include the package classmap autoloader
        // if (\File::exists(__DIR__.'/../vendor/autoload.php'))
        // {
        //     include __DIR__.'/../vendor/autoload.php';
        // }

                
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        
//         $this->publishes([
//             __DIR__.'/config.php' => config_path('so_upload.php'),
//         ]);

        //$this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'courier');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
