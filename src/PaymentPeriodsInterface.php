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
