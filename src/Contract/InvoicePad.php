<?php

namespace Paytech\Invoice\Core\Contract;

/**
 * InvoicePad interface
 * Ezt kell implementálnia a számlatömb modellnek
 *
 * @package Paytech\Invoice\Core\Contract
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
interface InvoicePad
{
    /**
     * Számlatömb prefixe
     *
     * @return string
     */
    public function getPadPrefix();

    /**
     * Számla kiállító
     *
     * @return InvoiceVendor
     */
    public function getInvoiceVendor();
}