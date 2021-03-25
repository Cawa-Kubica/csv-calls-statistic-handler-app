<?php

namespace App\Model;

/**
 * Class ClientCallStatistic
 * @package App\Model
 */
class ClientCallStatistic
{
    /**
     * @var int
     */
    public int $client_id;

    /**
     * @var array
     */
    public array $calls_count_by_continents = [];

    /**
     * @var int
     */
    public int $total_calls_count = 0;

    /**
     * @var array
     */
    public array $calls_duration_by_continents = [];

    /**
     * @var int
     */
    public int $calls_total_duration = 0;

    /**
     * @var array
     */
    public array $client_countries = [];

    /**
     * @param string $code
     * @param int $duration
     */
    public function updateCallsDurationContinent(string $code, int $duration)
    {
        if (!array_key_exists($code, $this->calls_duration_by_continents)) {
            $this->addCallsDurationContinent($code);
        }

        $this->calls_duration_by_continents[$code] += $duration;
    }

    /**
     * @param string $code
     */
    public function updateCallsCountContinent(string $code)
    {
        if (!array_key_exists($code, $this->calls_count_by_continents)) {
            $this->addCallsCountContinent($code);
        }

        $this->calls_count_by_continents[$code]++;
    }


    /**
     * @param string $code
     */
    private function addCallsDurationContinent(string $code)
    {
        $this->calls_duration_by_continents[$code] = 0;
    }

    /**
     * @param string $code
     */
    private function addCallsCountContinent(string $code)
    {
        $this->calls_count_by_continents[$code] = 0;
    }

}