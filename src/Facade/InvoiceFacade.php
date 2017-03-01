<?php

namespace Paytech\Invoice\Core\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Számlázás szolgáltatás homlokzata
 *
 * @package App\Support\Service
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
class InvoiceFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
//        return 'invoice';
        return 'Paytech\Invoice\Core\Contract\InvoiceManager';
    }
}
