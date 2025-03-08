<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use Illuminate\Console\Command;
use App\Models\KompetensiPegawai;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;

class RandomizeKompetensiPegawai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'randomize:kompetensi-pegawai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pegawai = Pegawai::select('pegawai.id as id_pegawai', 'pegawai.id_jabatan', 'standar_kompetensi.id as id_standar_kompetensi')
        ->join('jabatan', 'pegawai.id_jabatan', 'jabatan.id')
        ->join('standar_kompetensi', 'jabatan.id', 'standar_kompetensi.id_jabatan')
        ->join('users', 'pegawai.id_user', 'users.id')
        ->where('users.role', 'pegawai')
        ->get();

        KompetensiPegawai::truncate();
        DB::beginTransaction();
        foreach ($pegawai as $p) {
            try {
                KompetensiPegawai::updateOrCreate(
                    [
                    'id_pegawai' => $p->id_pegawai,
                    'id_standar_kompetensi' => $p->id_standar_kompetensi
                    ],
                    [
                        'kpi' => rand(50, 90)
                    ]
                );
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error($e->getMessage());
                return;
            }
        }

        $this->info('Randomize kompetensi pegawai success');
        return 1;
    }
}
