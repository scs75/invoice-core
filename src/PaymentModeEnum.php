<?php

namespace Paytech\Invoice\Core;

/**
 * Számla fizetési módok
 *
 * @package App\Support
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
abstract class PaymentModeEnum
{
    /**
     * Készpénz
     */
    const CASH = 'cash';

    /**
     * Átutalás
     */
    const TRANSFER = 'transfer';

    public static function getArray()
    {
        return [
            self::CASH,
            self::TRANSFER,
        ];
    }
}
