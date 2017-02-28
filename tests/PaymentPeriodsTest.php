<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriods;
use Kauri\Loan\Period;
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
        $totalLength = 0;
        $periodLength = 0;

        $this->assertEquals(0, $periodsCollection->getNoOfPeriods());
        $this->assertTrue(empty($periodsCollection->getPeriods()));

        foreach ($paymentPeriods as $periodLength) {
            $periodMock = $this->getMockPeriod($periodLength);
            $periodsCollection->add($periodMock);
            $totalLength = $totalLength + $periodLength;
        }

        $periods = $periodsCollection->getPeriods();
        $period = current($periods);
        $length = $period->getLength();

        $this->assertEquals($noOfPayments, $periodsCollection->getNoOfPeriods());
        $this->assertTrue(!empty($periodsCollection->getPeriods()));

        $this->assertEquals($totalLength / $length,
            $periodsCollection->getNumberOfPeriods($period, $periodsCollection::CALCULATION_TYPE_EXACT));
        $this->assertEquals($noOfPayments,
            $periodsCollection->getNumberOfPeriods($period, $periodsCollection::CALCULATION_TYPE_EXACT_INTEREST));
        $this->assertEquals($noOfPayments,
            $periodsCollection->getNumberOfPeriods($period, $periodsCollection::CALCULATION_TYPE_ANNUITY));

        $this->assertEquals($length,
            $periodsCollection->getRatePerPeriod($period, 360,
                $periodsCollection::CALCULATION_TYPE_EXACT));
        $this->assertEquals($length,
            $periodsCollection->getRatePerPeriod($period, 360,
                $periodsCollection::CALCULATION_TYPE_EXACT_INTEREST));
        $this->assertEquals($averagePeriodLength,
            $periodsCollection->getRatePerPeriod($period, 360,
                $periodsCollection::CALCULATION_TYPE_ANNUITY));
    }

    public function periodsData()
    {
        return [
            [7, [6, 5, 3, 9]],
            [30, [29, 30, 31, 30, 28]]
        ];
    }

    /**
     * @expectedException \Exception
     */
    public function testRatePerPeriodException()
    {
        $periodsCollection = new PaymentPeriods(1);
        $periodsCollection->getRatePerPeriod($this->getMockPeriod(3), 10, 10);
    }

    /**
     * @expectedException \Exception
     */
    public function testNumberOfPeriodsException()
    {
        $periodsCollection = new PaymentPeriods(1);
        $periodsCollection->getNumberOfPeriods($this->getMockPeriod(3), 10);
    }

    private function getMockPeriod($length)
    {
        $stub = $this->createMock(Period::class);
        $stub->method('getLength')
            ->willReturn($length);

        return $stub;
    }

}
