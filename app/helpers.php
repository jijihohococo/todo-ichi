<?php

function request()
{
    $rawInput = file_get_contents('php://input');
    $jsonInput = json_decode($rawInput, true);
    $requestData = (is_array($jsonInput) && !empty($jsonInput)) ? $jsonInput : $_REQUEST;
    return $requestData;
}
