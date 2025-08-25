<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid Email");
    }

    $sql = "SELECT * FROM Users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':email' => $email
        )
    );

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && isset($user['password_hash'])) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['role'] = $user['role'];
            header("Location:Dashboard.php");
            exit;
        }
        die("Hello");
    } else {
        die("Invalid Email or Password");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Sign In</title>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-sky-500 to-emerald-500 py-40 ">
        <div class="mx-auto container">
            <div class="flex flex-col  w-10/12 lg:w-4/12 bg-white rounded-xl mx-auto shadow-lg overflow-hidden">
                <div class="p-12 bg-cover bg-center bg-no-repeat justify-center items-center w-full  bg-[url('./imgs/Aurora.jpg')] flex flex-col text-white">
                    <h1 class="text-3xl mb-3">
                        Welcome
                    </h1>
                    <p class="text-center">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean suspendisse aliquam varius rutrum purus maecenas ac <a href="#" class="text-[#06f093]"> Learn more</a>
                    </p>
                </div>
                <div class="py-16 px-12 w-full ">
                    <h2 class="mb-4 text-3xl">Log In
                    </h2>
                    <p class="mb-4">Log in to your account. It's quick and free</p>
                    <form method="post" id="form">
                        <div class="">
                            <input type="text" id="email" name="email" placeholder="Email" class="border border-gray-400 py-1 px-2 w-full invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <p id="emailHint" class="text-gray-500 text-sm mt-1">
                            </p>
                        </div>
                        <div class="mt-5">
                            <input type="text" id="password" name="password" placeholder="Password" class="border border-gray-400 py-1 px-2 w-full invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <p id="passwordHint" class="text-gray-500 text-sm mt-1">
                            </p>
                        </div>

                        <div class="mt-2 ">
                            <input type="checkbox" id="check" class="accent-sky-500 border border-gray-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <span>
                                Remember me</span>
                        </div>
                        <p id="checkHint" class="text-gray-500 text-sm mt-1">
                        </p>
                        <div class="mt-5">
                            <button type="submit" class="w-full bg-sky-500 py-3 text-center text-white">Register Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/SignIn.js"></script>
</body>

</html>