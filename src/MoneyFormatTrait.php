<?php

namespace Paytech\Invoice\Core;

use Paytech\Invoice\Core\Model\Invoice;

/**
 * Pénznem formázó
 *
 * @package Paytech\Invoice\Core
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
trait MoneyFormatTrait
{
    public function formatCurrency($number, Invoice $invoice)
    {
        $lang = config('invoice.languages.'.$invoice->language);
        $amount = number_format($number,
            $lang['decimals'],
            $lang['dec_point'],
            $lang['thousands_sep']
        );
        $amount = $amount.' '.config('invoice.currencies.'.$invoice->currency.'.code');
        return $amount;
    }
}
