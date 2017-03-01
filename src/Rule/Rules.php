<?php

namespace Paytech\Invoice\Core\Rule;

use Paytech\Invoice\Core\InvoiceTypeEnum;

/**
 * Általános szabályok
 *
 * @package Paytech\Invoice\Core\Service
 * @author Sáray Csaba <csaba.saray@paytech.hu>
 * @licence http://paytech.hu All rights reserved
 */
class Rules
{
    /**
     * Legyenk tételek
     *
     * @param $invoice
     * @throws \Exception
     */
    public function countItems($invoice)
    {
        if($invoice->type != InvoiceTypeEnum::CORRECTION) {
            if($invoice->items->count() == 0) {
                throw new \Exception('Nincsenek tételek a számlán!');
            }
        }
    }
}