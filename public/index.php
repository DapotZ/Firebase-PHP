<?php

require __DIR__.'/../config.php'; 
use App\Controller\TemperatureController;

$temperatureController = new TemperatureController($database);

$temperature = $temperatureController->getTemperature();

if ($temperature) {
    // Ambil status kesehatan berdasarkan temperatur
    $healthStatus = $temperatureController->getHealthStatus($temperature->temperature);
} else {
    $healthStatus = [
        'text' => "No temperature data available.",
        'image' => "assets/images/error.png"
    ];
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 3;

$firestoreData = $temperatureController->getFieldFirestore($page, $itemsPerPage);
$totalItems = count($temperatureController->getFirestoreData('Temperatures')); 
$totalPages = ceil($totalItems / $itemsPerPage);

$healthStatusFirestore = [];
foreach ($firestoreData as $data) {
    $healthStatusFirestore[] = $temperatureController->getHealthStatus($data->getValue());
}


include __DIR__.'/../src/view/temperature.php';

