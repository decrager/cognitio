<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusBadge extends Component
{
    public $status;
    public $warna_status;
    public $status_text;

    public function __construct($status)
    {
        $this->status = (int) $status;

        $this->warna_status = match($this->status) {
            1 => 'color:orange;font-weight:bold;', // Usulan (kuning)
            2 => 'color:blue;font-weight:bold;',   // Konfirmasi (biru)
            3 => 'color:red;font-weight:bold;',    // Tidak Dikonfirmasi (merah)
            4 => 'color:green;font-weight:bold;',  // Ditetapkan (hijau)
            default => 'color:black;',             // Default (hitam)
        };

        $this->status_text = match($this->status) {
            1 => 'Usulan',
            2 => 'Konfirmasi',
            3 => 'Tidak Dikonfirmasi',
            4 => 'Ditetapkan',
            default => 'Tidak Diketahui',
        };
    }

    public function render()
    {
        return view('components.status-badge');
    }
}
