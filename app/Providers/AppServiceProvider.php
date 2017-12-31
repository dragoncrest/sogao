<?php

namespace App\Providers;

use App\Category;
use App\Models\Coin;

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
            $cats = Category::where('searchable', 1)->get();
            $view->with('cates', $cats);
            if (Auth::user()) {
                $coin = Coin::where('user_id', Auth::user()->id)->first();
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
