<?php


namespace App\Controllers;

use App\Entity\SensorReading;
use App\Services\SensorsService;
use Core\BaseController;
use Core\MVC;
use DateTime;

class SensorReadingController extends BaseController
{

    private SensorsService $sensorsService;

    public function __construct()
    {
        parent::__construct();
        $this->sensorsService = new SensorsService();
    }

    /**
     *  Сохранить показания
     */
    public function set() : void {
        $sensorReading = $this->sensorsService->parseData($this->post["command"]);
        $sensorReading->save();

        if($sensorReading->id) {

            $date = new DateTime();

            $sensorReading->insertDate = $date->getTimestamp();
            MVC::$redis->publish('eustatos', json_encode($sensorReading));
            MVC::$response->sendRes(1);
        }

        else
            MVC::$response->sendRes(0);
    }

    /**
     * Получить список показаний
     */
    public function list() : void {
        MVC::$response->accessCors();
        $list = MVC::$pdo->find('sensor_readings')
            ->order('id', 'DESC')
            ->limit(100)
            ->get(SensorReading::class);
        MVC::$response->sendRes(array_reverse($list));
    }
}