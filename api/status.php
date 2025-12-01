<?php

require "lib.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    returnJsonHttpResponse(200, ["status" => "OK"]);
}