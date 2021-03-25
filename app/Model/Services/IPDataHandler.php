<?php

namespace App\Model\Services;

use App\Model\IPData;
use App\Traits\GuzzleRequest;
use Exception;

/**
 * Class IPDataHandler
 * @package App\Model\Services
 */
class IPDataHandler
{
    use GuzzleRequest;

    /**
     * @var array
     */
    private array $ipProcessedList = [];

    /**
     * @var IPData|null
     */
    private ?IPData $ip_item;

    /**
     * IPDataHandler constructor.
     */
    public function __construct()
    {
        $this->guzzleInitClient();
    }

    /**
     * @param string $ip
     * @return IPData|null
     */
    public function getIPData(string $ip): ?IPData
    {
        try {
            if ($ip_key = array_search($ip, array_column($this->ipProcessedList, 'ip')) === false) {
                if ($ip_info = $this->requestJSONData(
                    'GET',
                    'http://api.ipstack.com/' . $ip . '?access_key=' . IPSTACK_API_KEY
                )) {
                    $this->ip_item = new IPData();
                    $this->ip_item->ip = $ip;
                    $this->ip_item->continent_code = !empty($ip_info['continent_code']) ?
                        (string)$ip_info['continent_code'] : null;
                    $this->ip_item->country_code = !empty($ip_info['country_code']) ?
                        (string)$ip_info['country_code'] : null;
                    $this->ip_item->flag = !empty($ip_info['location']) ?
                        (string)$ip_info['location']['country_flag'] : null;

                    $this->ipProcessedList[] = $this->ip_item;
                }

                // OR USE GeoIP PECL-extension

//                if ($ip_info = geoip_record_by_name($ip)) {
//                    $this->ip_item = new IPData();
//                    $this->ip_item->ip = $ip;
//                    $this->ip_item->continent_code = $ip_info['continent_code'];
//                    $this->ip_item->country_code = $ip_info['country_code'];
//
//                    $this->ipProcessedList[] = $this->ip_item;
//                }
            } else {
                $this->ip_item = $this->ipProcessedList[$ip_key];
            }
        } catch (Exception $exception) {
            handleException($exception);
        }

        return $this->ip_item;
    }
}