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
     * Exact payment with exact interest
     */
    const CALCULATION_TYPE_EXACT = 1;
    /**
     * Annuity payment with exact interest
     */
    const CALCULATION_TYPE_EXACT_INTEREST = 2;
    /**
     * Annuity payment with annuity interest
     */
    const CALCULATION_TYPE_ANNUITY = 3;

    /**
     * @var array
     */
    private $periods = array();
    /**
     * @var int
     */
    private $totalLength = 0;
    /**
     * @var int
     */
    private $averagePeriod;
    /**
     * @var int
     */
    private $averageTotalPeriod;

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
        $this->periods[$sequenceNo] = $period;
        $this->totalLength = $this->totalLength + $period->getLength();
        $this->averageTotalPeriod = count($this->periods) * $this->averagePeriod;
    }

    /**
     * @param PeriodInterface $period
     * @param float $yearlyInterestRate
     * @param int $calculationType
     * @return float
     * @throws \Exception
     */
    public function getRatePerPeriod(
        PeriodInterface $period,
        float $yearlyInterestRate,
        int $calculationType = self::CALCULATION_TYPE_ANNUITY
    ): float {
        switch ($calculationType) {
            case self::CALCULATION_TYPE_EXACT:
            case self::CALCULATION_TYPE_EXACT_INTEREST:
                $currentPeriod = $period->getLength();
                break;
            case self::CALCULATION_TYPE_ANNUITY:
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
     * @return float|int
     * @throws \Exception
     */
    public function getNumberOfPeriods(
        PeriodInterface $period,
        int $calculationType = self::CALCULATION_TYPE_ANNUITY
    ): float {
        switch ($calculationType) {
            case self::CALCULATION_TYPE_EXACT:
                $currentPeriod = $period->getLength();
                $totalPeriods = $this->totalLength;
                break;
            case self::CALCULATION_TYPE_EXACT_INTEREST:
            case self::CALCULATION_TYPE_ANNUITY:
                $currentPeriod = $this->averagePeriod;
                $totalPeriods = $this->averageTotalPeriod;
                break;
            default:
                throw new \Exception('Calculation type not implemented');
        }

        $numberOfPeriods = $totalPeriods / $currentPeriod;

        return $numberOfPeriods;
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