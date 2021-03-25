<?php

namespace App\Model\Services;

use App\Model\ClientCallStatistic;
use Exception;

/**
 * Class ImportClientCallStatistic
 * @package App\Controller
 */
class ImportClientCallStatistic
{
    /**
     * @var array
     */
    public array $importedFile;

    /**
     * @var array
     */
    public array $fileData = [];

    /**
     * @var array
     */
    public array $calls_statistic = [];

    /**
     * ImportClientCallStatistic constructor.
     */
    public function __construct()
    {
        $this->importedFile = $_FILES['file'];
    }

    /**
     * @return array|null
     */
    public function fileDataHandler(): ?array
    {
        try {
            if ($this->importedFile['type'] !== 'application/vnd.ms-excel') {
                errorResponse(415, 'Файл должен быть формата .csv');
            }

            if (!$this->fileData = file($this->importedFile['tmp_name'])) {
                errorResponse(422, 'Ошибка обработки импортируемого файла');
            }

            $ipDataHandler = new IPDataHandler();
            $phoneCodeHandler = new CountryCodeHandler();

            foreach ($this->fileData as $data) {
                $call = explode(',', $data);

                if (filter_var((int)$client_id = $call[0], FILTER_VALIDATE_INT)) {
                    if (!$client_call_stat = &$this->calls_statistic[$client_id]) {
                        $client_call_stat = new ClientCallStatistic();
                        $client_call_stat->client_id = $client_id;
                    }

                    if (filter_var($ip = trim($call[4]),
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE
                    )) {
                        $ipCountry = $ipDataHandler->getIPData($ip);

                        if (!in_array($ipCountry->country_code, array_column($client_call_stat->client_countries, 'country_code'))) {
                            $client_call_stat->client_countries[] = $ipCountry;
                        }
                    }

                    if (strlen((string)$phone_number = $call[3]) <= 14 ? $phone_number : false) {
                        $continentCode = $phoneCodeHandler->getContinentCodeByPhone($phone_number);

                        if ($continentCode) {
                            $client_call_stat->updateCallsDurationContinent($continentCode, (int)$call[2]);
                            $client_call_stat->updateCallsCountContinent($continentCode);
                        }
                    }

                    $client_call_stat->calls_total_duration += (int)$call[2];
                    $client_call_stat->total_calls_count++;
                }
            }

            array_values($this->calls_statistic);
            usort($this->calls_statistic, fn($a, $b) => strcmp($a->client_id, $b->client_id));
        } catch (Exception $exception) {
            handleException($exception);
        }

        return $this->calls_statistic;
    }
}
