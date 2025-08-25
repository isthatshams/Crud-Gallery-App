<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid Email");
    }

    if ($confirmPassword !== $password) {
        die("passwords do not match");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT user_id FROM Users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':email' => $email
        )
    );
    if ($stmt->fetch()) {
        die('email exists');
    }

    $sql = "INSERT INTO Users (email, first_name, last_name, password_hash)
    VALUES (:email, :first_name, :last_name, :password_hash)";

    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(
            array(
                ':email' => $_POST['email'],
                ':first_name' => $_POST['firstName'],
                ':last_name' => $_POST['lastName'],
                ':password_hash' => $passwordHash,
            )
        );
        header('Location:SignIn.php');
        exit;
    } catch (Exception $e) {
        die('error:' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Sign Up</title>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-sky-500 to-emerald-500 py-40 ">
        <div class="mx-auto container">
            <div class="flex flex-col lg:flex-row w-10/12 lg:w-8/12 bg-white rounded-xl mx-auto shadow-lg overflow-hidden">
                <div class="p-12 bg-cover bg-center bg-no-repeat justify-center items-center w-full lg:w-1/2 bg-[url('./imgs/Aurora.jpg')] flex flex-col text-white">
                    <h1 class="text-3xl mb-3">
                        Welcome
                    </h1>
                    <p class="text-center lg:text-left">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean suspendisse aliquam varius rutrum purus maecenas ac <a href="#" class="text-[#06f093]"> Learn more</a>
                    </p>
                </div>
                <div class="py-16 px-12 w-full lg:w-1/2">
                    <h2 class="mb-4 text-3xl">Register
                    </h2>
                    <p class="mb-4">Create your account. It's free and only take a minute</p>
                    <form id="form" method="POST">
                        <div class=" grid grid-cols-2 gap-5">
                            <input type="text" id="firstName" name="firstName" placeholder="First Name" class="border border-gray-400 py-1 px-2 focus:outline-sky-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <input type="text" id="lastName" name="lastName" placeholder="Last Name" class="border border-gray-400 py-1 px-2 focus:outline-sky-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                        </div>
                        <div class="mt-5">
                            <input type="text" id="email" name="email" placeholder="Email" class="border border-gray-400 py-1 px-2 w-full focus:outline-sky-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <p id="emailHint" class="text-gray-500 text-sm mt-1">
                            </p>
                        </div>
                        <div class="mt-5">
                            <input type="text" id="password" name="password" placeholder="Password" class="border border-gray-400 py-1 px-2 w-full focus:outline-sky-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <p id="passwordHint" class="text-gray-500 text-sm mt-1">
                            </p>
                        </div>
                        <div class="mt-5">
                            <input type="text" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" class="border border-gray-400 py-1 px-2 w-full focus:outline-sky-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <p id="confirmPasswordHint" class="text-gray-500 text-sm mt-1">
                        </div>
                        <div class="mt-5">
                            <input type="checkbox" id="agree" name="agree" class="accent-sky-500 border border-gray-500 invalid:outline-red-500 invalid:outline invalid:focus:outline-red-500">
                            <span>
                                I accept the <a href="#" class="text-sky-500 font-semibold">Terms of Use</a> & <a href="#" class="text-sky-500 font-semibold ">Privacy and Policy</a>
                            </span>
                            <p id="checkBoxHint" class="text-gray-500 text-sm mt-1">
                        </div>
                        <div class="mt-5">
                            <button type="submit" id="submitButton" class="w-full bg-sky-500 py-3 text-center text-white">Register Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/SignUp.js"></script>

</body>

</html>