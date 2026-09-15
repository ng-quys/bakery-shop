<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

echo json_encode([
    "status" => "success",
    "message" => "Backend PHP API connected successfully!"
]);