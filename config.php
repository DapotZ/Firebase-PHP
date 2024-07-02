<?php

require __DIR__.'/vendor/autoload.php'; 

use Kreait\Firebase\Factory;


$databaseURL = 'https://pblproject-a7c8d-default-rtdb.asia-southeast1.firebasedatabase.app/';
$factory = (new Factory)
    ->withDatabaseUri($databaseURL);
$database = $factory->createDatabase();

define('FIREBASE_PROJECT_ID', 'pblproject-a7c8d');
define('FIREBASE_CREDENTIALS_PATH', __DIR__ . '/public/firebase_credentials.json');
