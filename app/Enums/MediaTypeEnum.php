<?php

namespace App\Enums;

enum MediaTypeEnum
{
    public const IMAGE = 'image';

    public const FILE = 'file';

    public static function values(): array
    {
        return [
            self::IMAGE,
            self::FILE,
        ];
    }
}
