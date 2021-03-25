<?php

namespace App\Controller;

use App\Model\Services\ImportClientCallStatistic;
use Exception;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController
{
    /**
     * MainController constructor.
     */
    public function __construct()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file'])) {
                $importDataHandler = new ImportClientCallStatistic();

                $this->callsStatisticDataResponse($importDataHandler->fileDataHandler());
            } else {
                $this->index();
            }
        } catch (Exception $exception) {
            handleException($exception);
        }
    }

    /**
     * @param array|null $dataResponse
     */
    private function callsStatisticDataResponse(?array $dataResponse)
    {
        header("Content-Type: application/json");

        echo json_encode($dataResponse);
    }

    private function index()
    {
        require APP . 'view/home.php';
    }
}