<?php

declare(strict_types = 1);

namespace Kauri\Loan;


/**
 * Class PaymentPeriodsFactory
 * @package Kauri\Loan
 */
class PaymentPeriodsFactory implements PaymentPeriodsFactoryInterface
{
    /**
     * @param PaymentScheduleInterface $paymentSchedule
     * @return PaymentPeriodsInterface
     */
    public static function generate(PaymentScheduleInterface $paymentSchedule): PaymentPeriodsInterface
    {
        $periods = new PaymentPeriods($paymentSchedule->getConfig()->getAverageIntervalLength());

        $periodStart = clone $paymentSchedule->getConfig()->getStartDate();

        foreach ($paymentSchedule->getPaymentDates() as $paymentNo => $paymentDate) {
            $periodStart = self::calculatePeriodStart($periodStart);
            $periodEnd = self::calculatePeriodEnd($paymentDate);

            $period = new Period($periodStart, $periodEnd);
            $periods->add($period, $paymentNo);

            $periodStart = clone $paymentDate;
        }

        return $periods;
    }

    /**
     * @param \DateTimeInterface $periodStart
     * @return \DateTimeInterface
     */
    private static function calculatePeriodStart(\DateTimeInterface $periodStart): \DateTimeInterface
    {
        $periodStart = clone $periodStart;
        // Move to next day
        $periodStart->add(new \DateInterval('P1D'));

        return $periodStart;
    }

    /**
     * @param \DateTimeInterface $paymentDate
     * @return \DateTimeInterface
     */
    private static function calculatePeriodEnd(\DateTimeInterface $paymentDate): \DateTimeInterface
    {
        $periodEnd = clone $paymentDate;
        // Move to the end of the day
        $periodEnd->add(new \DateInterval('P1D'))->sub(new \DateInterval('PT1S'));
        return $periodEnd;
    }
}
