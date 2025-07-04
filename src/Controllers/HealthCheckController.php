<?php

namespace App\Controllers;

class HealthCheckController{
    public function up(){
        return ['status' => 'UP'];
    }
}