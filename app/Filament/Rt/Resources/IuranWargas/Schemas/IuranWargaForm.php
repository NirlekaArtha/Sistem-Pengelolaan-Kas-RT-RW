<?php

namespace App\Filament\Rt\Resources\IuranWargas\Schemas;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class IuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Form Iuran Warga')
                ->description('Form input iuran warga untuk RT')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Hidden::make('status')
                        ->default(IuranWargaStatus::DIBAYAR->value)
                        ->dehydrated()
                        ->visibleOn('create'),
                    Select::make('id_warga')
                        ->relationship(
                            'warga',
                            'nama_kepala_keluarga',
                            fn ($query) => $query->where(
                                'id_rt',
                                auth()->user()?->rt?->id,
                            ),
                        )
                        ->label('Nama Warga')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(
                            fn (Set $set, Get $get) => self::fillDetailIuranRepeater($set, $get),
                        )
                        ->required(),
                    Select::make('id_jenis_iuran')
                        ->relationship(
                            'jenisIuran',
                            'jenis_iuran',
                            fn ($query) => $query->where(
                                'id_rt',
                                auth()->user()?->rt?->id,
                            ),
                        )
                        ->label('Jenis Iuran')
                        ->rule(
                            fn (Get $get, $record) => Rule::unique(
                                'iuran_wargas',
                                'id_jenis_iuran',
                            )
                                ->ignore($record?->id)
                                ->where('id_warga', $get('id_warga'))
                                ->where('periode', $get('periode'))
                                ->where('id_rt', self::getCurrentRtId()),
                        )
                        ->validationMessages([
                            'unique' => 'Jenis iuran ini sudah tercatat untuk KK dan periode yang sama.',
                        ])
                        ->required()
                        ->searchable()
                        ->preload()
                        ->hiddenOn('create'),
                    TextInput::make('periode')
                        ->label('Periode')
                        ->type('month')
                        ->placeholder('YYYY-MM')
                        ->rule('date_format:Y-m')
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            fn (Set $set, Get $get) => self::fillDetailIuranRepeater($set, $get),
                        )
                        ->required(),
                    DatePicker::make('tanggal_bayar')
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->hint(
                            fn (string $operation): ?string => $operation === 'create'
                                ? 'Tanggal ini akan dipakai saat menandai iuran sebagai dibayar.'
                                : null,
                        ),
                    Repeater::make('detail_iuran')
                        ->label('Jenis Iuran Periode Ini')
                        ->visibleOn('create')
                        ->hidden(fn (Get $get): bool => blank($get('periode')))
                        ->defaultItems(0)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->collapsible(false)
                        ->columnSpanFull()
                        ->schema([
                            Hidden::make('id_jenis_iuran'),
                            Hidden::make('jenis_iuran'),
                            Hidden::make('is_disabled'),
                            Hidden::make('helper_text'),
                            Checkbox::make('is_selected')
                                ->label(fn (Get $get): string => $get('jenis_iuran') ?: 'Jenis Iuran')
                                ->helperText(fn (Get $get): ?string => $get('helper_text'))
                                ->disabled(fn (Get $get): bool => (bool) $get('is_disabled'))
                                ->columnSpan(2),
                            TextInput::make('nominal_label')
                                ->label('Nominal')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('status_label')
                                ->label('Status')
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->columns(4)
                        ->itemLabel(fn (array $state): ?string => $state['jenis_iuran'] ?? null)
                        ->helperText(
                            'Centang jenis iuran yang ingin ditandai sebagai dibayar untuk KK dan periode yang dipilih.',
                        ),
                ]),
        ]);
    }

    protected static function fillDetailIuranRepeater(Set $set, Get $get): void
    {
        $periode = $get('periode');

        if (blank($periode)) {
            $set('detail_iuran', []);

            return;
        }

        $wargaId = $get('id_warga');

        $set(
            'detail_iuran',
            self::getDetailIuranState(
                filled($wargaId) ? (int) $wargaId : null,
                (string) $periode,
            ),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected static function getDetailIuranState(?int $wargaId, string $periode): array
    {
        $rtId = self::getCurrentRtId();

        if (! $rtId) {
            return [];
        }

        $jenisIurans = JenisIuranWarga::query()
            ->where('id_rt', $rtId)
            ->orderBy('jenis_iuran')
            ->get();

        $existingIurans = filled($wargaId)
            ? IuranWarga::query()
                ->where('id_rt', $rtId)
                ->where('id_warga', $wargaId)
                ->where('periode', $periode)
                ->get()
                ->keyBy('id_jenis_iuran')
            : collect();

        return $jenisIurans
            ->map(function (JenisIuranWarga $jenisIuran) use (
                $wargaId,
                $existingIurans,
            ): array {
                /** @var IuranWarga|null $existingIuran */
                $existingIuran = $existingIurans->get($jenisIuran->id);
                $isDisabled = blank($wargaId) || $existingIuran?->status === IuranWargaStatus::DIBAYAR;

                return [
                    'id_jenis_iuran' => $jenisIuran->id,
                    'jenis_iuran' => $jenisIuran->jenis_iuran,
                    'is_selected' => false,
                    'is_disabled' => $isDisabled,
                    'nominal_label' => 'Rp '.number_format((float) $jenisIuran->jumlah, 0, ',', '.'),
                    'status_label' => self::getStatusLabel($wargaId, $existingIuran),
                    'helper_text' => self::getHelperText($wargaId, $existingIuran),
                ];
            })
            ->values()
            ->all();
    }

    protected static function getStatusLabel(
        ?int $wargaId,
        ?IuranWarga $existingIuran,
    ): string {
        if (blank($wargaId)) {
            return 'Pilih KK dulu';
        }

        return $existingIuran?->status?->getLabel() ?? 'Belum dicatat';
    }

    protected static function getHelperText(
        ?int $wargaId,
        ?IuranWarga $existingIuran,
    ): string {
        if (blank($wargaId)) {
            return 'Pilih KK terlebih dahulu agar daftar iuran bisa dicek status pembayarannya.';
        }

        if (! $existingIuran) {
            return 'Centang untuk membuat iuran ini sebagai dibayar pada periode terpilih.';
        }

        if ($existingIuran->status === IuranWargaStatus::DIBAYAR) {
            return 'Iuran ini sudah dibayar untuk periode tersebut.';
        }

        return 'Iuran ini sudah tercatat dengan status '.$existingIuran->status->getLabel().'. Centang untuk mengubahnya menjadi dibayar.';
    }

    protected static function getCurrentRtId(): ?int
    {
        return auth()->user()?->rt?->id;
    }
}
