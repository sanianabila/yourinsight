<?php
require 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT id_penulis, email, password FROM penulis WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["idpenulis"] = $row['id_penulis'];
        $_SESSION["email"] = $row['email'];
        $_SESSION["loggedin"] = true;
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid email or password";
    }
}
?>
