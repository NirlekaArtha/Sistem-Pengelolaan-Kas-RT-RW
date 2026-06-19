<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $id_warga
 * @property int $id_jenis_iuran
 * @property int $id_rt
 * @property string $periode
 * @property \Illuminate\Support\Carbon|null $tanggal_bayar
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JenisIuranWarga $jenisIuran
 * @property-read \App\Models\KwitansiIuranWarga|null $kwitansi
 * @property-read \App\Models\RT $rt
 * @property-read \App\Models\Warga $warga
 * @method static \Database\Factories\IuranWargaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereIdJenisIuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereIdWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereTanggalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IuranWarga whereUpdatedAt($value)
 */
	class IuranWarga extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rt
 * @property string $jenis_iuran
 * @property numeric $jumlah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IuranWarga> $iuranWargas
 * @property-read int|null $iuran_wargas_count
 * @property-read \App\Models\RT $rt
 * @method static \Database\Factories\JenisIuranWargaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereJenisIuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisIuranWarga whereUpdatedAt($value)
 */
	class JenisIuranWarga extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rt
 * @property string $periode
 * @property numeric $total_pendapatan
 * @property numeric $total_pengeluaran
 * @property numeric $saldo_awal
 * @property numeric $saldo_akhir
 * @property numeric $total_pendapatan_bersih
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RT $rt
 * @method static \Database\Factories\KasBulananRTFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereSaldoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereSaldoAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereTotalPendapatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereTotalPendapatanBersih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereTotalPengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRT whereUpdatedAt($value)
 */
	class KasBulananRT extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rw
 * @property string $periode
 * @property numeric $total_pendapatan
 * @property numeric $total_pengeluaran
 * @property numeric $total_pendapatan_bersih
 * @property numeric $saldo_awal
 * @property numeric $saldo_akhir
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RW $rw
 * @method static \Database\Factories\KasBulananRWFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereIdRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereSaldoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereSaldoAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereTotalPendapatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereTotalPendapatanBersih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereTotalPengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasBulananRW whereUpdatedAt($value)
 */
	class KasBulananRW extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rt
 * @property string $tipe
 * @property string $jenis
 * @property numeric $jumlah
 * @property string $sumber_tujuan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RT $rt
 * @method static \Database\Factories\KasRTFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereSumberTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRT whereUpdatedAt($value)
 */
	class KasRT extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rw
 * @property string $tipe
 * @property string $jenis
 * @property numeric $jumlah
 * @property string $sumber_tujuan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RW $rw
 * @method static \Database\Factories\KasRWFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereIdRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereSumberTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KasRW whereUpdatedAt($value)
 */
	class KasRW extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_petugas
 * @property numeric $jumlah
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Petugas $petugas
 * @method static \Database\Factories\KasbonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereIdPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereUpdatedAt($value)
 */
	class Kasbon extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $iuran_id
 * @property string $nomor_kwitansi
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon $tanggal_cetak
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IuranWarga $iuranWarga
 * @method static \Database\Factories\KwitansiIuranWargaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereIuranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereNomorKwitansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereTanggalCetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiIuranWarga whereUpdatedAt($value)
 */
	class KwitansiIuranWarga extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_setoran
 * @property string $nomor_kwitansi
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon $tanggal_cetak
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SetoranRW $setoran
 * @method static \Database\Factories\KwitansiSetoranRWFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereIdSetoran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereNomorKwitansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereTanggalCetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KwitansiSetoranRW whereUpdatedAt($value)
 */
	class KwitansiSetoranRW extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rw
 * @property string $tugas
 * @property string $nama
 * @property string $alamat
 * @property numeric $gaji_pokok
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kasbon> $kasbons
 * @property-read int|null $kasbons_count
 * @property-read \App\Models\RW $rw
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SlipGaji> $slipGajis
 * @property-read int|null $slip_gajis_count
 * @method static \Database\Factories\PetugasFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereIdRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereUpdatedAt($value)
 */
	class Petugas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rw
 * @property int $id_user
 * @property string $nomor_rt
 * @property string $nama
 * @property string $alamat
 * @property string $no_telepon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IuranWarga> $iuranWargas
 * @property-read int|null $iuran_wargas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JenisIuranWarga> $jenisIuranWargas
 * @property-read int|null $jenis_iuran_wargas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasBulananRT> $kasBulananRTs
 * @property-read int|null $kas_bulanan_r_ts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRT> $kasKeluarRTs
 * @property-read int|null $kas_keluar_r_ts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRT> $kasMasukRTs
 * @property-read int|null $kas_masuk_r_ts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRT> $kasRTs
 * @property-read int|null $kas_r_ts_count
 * @property-read \App\Models\RW $rw
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SetoranRW> $setoranRWs
 * @property-read int|null $setoran_r_ws_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warga> $wargas
 * @property-read int|null $wargas_count
 * @method static \Database\Factories\RTFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereIdRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereNomorRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RT whereUpdatedAt($value)
 */
	class RT extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_user
 * @property string $nomor_rw
 * @property string $nama
 * @property string $alamat
 * @property string $no_telepon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasBulananRW> $kasBulananRWs
 * @property-read int|null $kas_bulanan_r_ws_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRW> $kasKeluarRWs
 * @property-read int|null $kas_keluar_r_ws_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRW> $kasMasukRWs
 * @property-read int|null $kas_masuk_r_ws_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KasRW> $kasRWs
 * @property-read int|null $kas_r_ws_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Petugas> $petugas
 * @property-read int|null $petugas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RT> $rts
 * @property-read int|null $rts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SetoranRW> $setoranRWs
 * @property-read int|null $setoran_r_ws_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\RWFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereNomorRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RW whereUpdatedAt($value)
 */
	class RW extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rt
 * @property int $id_rw
 * @property string $periode
 * @property \Illuminate\Support\Carbon $tanggal_setor
 * @property numeric $jumlah_setor
 * @property string $status_validasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KwitansiSetoranRW|null $kwitansi
 * @property-read \App\Models\RT $rt
 * @property-read \App\Models\RW $rw
 * @method static \Database\Factories\SetoranRWFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereIdRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereJumlahSetor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereStatusValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereTanggalSetor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SetoranRW whereUpdatedAt($value)
 */
	class SetoranRW extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_petugas
 * @property numeric $total
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Petugas $petugas
 * @method static \Database\Factories\SlipGajiFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereIdPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereUpdatedAt($value)
 */
	class SlipGaji extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $profile_picture
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\RT|null $rt
 * @property-read \App\Models\RW|null $rw
 * @property-read \App\Models\Warga|null $warga
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_rt
 * @property int $id_user
 * @property string $nama_kepala_keluarga
 * @property string $alamat
 * @property string $no_telepon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IuranWarga> $iuranWargas
 * @property-read int|null $iuran_wargas_count
 * @property-read \App\Models\RT $rt
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\WargaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereIdRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereNamaKepalaKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warga whereUpdatedAt($value)
 */
	class Warga extends \Eloquent {}
}

