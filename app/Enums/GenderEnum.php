<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GenderEnum: string implements HasLabel
{
    const MALE = 'Male';
    const FEMALE = 'Female';

    public static function getValues()
    {
        return [
            self::MALE => 'Male',
            self::FEMALE => 'Female',
        ];
    }

    public function getLabel(): ?string
    {

        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            default => throw new \Exception('Unexpected match value'),
        };
    }

}
