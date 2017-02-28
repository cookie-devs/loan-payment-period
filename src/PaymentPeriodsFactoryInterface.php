<?php

declare(strict_types = 1);

namespace Kauri\Loan;


/**
 * Interface PaymentPeriodsFactoryInterface
 * @package Kauri\Loan
 */
interface PaymentPeriodsFactoryInterface
{
    /**
     * @param PaymentScheduleInterface $paymentSchedule
     * @return PaymentPeriodsInterface
     */
    public static function generate(PaymentScheduleInterface $paymentSchedule): PaymentPeriodsInterface;
}
