<?php

namespace App\Report;

class ReportData
{
    protected float $gain;
    protected float $tax;
    protected string $startDate;
    protected string $endDate;

    public function __construct(
        float $gain,
        float $tax,
        string $startDate,
        string $endDate
    )
    {
        $this->gain = $gain;
        $this->tax = $tax;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function __get(string $name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
        throw new \DomainException(sprintf("Unknown property: %s", $name));
    }
}