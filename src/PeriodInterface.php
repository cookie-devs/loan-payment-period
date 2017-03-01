<?php

declare(strict_types = 1);

namespace Kauri\Loan;


interface PeriodInterface
{
    /**
     * PeriodInterface constructor.
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end);

    /**
     * @return int
     */
    public function getLength(): int;

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
