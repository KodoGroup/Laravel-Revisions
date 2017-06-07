<?php

function config($key)
{
    $config = require_once(__DIR__.'/../config/revision.php');

    return \Illuminate\Support\Arr::get($config, str_replace('revision.', '', $key));
}
