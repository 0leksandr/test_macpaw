<?php
declare(strict_types=1);

namespace Setapp\Test\Tests\Payments;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Processor\AbstractProcessor;

class DummyInvoice implements InterfaceInvoice
{
    /** @var string */
    protected $id;
    /** @var int */
    protected $customerId;
    /** @var string */
    protected $amount;
    /** @var AbstractProcessor */
    protected $processor;

    public function __construct(string $id, int $customerId, string $amount, AbstractProcessor $processor)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->amount = $amount;
        $this->processor = $processor;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getProcessor(): AbstractProcessor
    {
        return $this->processor;
    }
}
