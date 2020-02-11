<?php

namespace Setapp\Test\Payments\Processor;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\LambdaProvider;

class LambdaProcessor extends AbstractProcessor
{
    /** @var LambdaProvider */
    private $provider;

    protected function __construct()
    {
        $this->provider = new LambdaProvider();
    }

    public function process(InterfaceInvoice $invoice): bool
    {
        return $this->provider->charge([
            'invoices' => [
                $invoice->getId() => [$invoice->getCustomerId(), $invoice->getAmount()],
            ],
        ])[$invoice->getId()];
    }
}
