<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriods;
use Kauri\Loan\PaymentPeriodsInterface;
use Kauri\Loan\Period;
use Kauri\Loan\PeriodInterface;
use PHPUnit\Framework\TestCase;


class PaymentPeriodsTest extends TestCase
{
    /**
     * @dataProvider periodsData
     * @param $averagePeriodLength
     * @param $paymentPeriods
     */
    public function testPaymentPeriods($averagePeriodLength, $paymentPeriods)
    {
        $periodsCollection = new PaymentPeriods($averagePeriodLength);
        $noOfPayments = count($paymentPeriods);

        $this->assertEquals(0, $periodsCollection->getNoOfPeriods());
        $this->assertTrue(empty($periodsCollection->getPeriods()));

        foreach ($paymentPeriods as $periodLength) {
            $periodMock = $this->getMockPeriod($periodLength);
            $periodsCollection->add($periodMock);
        }

        $this->assertEquals($noOfPayments, $periodsCollection->getNoOfPeriods());
        $this->assertTrue(!empty($periodsCollection->getPeriods()));

        foreach ($periodsCollection->getPeriodsLengths(PaymentPeriodsInterface::CALCULATION_MODE_AVERAGE) as $k => $periodLength) {
            $this->assertEquals($averagePeriodLength, $periodLength);
        }

        foreach ($periodsCollection->getPeriodsLengths(PaymentPeriodsInterface::CALCULATION_MODE_EXACT) as $k => $periodLength) {
            $this->assertEquals($paymentPeriods[$k], $periodLength);
        }
    }

    /**
     * @dataProvider periodsData
     * @param int $averagePeriodLength
     * @param array $paymentPeriods
     */
    public function testPeriod(int $averagePeriodLength, array $paymentPeriods)
    {
        $periodsCollection = new PaymentPeriods($averagePeriodLength);

        foreach ($paymentPeriods as $periodLength) {
            $periodMock = $this->getMockPeriod($periodLength);
            $periodsCollection->add($periodMock);
        }

        $periods = $periodsCollection->getPeriods();

        /** @var PeriodInterface $period */
        foreach ($periods as $period) {
            $this->assertEquals($paymentPeriods[$period->getSequenceNo()], $period->getLength());
        }
    }

    public function periodsData()
    {
        return [
            [7, [1 => 6, 5, 3, 9]],
            [30, [1 => 29, 30, 31, 30, 28]]
        ];
    }

    /**
     * @param $length
     * @return PeriodInterface
     */
    private function getMockPeriod($length)
    {
        $stub = $this->getMockBuilder(Period::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLength'])
            ->getMock();

        $stub->method('getLength')
            ->willReturn($length);

        return $stub;
    }

}
