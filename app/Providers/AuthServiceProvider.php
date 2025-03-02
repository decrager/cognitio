<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        config(['app.locale' => 'id']);
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $user['name'] = Pegawai::where('id_user', $user->id)->first()->nama;
                $unit = UnitKerja::join('pegawai', 'unit_kerja.id', 'pegawai.id_unit')
                ->where('pegawai.id_user', $user->id)
                ->first();

                if ($user->role == 'biro-sdm') {
                    $user['role_name'] = 'Biro SDM';
                } else if ($user->role == 'unit-kerja') {
                    $user['role_name'] = 'Unit Kerja';
                } else if ($user->role == 'pegawai') {
                    $user['role_name'] = 'Pegawai';
                }

                $view->with([
                    'user' => $user,
                    'unit' => $unit,
                ]);
            }
        });
    }
}
