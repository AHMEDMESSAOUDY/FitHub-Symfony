<?php
namespace App\Enum;

class type
{
    const ADISTANCE = 'adistance';
    const CABINET = 'cabinet';

    public static function getChoices(): array
    {
        return [
            self::ADISTANCE => 'Adistance',
            self::CABINET => 'Cabinet',
        ];
    }
}
