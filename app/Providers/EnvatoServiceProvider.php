<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EnvatoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    // public function boot()
    // {
    //     //
    // }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() .'/Helpers/Envato/ImportPaperData.php';
        require_once app_path() .'/Helpers/Envato/ImportAuthorData.php';
        require_once app_path() .'/Helpers/Envato/ImportAuthor_PaperData.php';
    }
}
