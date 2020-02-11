<?php
declare(strict_types=1);

namespace Setapp\Test\Payments;

use Exception;
use Setapp\Test\Core\InterfaceInvoice;

class BasePaymentGateway implements InterfacePaymentGateway
{
    /**
     * @param InterfaceInvoice[]|array $invoices
     *
     * @return boolean[] array for results [INVOICEID => RESULT, ...]
     * @throws Exception
     */
    public function charge(array $invoices): array
    {
        $results = [];
        foreach ($invoices as $invoice) {
            $results[$invoice->getId()] = $invoice->getProcessor()->process($invoice);
        }

        return $results;
    }
}
