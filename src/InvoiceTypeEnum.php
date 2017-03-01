<?php

namespace Paytech\Invoice\Core;

/**
 * Számla típus
 *
 * @package App\Support
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
abstract class InvoiceTypeEnum
{
    /**
     * Normál számla
     */
    const NORMAL = 'normal';

    /**
     * Díjbekérő
     */
    const PROFORMA = 'proforma';

    /**
     * Sztornó számla
     */
    const CANCEL = 'cancel';

    /**
     * Helyesbítő számla
     */
    const CORRECTION = 'correction';

    /**
     * Előlegszámla
     */
    const ADVANCE = 'advance';

    /**
     * Részszámla
     */
    const PARTIAL = 'partial';

    public static function getArray()
    {
        return [
            self::NORMAL,
            self::PROFORMA,
            self::CANCEL,
            self::CORRECTION,
            self::ADVANCE,
            self::PARTIAL,
        ];
    }
}
