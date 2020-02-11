<?php

namespace Setapp\Test\Payments\Processor;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\SimpleProvider;

class SimpleProcessor extends AbstractProcessor
{
    /** @var SimpleProvider */
    private $provider;

    protected function __construct()
    {
        $this->provider = new SimpleProvider();
    }

    public function process(InterfaceInvoice $invoice): bool
    {
        return $this->provider->charge($invoice->getCustomerId(), $invoice->getAmount());
    }
}
