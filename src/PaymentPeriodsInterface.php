<?php

declare(strict_types = 1);

namespace Kauri\Loan;

/**
 * Interface PaymentPeriodsInterface
 * @package Kauri\Loan
 */
interface PaymentPeriodsInterface
{
    /**
     * Exact payment with exact interest
     */
    const CALCULATION_MODE_EXACT = 1;
    /**
     * Annuity payment with exact interest
     */
    const CALCULATION_MODE_EXACT_INTEREST = 2;
    /**
     * Annuity payment with annuity interest
     */
    const CALCULATION_MODE_AVERAGE = 3;

    const CALCULATE_FOR_PAYMENT = 4;

    const CALCULATE_FOR_INTEREST = 5;

    /**
     * PaymentPeriodsInterface constructor.
     * @param int $averagePeriod
     */
    public function __construct(int $averagePeriod);

    /**
     * @param PeriodInterface $period
     * @param int|null $sequenceNo
     */
    public function add(PeriodInterface $period, int $sequenceNo = null): void;

    /**
     * @param PeriodInterface $period
     * @param float $yearlyInterestRate
     * @param int $calculationType
     * @return float
     */
    public function getRatePerPeriod(PeriodInterface $period, float $yearlyInterestRate, int $calculationType): float;

    /**
     * @param PeriodInterface $period
     * @param int $calculationType
     * @return float|int
     */
    public function getNumberOfRemainingPeriods(PeriodInterface $period, int $calculationType): float;

    /**
     * @return array
     */
    public function getPeriods(): array;

    /**
     * @return int
     */
    public function getNoOfPeriods(): int;
}
