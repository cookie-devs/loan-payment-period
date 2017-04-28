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
            $periodMock = $this->getMockPeriod($periodLength, $averagePeriodLength);
            $periodsCollection->add($periodMock);
        }

        $this->assertEquals($noOfPayments, $periodsCollection->getNoOfPeriods());
        $this->assertTrue(!empty($periodsCollection->getPeriods()));

        /**
         * @var  $k
         * @var PeriodInterface $period
         */
        foreach ($periodsCollection->getPeriods() as $no => $period) {
            $this->assertEquals($no, $period->getSequenceNo());
            $this->assertEquals($averagePeriodLength, $period->getAvgLength());
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
    private function getMockPeriod($length, $averagePeriodLength)
    {
        $stub = $this->getMockBuilder(Period::class)
            ->disableOriginalConstructor()
            ->setMethods(['getExactLength', 'getAvgLength'])
            ->getMock();

        $stub->method('getExactLength')
            ->willReturn($length);
        $stub->method('getAvgLength')
            ->willReturn($averagePeriodLength);

        return $stub;
    }

}
