<?php

namespace Paytech\Invoice\Core\Contract;

use Paytech\Invoice\Core\Model\Vat;

/**
 * InvoiceItem interface
 * Ezt kell implementálnia a termék vagy szolgáltatás modellnek
 *
 * @package Paytech\Invoice\Core\Contract
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
interface InvoiceItem
{
    /**
     * Megnevezés
     *
     * @return string
    */
    public function getInvoiceDescription();

    /**
     * Egység
     *
     * @return string
     */
    public function getInvoiceUnit();

    /**
     * Áfa %-ban
     *
     * @return Vat
     */
    public function getInvoiceVat();
}