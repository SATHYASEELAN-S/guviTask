<?php

// Include necessary files
require_once("../redis/vendor/predis/predis/autoload.php");
require_once("../redis/vendor/autoload.php");
require_once("../mongodb/vendor/autoload.php");

use MongoDB\BSON\ObjectId; // Import ObjectId class

// MongoDB configuration
$mongoDB = 'myDB'; // Change this to your actual database name

// Create a MongoDB connection
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");

    // Check if the connection was successful
    if ($mongoClient) {
        // Select the database
        $database = $mongoClient->$mongoDB;

        // Select the collection (table) within the database
        $collection = $database->userProfile; // Assuming your collection is named 'userProfile'
    } else {
        // Handle connection errors
        echo json_encode(['status' => 'error', 'message' => "Error connecting to MongoDB"]);
        exit();
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error connecting to MongoDB: " . $e->getMessage()]);
    exit();
}

// Get user email from Redis
$redis = new Predis\Client();
$redisKey = "user";
$userEmail = $redis->get("logged-mail");

// Check if the form is submitted and the action is updateForm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "updateForm") {
    // Retrieve form data
    // Note: Validate and sanitize user input to prevent security issues

    // Example: Assuming you have fields 'name', 'dob', 'address', 'bio', 'linkedin', 'github', and 'twitter' in your form
    $name = $_POST['name'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $address = $_POST['address'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $linkedin = $_POST['linkedin'] ?? null;
    $github = $_POST['github'] ?? null;
    $twitter = $_POST['twitter'] ?? null;

    // Filter based on email
    $filter = ['email' => $userEmail];

    // Fetch existing data
    $existingData = $collection->findOne($filter);

    // Data to be updated
    if ($existingData) {
        $updateData = ['$set' => []];

        // Add fields to update only if they are not empty
        if (!empty($name)) {
            $updateData['$set']['name'] = $name;
        } else {
            // If field is empty, set it to the existing value
            $updateData['$set']['name'] = $existingData['name'];
        }

        if (!empty($dob)) {
            $updateData['$set']['dob'] = $dob;
        } else {
            $updateData['$set']['dob'] = $existingData['dob'];
        }

        if (!empty($address)) {
            $updateData['$set']['address'] = $address;
        } else {
            $updateData['$set']['address'] = $existingData['address'];
        }

        if (!empty($bio)) {
            $updateData['$set']['bio'] = $bio;
        } else {
            $updateData['$set']['bio'] = $existingData['bio'];
        }

        if (!empty($linkedin)) {
            $updateData['$set']['linkedin'] = $linkedin;
        } else {
            $updateData['$set']['linkedin'] = $existingData['linkedin'];
        }

        if (!empty($github)) {
            $updateData['$set']['github'] = $github;
        } else {
            $updateData['$set']['github'] = $existingData['github'];
        }

        if (!empty($twitter)) {
            $updateData['$set']['twitter'] = $twitter;
        } else {
            $updateData['$set']['twitter'] = $existingData['twitter'];
        }

        // Update the data in the collection
        $result = $collection->updateOne($filter, $updateData);

        if ($result->getModifiedCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes or error occurred during the update']);
        }
    } else {
        // Invalid action or user not found
        echo json_encode(['status' => 'error', 'message' => 'Invalid action or user not found']);
    }
} else {
    // Invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
