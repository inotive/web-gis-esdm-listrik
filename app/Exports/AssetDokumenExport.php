<?php

namespace App\Exports;

use App\Models\AssetDokumen;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssetDokumenExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return AssetDokumen::query()
            ->with(['asset.unitKerja'])
            ->when($this->filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_sertif', 'like', "%{$search}%")
                       ->orWhere('nama_sertifikat', 'like', "%{$search}%")
                       ->orWhere('no_dokumen', 'like', "%{$search}%")
                       ->orWhereHas('asset', function ($qa) use ($search) {
                           $qa->where('nama_asset', 'like', "%{$search}%")
                              ->orWhere('kode_asset', 'like', "%{$search}%");
                       });
                });
            })
            ->when($this->filters['status'] ?? null, function ($q, $status) {
                if ($status === 'digitalized') {
                    $q->whereNotNull('file_sertif');
                } elseif ($status === 'link') {
                    $q->whereNotNull('link_sertif');
                } elseif ($status === 'none') {
                    $q->whereNull('file_sertif')->whereNull('link_sertif');
                } elseif ($status === 'confirmed') {
                    $q->where('has_konfir', true);
                } elseif ($status === 'unconfirmed') {
                    $q->where(function ($qq) {
                        $qq->whereNull('has_konfir')->orWhere('has_konfir', false);
                    });
                }
            })
            ->when($this->filters['unit_kerja'] ?? null, function ($q, $unit) {
                $q->whereHas('asset', fn($qa) => $qa->where('unit_kerja_id', (int) $unit));
            })
            ->when($this->filters['tgl_from'] ?? null, fn($q, $d) => $q->whereDate('tgl_sertif', '>=', $d))
            ->when($this->filters['tgl_to'] ?? null, fn($q, $d) => $q->whereDate('tgl_sertif', '<=', $d))
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'Kode Asset',
            'Nama Asset',
            'Unit Kerja',
            'No Sertifikat',
            'Tgl Sertifikat',
            'Nama Sertifikat',
            'No Dokumen',
            'Tgl Dokumen',
            'Tgl Perolehan',
            'Tgl Buku',
            'Sudah Konfirmasi?',
            'Status Sertifikat',
            'Keterangan',
            'Jenis Bukti (File/Link/Tidak Ada)',
            'URL File / Link',
        ];
    }

    public function map($row): array
    {
        $jenis = 'Tidak Ada';
        $url   = '';

        if ($row->file_sertif) {
            $jenis = 'File';
            $url   = Storage::disk('public')->url($row->file_sertif);
        } elseif ($row->link_sertif) {
            $jenis = 'Link';
            $url   = $row->link_sertif;
        }

        return [
            $row->asset->kode_asset ?? '-',
            $row->asset->nama_asset ?? '-',
            $row->asset->unitKerja->nama_unit ?? '-',
            $row->no_sertif ?? '-',
            optional($row->tgl_sertif)->format('Y-m-d'),
            $row->nama_sertifikat ?? '-',
            $row->no_dokumen ?? '-',
            optional($row->tanggal_dokumen)->format('Y-m-d'),
            optional($row->tanggal_oleh)->format('Y-m-d'),
            optional($row->tanggal_buku)->format('Y-m-d'),
            $row->has_konfir ? 'Ya' : 'Tidak',
            $row->sts_sertif ?? '-',
            $row->ket_sertif ?? '-',
            $jenis,
            $url,
        ];
    }
}
