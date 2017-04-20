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
     * @param int $averagePeriodLenght
     */
    public function __construct(int $averagePeriodLenght)
    {
        $this->averagePeriodLenght = $averagePeriodLenght;
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

    /**
     * @param int $calculationMode
     * @return array
     */
    public function getPeriodsLengths(int $calculationMode = self::CALCULATION_MODE_AVERAGE): array
    {
        $periodsLengths = array();

        /** @var PeriodInterface $period */
        foreach ($this->getPeriods() as $period) {
            $length = $period->getLength();

            if ($calculationMode == self::CALCULATION_MODE_AVERAGE)
            {
                $length = $this->averagePeriodLenght;
            }
            $periodsLengths[$period->getSequenceNo()] = $length;
        }

        return $periodsLengths;
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
