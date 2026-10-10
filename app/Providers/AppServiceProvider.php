<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // The whole app uses Bootstrap, not Tailwind
        Paginator::useBootstrapFive();

        // Category menu for the public website header and footer
        View::composer('frontend.layouts.app', function ($view) {
            $view->with('navCategories', Category::where('status', true)
                ->whereHas('products', fn ($query) => $query->where('status', true))
                ->withCount(['products' => fn ($query) => $query->where('status', true)])
                ->orderByDesc('products_count')
                ->orderBy('name')
                ->limit(10)
                ->get());
        });
    }
}
