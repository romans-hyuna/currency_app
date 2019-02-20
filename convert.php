<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Converter;

echo Converter::convert($_POST);
die;