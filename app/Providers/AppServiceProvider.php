<?php

namespace App\Providers;
use DB;
use Config;
use Illuminate\Support\ServiceProvider;
use App\Model\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      foreach (Setting::all() as $setting) {
        Config::set('setting.'.$setting->setting_key, $setting->setting_value);
      }
    }
}
