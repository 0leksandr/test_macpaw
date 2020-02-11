<?php

namespace Setapp\Test\Payments\Processor;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\DetailedProvider;

class DetailedProcessor extends AbstractProcessor
{
    /** @var DetailedProvider */
    private $provider;

    protected function __construct()
    {
        $this->provider = new DetailedProvider();
    }

    public function process(InterfaceInvoice $invoice): bool
    {
        $this->provider->schedule(
            $invoice->getCustomerId(),
            [
                'amount'       => $invoice->getAmount(),
                'request_time' => date('Y-m-d\TH:i:sP'),
                'invoice_id'   => $invoice->getId(),
            ]
        );

        return true;
    }
}
