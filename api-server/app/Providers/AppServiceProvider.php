<?php

namespace App\Providers;

use App\Models\File as FileModel;
use App\Models\Genre;
use App\Models\Singer;
use App\Models\Song;
use App\Policies\FilePolicy;
use App\Policies\GenrePolicy;
use App\Policies\SingerPolicy;
use App\Policies\SongPolicy;
use App\Services\SongService;
use App\Services\SongServiceInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SongServiceInterface::class, SongService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        if (env('DB_LOG_ACTIVE', false)) {
            DB::listen(function ($query) {
                File::append(
                    storage_path('/logs/db-query.log'),
                    sprintf(
                        '[%s] %s [%s]%s%s',
                        date('Y-m-d H:i:s'),
                        $query->sql,
                        implode(', ', $query->bindings),
                        PHP_EOL,
                        PHP_EOL
                    )
                );
            });
        }

        Gate::policy(Song::class, SongPolicy::class);
        Gate::policy(Genre::class, GenrePolicy::class);
        Gate::policy(Singer::class, SingerPolicy::class);
        Gate::policy(FileModel::class, FilePolicy::class);
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
