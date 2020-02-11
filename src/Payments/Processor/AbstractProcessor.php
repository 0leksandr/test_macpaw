<?php

namespace Setapp\Test\Payments\Processor;

use Setapp\Test\Core\InterfaceInvoice;

abstract class AbstractProcessor
{
    /** @var AbstractProcessor[] */
    private static $processors;

    abstract public function process(InterfaceInvoice $invoice): bool;

    public static function get(): self
    {
        $class = static::class;
        if (!isset(self::$processors[$class])) {
            self::$processors[$class] = new $class;
        }

        return self::$processors[$class];
    }
}
