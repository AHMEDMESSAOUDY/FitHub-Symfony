<?php
namespace App\Enum;

class typeCaterogy
{
    const NEW = 'new';
    const FOLLOWUP = 'followup';

    public static function getChoices(): array
    {
        return [
            self::NEW => 'New',
            self::FOLLOWUP => 'Followup',
        ];
    }
}
