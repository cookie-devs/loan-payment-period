<?php

declare(strict_types=1);

namespace Kauri\Loan;

/**
 * Class Period
 * @package Kauri\Loan
 */
class Period implements PeriodInterface
{
    /**
     * @var \DateTimeInterface
     */
    private $start;
    /**
     * @var \DateTimeInterface
     */
    private $end;
    /**
     * @var float
     */
    private $exactLength;
    /**
     * @var float
     */
    private $avgLength;
    /**
     * @var int
     */
    private $sequenceNo;

    /**
     * Period constructor.
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float $avgLength
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end, float $avgLength)
    {
        $this->start = $start;
        $this->end = $end;
        $this->exactLength = self::calculatePeriodLength($start, $end);
        $this->avgLength = $avgLength;
    }

    /**
     * @param int $lengthMode
     * @return float
     */
    public function getLength(int $lengthMode): float
    {
        if (self::LENGTH_MODE_EXACT == $lengthMode) {
            return $this->getExactLength();
        }

        return $this->getAvgLength();
    }

    /**
     * @return float
     */
    public function getAvgLength(): float
    {
        return $this->avgLength;
    }

    /**
     * @return float
     */
    public function getExactLength(): float
    {
        return $this->exactLength;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    /**
     * @param $sequenceNo
     * @return bool|int
     */
    public function setSequenceNo(int $sequenceNo)
    {
        if (is_null($this->sequenceNo)) {
            $this->sequenceNo = $sequenceNo;
        }

        return $this->sequenceNo;
    }

    /**
     * @return int
     */
    public function getSequenceNo(): int
    {
        return (int) $this->sequenceNo;
    }

    /**
     * @param \DateTimeInterface $periodStart
     * @param \DateTimeInterface $periodEnd
     * @return int
     */
    private static function calculatePeriodLength(\DateTimeInterface $periodStart, \DateTimeInterface $periodEnd): int
    {
        $diff = (int) $periodEnd->diff($periodStart)->days + 1;
        return $diff;
    }

}
