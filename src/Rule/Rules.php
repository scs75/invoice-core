<?php

namespace Paytech\Invoice\Core\Rule;

use Paytech\Invoice\Core\InvoiceTypeEnum;
use Paytech\Invoice\Core\PaymentModeEnum;

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
     * Legyenek tételek
     *
     * @param $invoice
     * @throws \Exception
     */
    public function requireItems($invoice)
    {
        if($invoice->type != InvoiceTypeEnum::CORRECTION) {
            if($invoice->items->count() == 0) {
                throw new \Exception('Nincsenek tételek a számlán!');
            }
        }
    }

    /**
     * Átutalásos számlán kötelező a fizetési határidő
     *
     * @param $invoice
     * @throws \Exception
     */
    public function requireDeadline($invoice)
    {
        if($invoice->payment_mode == PaymentModeEnum::TRANSFER) {
            if(is_null($invoice->deadline_d)) {
                throw new \Exception('Fizetési határidő kötelező!');
            }
        }
    }

    /**
     * Fizetési határidő nem lehet múltbeli dátum
     *
     * @param $invoice
     * @throws \Exception
     */
    public function validDeadline($invoice)
    {
        if(!is_null($invoice->deadline_d) && $invoice->deadline_d->isPast()) {
            throw new \Exception('Fizetési határidő nem lehet múltbeli dátum!');
        }
    }
}