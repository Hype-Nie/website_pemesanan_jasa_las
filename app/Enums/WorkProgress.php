<?php

namespace App\Enums;

enum WorkProgress: int
{
    case PENDING = 0;
    case PREPARATION = 25;
    case ASSEMBLY = 50;
    case FINISHING = 75;
    case COMPLETED = 100;

    /**
     * Get human-readable Indonesian label.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => '0% - Menunggu Dimulai',
            self::PREPARATION => '25% - Persiapan & Pemotongan Bahan',
            self::ASSEMBLY => '50% - Perakitan & Pengelasan',
            self::FINISHING => '75% - Penghalusan & Pengecatan (Finishing)',
            self::COMPLETED => '100% - Selesai & Siap Diambil/Diantar',
        };
    }

    /**
     * Get short step name.
     */
    public function stepName(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::PREPARATION => 'Persiapan Bahan',
            self::ASSEMBLY => 'Perakitan & Pengelasan',
            self::FINISHING => 'Finishing',
            self::COMPLETED => 'Selesai',
        };
    }

    /**
     * Get Bootstrap badge class.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'secondary',
            self::PREPARATION => 'warning text-dark',
            self::ASSEMBLY => 'info text-dark',
            self::FINISHING => 'primary',
            self::COMPLETED => 'success',
        };
    }

    /**
     * Return all enum cases as key-value array [value => label].
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
