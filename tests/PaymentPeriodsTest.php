<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriods;
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

        foreach ($periodsCollection as $k => $period) {
            $this->assertEquals($averagePeriodLength, $period->getAvgLength());
            $this->assertEquals($averagePeriodLength, $period->getLength($period::LENGTH_MODE_AVG));

            $this->assertEquals($paymentPeriods[$period->getSequenceNo()], $period->getLength($period::LENGTH_MODE_EXACT));
            $this->assertEquals($paymentPeriods[$period->getSequenceNo()], $period->getExactLength());
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
