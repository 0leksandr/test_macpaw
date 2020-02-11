<?php
declare(strict_types=1);

namespace Setapp\Test\Tests\Payments;

use Exception;
use PHPUnit\Framework\TestCase;
use Setapp\Test\Payments\BasePaymentGateway;
use Setapp\Test\Payments\Processor\CombinedProcessor;
use Setapp\Test\Payments\Processor\DetailedProcessor;
use Setapp\Test\Payments\Processor\LambdaProcessor;
use Setapp\Test\Payments\Processor\SimpleProcessor;

class BasePaymentGatewayTest extends TestCase
{
    /** @var BasePaymentGateway */
    protected $testingUnit;

    public function setUp()
    {
        parent::setUp();
        $this->testingUnit = new BasePaymentGateway();
    }

    public function getChargeTestsSets(): array
    {
        $detailedSuccess1 = new DummyInvoice('d1-ok', 200, '20.00', DetailedProcessor::get());
        $detailedSuccess2 = new DummyInvoice('d2-ok', 201, '40.00', DetailedProcessor::get());

        $simpleSuccess = new DummyInvoice('s-ok', 100, '10.00', SimpleProcessor::get());
        $simpleFail = new DummyInvoice('s-fail', 101, '1000.00', SimpleProcessor::get());

        $lambdaSuccess1 = new DummyInvoice('l1-ok', 300, '30.00', LambdaProcessor::get());
        $lambdaFail1 = new DummyInvoice('l1-fail', 301, '50.00', LambdaProcessor::get());
        $lambdaSuccess2 = new DummyInvoice('l2-ok', 302, '70.00', LambdaProcessor::get());
        $lambdaFail2 = new DummyInvoice('l2-fail', 303, '80.00', LambdaProcessor::get());

        $combinedSuccess = new DummyInvoice('c-ok', 100, '10.00', CombinedProcessor::get());
        $combinedFail1   = new DummyInvoice('c1-fail', 101, '10.00', CombinedProcessor::get());
        $combinedFail2   = new DummyInvoice('c2-fail', 100, '110.00', CombinedProcessor::get());

        return [
            'detailed provider' => [['d1-ok' => true], $detailedSuccess1],
            'simple provider' => [['s-ok' => true, 's-fail' => false], $simpleSuccess, $simpleFail],
            'lambda provider' => [['l1-ok' => true, 'l1-fail' => false], $lambdaSuccess1, $lambdaFail1],
            'combined provider' => [
                ['c-ok' => true, 'c1-fail' => false, 'c2-fail' => false],
                $combinedSuccess,
                $combinedFail1,
                $combinedFail2,
            ],
            'mixed providers' => [
                [
                    's-ok'    => true,
                    'l1-ok'   => true,
                    'd2-ok'   => true,
                    'l2-ok'   => true,
                    'l1-fail' => false,
                    'l2-fail' => false,
                    'c-ok'    => true,
                    'c1-fail' => false,
                    'c2-fail' => false,
                ],
                $simpleSuccess,
                $lambdaSuccess1,
                $detailedSuccess2,
                $lambdaSuccess2,
                $lambdaFail1,
                $lambdaFail2,
                $combinedSuccess,
                $combinedFail1,
                $combinedFail2,
            ],
        ];
    }

    /**
     * @dataProvider getChargeTestsSets
     *
     * @param $expectedResult
     * @param array $inputInvoices
     * @throws Exception
     */
    public function testCharge($expectedResult, ...$inputInvoices): void
    {
        $result = $this->testingUnit->charge($inputInvoices);
        self::assertSame($expectedResult, $result);
    }
}
