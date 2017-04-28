<?php

declare(strict_types = 1);

namespace Kauri\Loan;

/**
 * Class PaymentPeriods
 * @package Kauri\Loan
 */
class PaymentPeriods implements PaymentPeriodsInterface
{
    /**
     * @var array
     */
    private $periods = array();
    /**
     * @var int
     */
    private $averagePeriodLenght;

    /**
     * PaymentPeriods constructor.
     * @param int $averagePeriodLength
     */
    public function __construct(int $averagePeriodLength)
    {
        $this->averagePeriodLenght = $averagePeriodLength;
    }

    /**
     * @param PeriodInterface $period
     * @param int|null $sequenceNo
     */
    public function add(PeriodInterface $period, int $sequenceNo = null): void
    {
        if (is_null($sequenceNo)) {
            $sequenceNo = $this->getNoOfPeriods() + 1;
        }

        $period->setSequenceNo($sequenceNo);
        $this->periods[$sequenceNo] = $period;
    }

    public function getAvgPeriodLength(): float
    {
        return $this->averagePeriodLenght;
    }

    /**
     * @return array
     */
    public function getPeriods(): array
    {
        return $this->periods;
    }

    /**
     * @return int
     */
    public function getNoOfPeriods(): int
    {
        return count($this->periods);
    }
}
