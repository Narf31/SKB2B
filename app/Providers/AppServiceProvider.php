<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Orders\Orders;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Validators;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        Carbon::setLocale(config('app.locale'));

        view()->composer('partials.menu', function ($view) {
            $view->with('menuItems', Menu::all());
        });

        Validator::extend('inn', function ($attribute, $value, $parameters, $validator) {
            if (Validators::validateInn($value)) {
                return true;
            }
            return false;
        });

        Validator::extend('kpp', function ($attribute, $value, $parameters, $validator) {
            if (Validators::validatekpp($value)) {
                return true;
            }
            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
