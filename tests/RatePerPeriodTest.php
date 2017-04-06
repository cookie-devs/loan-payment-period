<?php

namespace Kauri\Loan\Test;


use Kauri\Loan\PaymentPeriods;
use Kauri\Loan\Period;
use PHPUnit\Framework\TestCase;

class RatePerPeriodTest extends TestCase
{
    /**
     * @dataProvider expectedRateData
     * @param $calculationMode
     * @param $calculateFor
     * @param $expected
     */
    public function testGetRatePerPeriod($calculationMode, $calculateFor, $expected)
    {
        $periods = $this->periodsData();

        foreach ($periods->getPeriods() as $k => $p) {
            $this->assertEquals($expected[$k], $periods->getRatePerPeriod($p, 360, $calculationMode, $calculateFor));
        }
    }

    /**
     * @dataProvider expectedPeriodsData
     * @param $calculationMode
     * @param $calculateFor
     * @param $expected
     */
    public function testGetNumberOfRemainingPeriods($calculationMode, $calculateFor, $expected)
    {
        $periods = $this->periodsData();

        foreach ($periods->getPeriods() as $k => $p) {
            $this->assertEquals($expected[$k], $periods->getNumberOfRemainingPeriods($p, $calculationMode, $calculateFor));
        }
    }

    public function expectedRateData()
    {
        return [
            [PaymentPeriods::CALCULATION_MODE_AVERAGE, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 30, 30, 30, 30, 30]],
            [PaymentPeriods::CALCULATION_MODE_EXACT_INTEREST, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 30, 30, 30, 30, 30]],
            [PaymentPeriods::CALCULATION_MODE_EXACT, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 31, 29, 31, 30, 31]],
            [PaymentPeriods::CALCULATION_MODE_AVERAGE, PaymentPeriods::CALCULATE_FOR_INTEREST, [1 => 30, 30, 30, 30, 30]],
            [PaymentPeriods::CALCULATION_MODE_EXACT_INTEREST, PaymentPeriods::CALCULATE_FOR_INTEREST, [1 => 31, 29, 31, 30, 31]],
            [PaymentPeriods::CALCULATION_MODE_EXACT, PaymentPeriods::CALCULATE_FOR_INTEREST, [1 => 31, 29, 31, 30, 31]]
        ];
    }

    public function expectedPeriodsData()
    {
        return [
            [PaymentPeriods::CALCULATION_MODE_AVERAGE, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 5, 5, 5, 5, 5]],
            [PaymentPeriods::CALCULATION_MODE_EXACT_INTEREST, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 5, 5, 5, 5, 5]],
            [PaymentPeriods::CALCULATION_MODE_EXACT, PaymentPeriods::CALCULATE_FOR_PAYMENT, [1 => 152/31, 152/29, 152/31, 152/30, 152/31]],
            [PaymentPeriods::CALCULATION_MODE_AVERAGE, PaymentPeriods::CALCULATE_FOR_INTEREST, [1 => 5, 5, 5, 5, 5]],
            [PaymentPeriods::CALCULATION_MODE_EXACT_INTEREST, PaymentPeriods::CALCULATE_FOR_INTEREST, [1 => 152/31, 152/29, 152/31, 152/30, 152/31]],
            [PaymentPeriods::CALCULATION_MODE_EXACT, PaymentPeriods::CALCULATE_FOR_INTEREST,  [1 => 152/31, 152/29, 152/31, 152/30, 152/31]]
        ];
    }

    private function periodsData()
    {
        $periods = new PaymentPeriods(30);

        $dates = array(
            ['2016-01-01', '2016-01-31'],
            ['2016-02-01', '2016-02-29'],
            ['2016-03-01', '2016-03-31'],
            ['2016-04-01', '2016-04-30'],
            ['2016-05-01', '2016-05-31']
        );

        foreach ($dates as $date) {
            $period = new Period(new \DateTime($date[0]), new \DateTime($date[1]));
            $periods->add($period);
        }

        return $periods;
    }
}
