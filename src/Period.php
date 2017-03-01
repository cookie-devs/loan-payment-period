<?php

declare(strict_types = 1);

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
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $sequenceNo;

    /**
     * Period constructor.
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->length = self::calculatePeriodLength($start, $end);
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

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
        return (int)$this->sequenceNo;
    }

    /**
     * @param \DateTimeInterface $periodStart
     * @param \DateTimeInterface $periodEnd
     * @return int
     */
    private static function calculatePeriodLength(\DateTimeInterface $periodStart, \DateTimeInterface $periodEnd): int
    {
        $diff = (int)$periodEnd->diff($periodStart)->days + 1;
        return $diff;
    }

}
