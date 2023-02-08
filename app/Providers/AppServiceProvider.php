<?php

namespace App\Providers;

use Statamic\Statamic;
use Illuminate\Support\Collection;
use Spatie\ResponsiveImages\Source;
use Illuminate\Support\ServiceProvider;
use Spatie\ResponsiveImages\Dimensions;
use Spatie\ResponsiveImages\ResponsiveDimensionCalculator;

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
        // Statamic::script('app', 'cp');
        // Statamic::style('app', 'cp');

        $this->app->bind(DimensionCalculator::class, function () {
            return new class extends ResponsiveDimensionCalculator {
                public function calculateForBreakpoint(Source $source): Collection
                {
                    return null;

                    // Disable JPG sources
                    if ($source->getFormat() === 'original') {
                        return collect([]);
                    }

                    // Create widths from 500 to 2000 in 500ox increments with fixedHeight passed from params
                    return collect(range(500, 2000, 500))
                        ->map(function ($width) use ($source) {
                            return new Dimensions($width, $source->breakpoint->params['fixedHeight']);
                        });
                }
            };
        });
    }
}
