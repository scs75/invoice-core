<?php

namespace Paytech\Invoice\Core\Contract;

/**
 * InvoiceCustomer interface
 * Ezt kell implementálnia a vevő modellnek
 *
 * @package Paytech\Invoice\Core\Contract
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
interface InvoiceCustomer
{
    /**
     * Vevő neve
     *
     * @return string
     */
    public function getCustomerName();

    /**
     * Ir. szám
     *
     * @return string
     */
    public function getCustomerZipCode();

    /**
     *
     *
     * @return string
     */
    public function getCustomerCity();

    /**
     *
     *
     * @return string
     */
    public function getCustomerStreet();

    /**
     *
     *
     * @return string
     */
    public function getCustomerCountry();

    /**
     *
     *
     * @return string
     */
    public function getCustomerTaxNumber();

    /**
     *
     *
     * @return string
     */
    public function getCustomerEuTaxNumber();
}