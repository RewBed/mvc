<?php

namespace App\Services;

use App\Entity\SensorReading;

class SensorsService
{

    /**
     * Парсинг строки с данными
     *
     * @param string $data
     * Строка команды
     *
     * @return SensorReading
     */
    public function parseData(string $data) : SensorReading {

        $arr = explode(',', $data);

        $sensorReading = new SensorReading();

        $sensorReading->temperature = $arr[1];
        $sensorReading->humidity = $arr[2];
        $sensorReading->light = $arr[3];

        return $sensorReading;
    }
}