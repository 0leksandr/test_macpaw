<?php
declare(strict_types=1);

namespace Setapp\Test\Core;

use Setapp\Test\Payments\Processor\AbstractProcessor;

interface InterfaceInvoice
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @return string
     */
    public function getAmount(): string;

    public function getProcessor(): AbstractProcessor;
}

