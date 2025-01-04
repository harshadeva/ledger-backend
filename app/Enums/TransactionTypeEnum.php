<?php

namespace App\Enums;

enum TransactionTypeEnum
{
    public const INCOME = 'INCOME';
    public const EXPENSE = 'EXPENSE';
    public const TRANSFER = 'TRANSFER';

    public static function values(): array
    {
        return [
            self::INCOME,
            self::EXPENSE,
            self::TRANSFER,
        ];
    }
}
