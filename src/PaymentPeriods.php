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
                $currentPeriod = $period->getLength();
                break;
            case self::CALCULATION_MODE_EXACT_INTEREST:
                if ($calculateFor == self::CALCULATE_FOR_INTEREST) {
                    $currentPeriod = $period->getLength();
                } else {
                    $currentPeriod = $this->averagePeriod;
                }
                break;
            case self::CALCULATION_MODE_AVERAGE:
                $currentPeriod = $this->averagePeriod;
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
    public function getNumberOfRemainingPeriods(
        PeriodInterface $period,
        int $calculationType = self::CALCULATION_MODE_AVERAGE,
        int $calculateFor = self::CALCULATE_FOR_PAYMENT
    ): float {
        switch ($calculationType) {
            case self::CALCULATION_MODE_EXACT:
                $currentPeriod = $period->getLength();
                $totalPeriods = $this->getExactRemainingPeriodsLength($period);
                break;
            case self::CALCULATION_MODE_EXACT_INTEREST:
                if ($calculateFor == self::CALCULATE_FOR_PAYMENT) {
                    $currentPeriod = $this->averagePeriod;
                    $totalPeriods = $this->getAverageRemainingPeriodsLength($period);
                } else {
                    $currentPeriod = $period->getLength();
                    $totalPeriods = $this->getExactRemainingPeriodsLength($period);
                }
                break;
            case self::CALCULATION_MODE_AVERAGE:
                $currentPeriod = $this->averagePeriod;
                $totalPeriods = $this->getAverageRemainingPeriodsLength($period);
                break;
            default:
                throw new \Exception('Calculation type not implemented');
        }

        //$numberOfPeriods = ($currentPeriod + $totalPeriods) / $currentPeriod;
        $numberOfPeriods = $totalPeriods / $currentPeriod;

        return $numberOfPeriods;
    }

    /**
     * @param PeriodInterface $currentPeriod
     * @return int
     */
    public function getExactRemainingPeriodsLength(PeriodInterface $currentPeriod)
    {
        //$followingPeriods = $this->getFollowingPeriods($currentPeriod->getSequenceNo());
        $followingPeriods = $this->getPeriods();
        $remainingPeriodsLength = 0;


        foreach ($followingPeriods as $period) {
            $remainingPeriodsLength += $period->getLength();
        }

        return $remainingPeriodsLength;
    }

    /**
     * @param PeriodInterface $currentPeriod
     * @return int
     */
    public function getAverageRemainingPeriodsLength(PeriodInterface $currentPeriod)
    {
        //$followingPeriods = $this->getFollowingPeriods($currentPeriod->getSequenceNo());
        $followingPeriods = $this->getPeriods();
        $remainingPeriodsLength = $this->averagePeriod * count($followingPeriods);

        return $remainingPeriodsLength;
    }

    /**
     * @param int $currentPeriodSequenceNo
     * @return array
     */
    private function getFollowingPeriods(int $currentPeriodSequenceNo): array
    {
        $followingPeriods = array();

        foreach ($this->getPeriods() as $period) {
            if ($period->getSequenceNo() > $currentPeriodSequenceNo) {
                array_push($followingPeriods, $period);
            }
        }

        return $followingPeriods;
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
