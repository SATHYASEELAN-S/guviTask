<?php
include('config.php');
require '../redis/vendor/predis/predis/autoload.php';

function validateCredentials($email, $password)
{
    global $db; // Assuming $db is the PDO instance

    $query = "SELECT * FROM reg WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Compare the typed password with the stored hash
        if ($password===$user['password']) {
            $redis = new Predis\Client();

            // Store email and hashed password in Redis
            $redis->set("logged-mail", $user['email']);
            $redis->set("logged-pass", $user['password']); 
            return true;
        }
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_POST['email'];
    $userPwd = $_POST['password'];

    $validCredentials = validateCredentials($userEmail, $userPwd);

    if ($validCredentials) {
        $res = [
            'status' => 200,
            'message' => "Login successful",
            'email' => $userEmail,
            'password' => $userPwd
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 400,
            'message' => "Incorrect email or password"
        ];
        echo json_encode($res);
        return;
    }
} else {
    $res = [
        'status' => 400,
        'message' => 'Bad Request'
    ];
    echo json_encode($res);
    return;
}
?>
