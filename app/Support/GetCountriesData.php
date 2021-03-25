<?php

namespace App\Support;

use App\Traits\GuzzleRequest;
use Exception;

/**
 * Class GetCountriesData
 * @package App\Support
 */
class GetCountriesData
{
    use GuzzleRequest;

    /**
     * GetCountriesData constructor.
     */
    public function __construct()
    {
        $this->guzzleInitClient();
    }

    public function getDataToFile()
    {
        try {
            $countries_response = $this->requestJSONData(
                'POST',
                'https://countries.trevorblades.com/',
                [
                    'headers' => [],
                    'json' => [
                        'query' => 'query {
                                    countries {
                                        code
                                        continent {
                                            code
                                        }
                                    phone
                              }
                            }'
                    ],
                ]
            );

            if (!empty($response = $countries_response['data'])) {
                file_put_contents(RESOURCES . 'data/country_codes.json', json_encode($response['countries']));
            }
        } catch (Exception $exception) {
            handleException($exception);
        }

        return;
    }
}