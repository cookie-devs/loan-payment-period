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
     * @return float
     */
    public function getAvgPeriodLength(): float;

    /**
     * @return array
     */
    public function getPeriods(): array;

    /**
     * @return int
     */
    public function getNoOfPeriods(): int;
}
