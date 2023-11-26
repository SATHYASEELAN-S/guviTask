<?php
require_once("../redis/vendor/predis/predis/autoload.php");
require_once("../redis/vendor/autoload.php");
require_once("../mongodb/vendor/autoload.php");
use MongoDB\Client as MongoClient;

$redis = new Predis\Client();
$redisKey = "user";
$userEmail = $redis->get("logged-mail");

$mongoDB = 'myDB'; 

$mongoClient = new MongoClient("mongodb://localhost:27017");
$database = $mongoClient->$mongoDB;
$collection = $database->userProfile; 

if ($userEmail !== null) {
    try {
        $existingDocument = $collection->findOne(['email' => $userEmail]);

        if (!$existingDocument) {
            $newDocument = [
                'email' => $userEmail,
                'name' => "NA",
                'dob' => "NA",
                'address' => "NA",
                'bio' => "NA",
                'linkedin' => "NA",
                'github' => 'NA',
                'twitter' => 'NA',
            ];

            $result = $collection->insertOne($newDocument);

            if ($result->getInsertedCount() > 0) {
                echo 'Document inserted in MongoDB successfully.';
            } else {
                echo 'Failed to insert document in MongoDB.';
            }
        }

        // Fetch user data
        $userData = $collection->findOne(['email' => $userEmail]);

        if (!$userData) {
            echo 'User data not found in MongoDB.';
        }

    } catch (\Exception $e) {
        echo 'Error connecting to MongoDB: ' . $e->getMessage();
    }
} else {
    echo "User email not found in Redis.";
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $document = $collection->findOne(['email' => $userEmail]);

    if ($document !== null) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'name' => $document['name'],
                'email' => $document['email'],
                'dob' => $document['dob'],
                'address' => $document['address'],
                'bio' => $document['bio'],
                'linkedin' => $document['linkedin'],
                'github' => $document['github'],
                'twitter' => $document['twitter'],
            ],
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Document not found',
        ]);
    }
}
?>
