<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password1'];
    $cpass = $_POST['password2'];

        $stmt1 = $db->prepare("SELECT * FROM reg WHERE email = ?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $stmt1->store_result();

        if ($stmt1->num_rows == 1) {
            echo "Email already exists";
        } else {
         //   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO reg (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
           
            if ($stmt->execute()) {
                echo "Record inserted successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            $stmt1->close();
            $db->close();
        }

}
?>
