<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\UserCoin;

use Auth;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app['request']->server->set('HTTPS', true);

        view()->composer('*',function($view) {
            $cats      = Category::where('searchable', 1)->get();
            $catsAdmin = Category::all();
            $view->with('cates', $cats);
            $view->with('catsAdmin', $catsAdmin);
            if (Auth::user()) {
                $coin = UserCoin::where('user_id', Auth::user()->id)->first();
                if (!$coin) {
                    $coin = 0;
                } else {
                    $coin = $coin->coin;
                }
                $view->with('coin', $coin);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
