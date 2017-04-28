<?php

declare(strict_types = 1);

namespace Kauri\Loan;


interface PeriodInterface
{
    const LENGTH_MODE_EXACT = 1;
    const LENGTH_MODE_AVG = 2;

    /**
     * PeriodInterface constructor.
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float $avgLength
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end, float $avgLength);

    /**
     * @param int $lengthMode
     * @return float
     */
    public function getLength(int $lengthMode): float;

    /**
     * @return float
     */
    public function getAvgLength(): float;

    /**
     * @return float
     */
    public function getExactLength(): float;

    /**
     * @return \DateTimeInterface
     */
    public function getEnd(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getStart(): \DateTimeInterface;

    /**
     * @param int $sequenceNo
     * @return mixed
     */
    public function setSequenceNo(int $sequenceNo);

    /**
     * @return int
     */
    public function getSequenceNo(): int;

}
