<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriodsFactory;
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
     * @param array $dates
     * @param array $periodLength
     * @param array $numPeriods
     */
    public function testSomething(
        $noOfPayments,
        \DateTime $startDate,
        $dateIntervalPattern,
        array $dates,
        array $periodLength,
        array $numPeriods
    ) {
        $config = new PaymentScheduleConfig($noOfPayments, $startDate, $dateIntervalPattern);
        $schedule = PaymentScheduleFactory::generate($config);
        $paymentPeriods = PaymentPeriodsFactory::generate($schedule);

        foreach ($paymentPeriods->getPeriods() as $no => $period) {
            $this->assertEquals($period->getEnd()->format('Y-m-d'), $dates[$no]);
            $this->assertEquals($period->getLength(), $periodLength[$no]);
            $this->assertEquals($paymentPeriods->getNumberOfPeriods($period, $paymentPeriods::CALCULATION_TYPE_EXACT),
                $numPeriods[$no]);
            $this->assertEquals($paymentPeriods->getNumberOfPeriods($period,
                $paymentPeriods::CALCULATION_TYPE_EXACT_INTEREST), $noOfPayments);
            $this->assertEquals($paymentPeriods->getNumberOfPeriods($period, $paymentPeriods::CALCULATION_TYPE_ANNUITY),
                $noOfPayments);
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
                [1 => 1, 1, 1],
                [1 => 3 / 1, 3 / 1, 3 / 1]
            ],
            'P3D' => [
                3,
                new \DateTime('2000-01-01'),
                'P3D',
                [1 => "2000-01-04", "2000-01-07", "2000-01-10"],
                [1 => 3, 3, 3],
                [1 => 9 / 3, 9 / 3, 9 / 3]
            ],
            'P1M' => [
                3,
                new \DateTime('2000-01-01'),
                'P1M',
                [1 => "2000-02-01", "2000-03-01", "2000-04-01"],
                [1 => 31, 29, 31],
                [1 => 91 / 31, 91 / 29, 91 / 31]
            ],
        ];
    }

}
