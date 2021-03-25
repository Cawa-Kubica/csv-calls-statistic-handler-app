<?php

namespace App\Model;

/**
 * Class IPData
 * @package App\Model
 */
class IPData
{
    /**
     * @var string
     */
    public string $ip;

    /**
     * @var string
     */
    public ?string $continent_code;

    /**
     * @var string
     */
    public ?string $country_code;

    /**
     * @var string|null
     */
    public ?string $flag;
}