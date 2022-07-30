<?php

namespace App\Entity;

use Core\Entity;
use DateTime;

class SensorReading extends Entity
{
    protected string $table = 'sensor_readings';
    protected bool $withoutTime = true;

    public int $temperature;
    public int $humidity;
    public int $light;
    public string $insertDate;
}