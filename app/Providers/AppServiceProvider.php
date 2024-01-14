<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('user', function (Request $request) {
            return $request->user() ? array_merge($request->user()->only('displayname', 'username', 'avatar'), ['isPrivate' => $request->user()->isPrivateProfile()]) : null;
        });

        Inertia::share('errors', function () {
            return Session::get('errors')
                ? Session::get('errors')->getBag('default')->getMessages()
                : [];
        });

        Inertia::share('referer', function (Request $request) {
            return $request->headers->get('referer');
        });
    }
}
