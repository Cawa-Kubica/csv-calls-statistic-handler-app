<?php

namespace App\Model\Services;

use Exception;

/**
 * Class CountryCodeHandler
 * @package App\Model\Services
 */
class CountryCodeHandler
{
    /**
     * @var array
     */
    private array $countryCodes = [];

    /**
     * @var string|null
     */
    private ?string $phoneContinentCode = null;

    /**
     * CountryCodeHandler constructor.
     */
    public function __construct()
    {
        $countryCodesFile = file_get_contents(RESOURCES . "data/country_codes.json");
        $this->countryCodes = json_decode($countryCodesFile, true);
    }

    /**
     * @param string $phoneNumber
     * @return string|null
     */
    public function getContinentCodeByPhone(string $phoneNumber): ?string
    {
        try {
            foreach ($this->countryCodes as $countryCode) {
                if (substr($phoneNumber, 0, strlen($countryCode['phone'])) === $countryCode['phone']) {
                    $this->phoneContinentCode = $countryCode['continent']['code'];
                }
            }
        } catch (Exception $exception) {
            handleException($exception);
        }

        return $this->phoneContinentCode;
    }
}