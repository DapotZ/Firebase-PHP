<?php

namespace App\Controller;

use App\Model\Temperature;
use App\Model\FirestoreData;
use Kreait\Firebase\Database;
use Firebase\JWT\JWT;

class TemperatureController
{
    private $database;
    private $firebaseCredentialsPath;
    private $firebaseProjectId;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->firebaseCredentialsPath = __DIR__ . '/../../public/firebase_credentials.json';
        $this->firebaseProjectId = 'pblproject-a7c8d';
        date_default_timezone_set('Asia/Jakarta');
    }

    public function getFirestoreData($collectionName)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->firebaseProjectId}/databases/(default)/documents/{$collectionName}";
        $accessToken = $this->getAccessToken();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        // Debugging: Log the response
        error_log(print_r($data, true));

        if (isset($data['documents'])) {
            return $data['documents'];
        } else {
            // Handle error case
            error_log("No documents found in the collection.");
            return [];
        }
    }

    private function getAccessToken()
    {
        $data = json_decode(file_get_contents($this->firebaseCredentialsPath), true);

        if (isset($data['private_key']) && isset($data['client_email'])) {
            $now = time();
            $payload = [
                'iss' => $data['client_email'],
                'sub' => $data['client_email'],
                'aud' => 'https://firestore.googleapis.com/google.firestore.v1.Firestore',
                'iat' => $now,
                'exp' => $now + 3600,
                'uid' => $data['client_email'],
            ];

            return JWT::encode($payload, $data['private_key'], 'RS256');
        } else {
            die('Error: "private_key" or "client_email" not found in Firebase credentials.');
        }
    }

    public function getTemperature()
    {
        $reference = $this->database->getReference('temperature');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $value = $snapshot->getValue();

            // Pastikan nilai temperature adalah numerik
            if (is_numeric($value)) {
                return new Temperature($value);
            } else {
                echo "Invalid temperature value found in 'temperature' node.";
            }
        } else {
            echo "No temperature data available.";
        }

        return null;
    }

    public function getHealthStatus($temperature)
    {
        if ($temperature >= 36.5 && $temperature <= 37.5) {
            return [
                'text' => "You are healthy",
                'image' => "assets/images/love.png"
            ];  
        } elseif ($temperature > 37.5 && $temperature <= 38) {
            return [
                'text' => "You might need more rest",
                'image' => "assets/images/sadface.png"
            ];
        } elseif ($temperature > 38) {
            return [
                'text' => "You're sick, see a doctor!",
                'image' => "assets/images/fever.png"
            ];
        } else {
            return [
                'text' => "You're sick, see a doctor!",
                'image' => "assets/images/fever.png"
            ];
        }
    }

    public function getFieldFirestore($page = 1, $itemsPerPage = 5)
    {
        $data = $this->getFirestoreData('Temperatures');
        
        if (!empty($data)) {
            $temperatures = [];
            $start = ($page - 1) * $itemsPerPage;
            $documents = array_slice($data, $start, $itemsPerPage);

            foreach ($documents as $document) {
                $fields = $document['fields'];
                $temperature = isset($fields['Temperature']['stringValue']) ? $fields['Temperature']['stringValue'] : '';
                $timestamp = isset($fields['Temperature']['stringValue']) ? $fields['TimeStamp']['stringValue'] : '';

                $temperatureObj = new FirestoreData($temperature, $timestamp);
                $temperatures[] = $temperatureObj;
            }
            return $temperatures;
        } else {
            // Debugging: Log the case when no documents are found
            error_log("No documents found for the current page.");
            return [];
        }
    }
}
