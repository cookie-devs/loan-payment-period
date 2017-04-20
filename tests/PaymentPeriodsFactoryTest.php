<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriodsFactory;
use Kauri\Loan\PaymentPeriodsInterface;
use Kauri\Loan\PaymentScheduleConfig;
use Kauri\Loan\PaymentScheduleFactory;
use PHPUnit\Framework\TestCase;


class PaymentPeriodsFactoryTest extends TestCase
{
    /**
     * @dataProvider datesProvider
     * @param $noOfPayments
     * @param \DateTime $startDate
     * @param $dateIntervalPattern
     * @param array $endDates
     * @param array $startDates
     * @param array $expectedPeriodsLengthsExact
     * @param array $expectedPeriodsLengthsAverage
     */
    public function testSomething(
        $noOfPayments,
        \DateTime $startDate,
        $dateIntervalPattern,
        array $endDates,
        array $startDates,
        array $expectedPeriodsLengthsExact,
        array $expectedPeriodsLengthsAverage
    ) {
        $config = new PaymentScheduleConfig($noOfPayments, $startDate, $dateIntervalPattern);
        $schedule = PaymentScheduleFactory::generate($config);
        $paymentPeriods = PaymentPeriodsFactory::generate($schedule);

        foreach ($paymentPeriods->getPeriods() as $no => $period) {
            $this->assertEquals($period->getEnd()->format('Y-m-d'), $endDates[$no]);
            $this->assertEquals($period->getStart()->format('Y-m-d'), $startDates[$no]);
            $this->assertEquals($period->getLength(), $expectedPeriodsLengthsExact[$no]);
        }

        foreach ($paymentPeriods->getPeriodsLengths(PaymentPeriodsInterface::CALCULATION_MODE_EXACT) as $k => $item) {
            $this->assertEquals($expectedPeriodsLengthsExact[$k], $item);
        }

        foreach ($paymentPeriods->getPeriodsLengths(PaymentPeriodsInterface::CALCULATION_MODE_AVERAGE) as $k => $item) {
            $this->assertEquals($expectedPeriodsLengthsAverage[$k], $item);
        }
    }

    public function datesProvider()
    {
        return [
            'P1D' => [
                3,
                new \DateTime('2000-01-01'),
                'P1D',
                [1 => "2000-01-02", "2000-01-03", "2000-01-04"],
                [1 => "2000-01-02", "2000-01-03", "2000-01-04"],
                [1 => 1, 1, 1],
                [1 => 1, 1, 1]
            ],
            'P3D' => [
                3,
                new \DateTime('2000-01-01'),
                'P3D',
                [1 => "2000-01-04", "2000-01-07", "2000-01-10"],
                [1 => "2000-01-02", "2000-01-05", "2000-01-08"],
                [1 => 3, 3, 3],
                [1 => 3, 3, 3]
            ],
            'P1M' => [
                3,
                new \DateTime('2000-01-01'),
                'P1M',
                [1 => "2000-02-01", "2000-03-01", "2000-04-01"],
                [1 => "2000-01-02", "2000-02-02", "2000-03-02"],
                [1 => 31, 29, 31],
                [1 => 30, 30, 30]
            ],
        ];
    }

}
