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
    private $averagePeriod;

    /**
     * PaymentPeriods constructor.
     * @param int $averagePeriod
     */
    public function __construct(int $averagePeriod)
    {
        $this->averagePeriod = $averagePeriod;
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
     * @param PeriodInterface $period
     * @param float $yearlyInterestRate
     * @param int $calculationType
     * @param int $calculateFor
     * @return float
     * @throws \Exception
     */
    public function getRatePerPeriod(
        PeriodInterface $period,
        float $yearlyInterestRate,
        int $calculationType = self::CALCULATION_MODE_AVERAGE,
        int $calculateFor = self::CALCULATE_FOR_INTEREST
    ): float {
        switch ($calculationType) {
            case self::CALCULATION_MODE_EXACT:
            case self::CALCULATION_MODE_EXACT_INTEREST:
            case self::CALCULATION_MODE_AVERAGE:
                $currentPeriod = $this->getCurrentPeriod($period, $calculationType, $calculateFor);
                break;
            default:
                throw new \Exception('Calculation type not implemented');
        }

        $ratePerPeriod = $yearlyInterestRate / 360 * $currentPeriod;

        return $ratePerPeriod;
    }

    /**
     * @param PeriodInterface $period
     * @param int $calculationType
     * @param int $calculateFor
     * @return float
     * @throws \Exception
     */
    public function getNumberOfPeriods(
        PeriodInterface $period,
        int $calculationType = self::CALCULATION_MODE_AVERAGE,
        int $calculateFor = self::CALCULATE_FOR_PAYMENT
    ): float {
        switch ($calculationType) {
            case self::CALCULATION_MODE_EXACT:
            case self::CALCULATION_MODE_EXACT_INTEREST:
            case self::CALCULATION_MODE_AVERAGE:
                $totalPeriods = $this->getTotalPeriods($calculationType, $calculateFor);
                break;
            default:
                throw new \Exception('Calculation type not implemented');
        }

        $currentPeriod = $this->getCurrentPeriod($period, $calculationType, $calculateFor);

        $numberOfPeriods = $totalPeriods / $currentPeriod;

        return $numberOfPeriods;
    }

    /**
     * @return int
     */
    public function getExactPeriodsLength()
    {
        $followingPeriods = $this->getPeriods();
        $remainingPeriodsLength = 0;


        foreach ($followingPeriods as $period) {
            $remainingPeriodsLength += $period->getLength();
        }

        return $remainingPeriodsLength;
    }

    /**
     * @return int
     */
    public function getAveragePeriodsLength()
    {
        $followingPeriods = $this->getPeriods();
        $remainingPeriodsLength = $this->averagePeriod * count($followingPeriods);

        return $remainingPeriodsLength;
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

    /**
     * @param PeriodInterface $period
     * @param int $calculationType
     * @param int $calculateFor
     * @return int
     */
    private function getCurrentPeriod(PeriodInterface $period, int $calculationType, int $calculateFor)
    {
        if ($calculationType == self::CALCULATION_MODE_EXACT) {
            return $period->getLength();
        }

        if ($calculateFor == self::CALCULATE_FOR_INTEREST && $calculationType == self::CALCULATION_MODE_EXACT_INTEREST) {
            return $period->getLength();
        }

        return $this->averagePeriod;
    }

    /**
     * @param int $calculationType
     * @param int $calculateFor
     * @return int
     */
    private function getTotalPeriods(int $calculationType, int $calculateFor)
    {
        if ($calculationType == self::CALCULATION_MODE_EXACT) {
            return $this->getExactPeriodsLength();
        }

        if ($calculateFor == self::CALCULATE_FOR_INTEREST && $calculationType == self::CALCULATION_MODE_EXACT_INTEREST) {
            return $this->getExactPeriodsLength();
        }

        return $this->getAveragePeriodsLength();
    }


}
