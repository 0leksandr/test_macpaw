<?php
declare(strict_types=1);

namespace Setapp\Test\Payments;

use Exception;
use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\DetailedProvider;
use Setapp\Test\Payments\Providers\LambdaProvider;
use Setapp\Test\Payments\Providers\SimpleProvider;

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
            $results[$invoice->getId()] = $this->process($invoice);
        }

        return $results;
    }

    /**
     * @param InterfaceInvoice $invoice
     * @return bool
     * @throws Exception
     */
    private function process(InterfaceInvoice $invoice): bool
    {
        switch ($invoice->getProvider()) {
            case DetailedProvider::NAME:
                (new DetailedProvider())->schedule(
                    $invoice->getCustomerId(),
                    [
                        'amount'       => $invoice->getAmount(),
                        'request_time' => date('Y-m-d\TH:i:sP'),
                        'invoice_id'   => $invoice->getId(),
                    ]
                );
                return true;
            case SimpleProvider::NAME:
                return (new SimpleProvider())->charge(
                    $invoice->getCustomerId(),
                    $invoice->getAmount()
                );
            case LambdaProvider::NAME:
                return (new LambdaProvider())->charge([
                    'invoices' => [
                        $invoice->getId() => [$invoice->getCustomerId(), $invoice->getAmount()],
                    ],
                ])[$invoice->getId()];
            default:
                throw new Exception('Unexpected provider');
        }
    }
}
