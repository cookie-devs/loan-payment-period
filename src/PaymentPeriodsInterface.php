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
     * Annuity payment with annuity interest
     */
    const CALCULATION_MODE_AVERAGE = 2;

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
     * @param int $calculationMode
     * @return array
     */
    public function getPeriodsLengths(int $calculationMode = self::CALCULATION_MODE_AVERAGE): array;

    /**
     * @return array
     */
    public function getPeriods(): array;

    /**
     * @return int
     */
    public function getNoOfPeriods(): int;
}
