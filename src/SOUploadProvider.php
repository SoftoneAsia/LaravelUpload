<?php

namespace SOA\LaravelUpload;

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
        if (\File::exists(__DIR__.'/../vendor/autoload.php'))
        {
            include __DIR__.'/../vendor/autoload.php';
        }

        /**
        * Routes
        */
        
        // Method 1
        // A simple include, but in the routes files, controllers should be called by their namespace
        // include __DIR__.'/routes/web.php';
        
        // Method 2
        // A Better method, extend the app routes by adding a group with a specified namespace

        $this->app->router->group(['namespace' => 'SOA\LaravelUpload\App\Http\Controllers'],
            function(){
                require __DIR__.'/routes/web.php';
            }
        );

        /**
        * Views
        * use: view('PackageName::view_name');
        */
        $this->loadViewsFrom(__DIR__.'/resources/views', 'SOA\LaravelUpload');

        /*
        * php artisan vendor:publish
        * Existing files will not be published
        */

        // Publish views to resources/views/vendor/vendor-name/package-name
        $this->publishes(
            [
                __DIR__.'/resources/views' => base_path('resources/views/soa/upload'),
            ]
        );

        // Publish assets to public/vendor/vendor-name/package-name
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/softoneasia/laravelupload'),
        ], 'public');

        // Publish configurations to config/vendor/vendor-name/package-name
        // Config::get('vendor.yk.laravel-package-example')
        $this->publishes([
            __DIR__.'/config' => config_path('vendor/softoneasia/laravelupload'),
        ]);

        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
        $kernel->pushMiddleware('SOA\LaravelUpload\App\Http\Middleware\MiddlewareExample');

        /**
         * Register migrations, so they will be automatically run when the php artisan migrate command is executed.
         */
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        /**
         * Register commands, so you may execute them using the Artisan CLI.
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                \SOA\LaravelUpload\App\Console\Commands\Hello::class,
            ]);
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
        * Merge configurations
        */
        // $this->mergeConfigFrom(
        //     __DIR__.'/config/app.php', 'packages.Yk.LaravelPackageExample.app'
        // );

        // $this->app->bind('ClassExample', function(){
        //     return $this->app->make('Yk\LaravelPackageExample\Classes\ClassExample');
        // });

    }
}
