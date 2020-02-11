<?php

namespace Setapp\Test\Payments\Processor;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Tests\Payments\DummyInvoice;

class CombinedProcessor extends AbstractProcessor
{
    /** @var AbstractProcessor[] */
    private $processors;

    public function __construct()
    {
        $this->processors = [
            DetailedProcessor::get(),
            SimpleProcessor::get(),
            LambdaProcessor::get(),
        ];
    }

    public function process(InterfaceInvoice $invoice): bool
    {
        $result = true;
        foreach ($this->divide($invoice) as $partialInvoice) {
            $result = $result && $partialInvoice->getProcessor()->process($invoice);
        }

        return $result;
    }

    /**
     * @param InterfaceInvoice $invoice
     * @return InterfaceInvoice[]
     */
    private function divide(InterfaceInvoice $invoice): array
    {
        $invoices = [];
        foreach ($this->processors as $processor) {
            $invoices[] = new DummyInvoice(
                $invoice->getId(),
                $invoice->getCustomerId(),
                $invoice->getAmount() / count($this->processors),
                $processor
            );
        }

        return $invoices;
    }
}
