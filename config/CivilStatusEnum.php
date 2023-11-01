<?php

enum CivilStatusEnum
{

    const SINGLE = 'Single';
    const MARRIED = 'Married';
    const WIDOWED = 'Widowed';
    const OTHER = 'Other';

    public static function getValues()
    {
        return [
            self::SINGLE => 'Single',
            self::MARRIED => 'Married',
            self::WIDOWED => 'Widowed',
            self::OTHER => 'Other',
        ];
    }

}
