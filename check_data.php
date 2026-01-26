<?php

use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\User;

echo "Checking Database Data...\n";

$pCount = Permohonan::count();
echo "Total Permohonan (Master): " . $pCount . "\n";

if ($pCount > 0) {
    echo "Sample Permohonan:\n";
    foreach (Permohonan::take(5)->get() as $p) {
        echo "- ID: {$p->id}, Nama: {$p->nama}\n";
    }
} else {
    echo "WARNING: No Permohonan Master data found!\n";
}

$puCount = PermohonanUser::count();
echo "Total PermohonanUser (Transaksi): " . $puCount . "\n";

if ($puCount > 0) {
    echo "Sample PermohonanUser:\n";
    foreach (PermohonanUser::with(['user', 'permohonan'])->take(5)->get() as $pu) {
        echo "- ID: {$pu->id}, User: " . ($pu->user->name ?? 'N/A') . ", Permohonan: " . ($pu->permohonan->nama ?? 'N/A') . ", Status: {$pu->status}\n";
    }
} else {
    echo "WARNING: No PermohonanUser data found.\n";
}

$uCount = User::count();
echo "Total Users: " . $uCount . "\n";
