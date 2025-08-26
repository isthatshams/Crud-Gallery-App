<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if (isset($_SESSION['user_id']) && isset($_POST['last_name']) && isset($_POST['first_name'])) {
        $first_name = trim($_POST['first_name']) ?? '';
        $last_name = trim($_POST['last_name']) ?? '';
        $user_id = $_SESSION['user_id'] ?? '';
        if ($_POST['first_name'] == '' && $_POST['last_name'] == '' && $_SESSION['user_id']) {
            die('All feilds must be full');
        }
        try {
            $sql = "UPDATE Users SET first_name = :first_name, last_name = :last_name WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':user_id' => $_SESSION['user_id'],
                )
            );


            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name']  = $last_name;
        } catch (Exception $e) {
            echo ('Error: ' . $e->getMessage());
        }
    }
}


if (isset($_POST['log_out'])) {
    session_unset();
    session_destroy();
    header('Location: SignIn.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/95f824aa24.js" crossorigin="anonymous"></script>
    <title>Upload Images</title>
</head>

<body class="min-h-screen bg-slate-100 dark:bg-gray-800 flex">
    <aside class="hidden md:block w-[300px] min-h-screen">
        <div class="fixed top-0 bottom-0 w-[300px] px-6 bg-sky-500 dark:bg-sky-700 rounded-r-lg text-sky-200 dark:text-sky-200">
            <div class="py-2 items-center flex justify-between ">
                <img src="./imgs/svg/logo copy.svg" alt="Logo" class="size-10">
                <h1 class="text-3xl text-white dark:text-gray-100">Sprintive</h1>
            </div>
            <hr class="border-2 my-2 mx-auto rounded-full border-sky-300 dark:border-sky-200">

            <nav class="flex flex-col w-full">
                <ul class="flex flex-col justify-between w-full">
                    <li class="flex-1">
                        <ul class=" w-full">
                            <li class="mb-1 w-full">
                                <a href="Dashboard.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/home.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="text-black dark:text-white">Dashboard</p>
                                </a>
                            </li>
                            <li class="mb-1 w-full">
                                <a href="UploadData.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/folders.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="text-black dark:text-white">Folders</p>
                                </a>
                            </li>
                            <li class="mb-1 w-full rounded-lg bg-sky-300 dark:bg-sky-600">
                                <a href="Settings.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/users.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="font-bold text-white dark:text-gray-100">Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="size-32">
                        <div class="inset-x-0 bottom-0 p-2 absolute">
                            <form method="post">
                                <button type="submit" id="log_out" name="log_out" class="w-full border border-red-500 bg-red-500 dark:bg-red-700 text-white dark:text-white py-3 text-center rounded-lg hover:bg-red-400 dark:hover:bg-red-800">Log out</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <main class="pt-12 flex-1">
        <div class="container mx-auto">
            <h1 class="text-5xl text-black dark:text-white">Settings</h1>
            <hr class="my-5 border-gray-400 dark:border-gray-600">
            <div>
                <div class="bg-gradient-to-br from-sky-500 to-emerald-500 flex flex-row rounded-lg">
                    <div class="mt-40 w-full bg-white dark:bg-gray-800 px-8 py-12">
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex gap-4 items-center">
                                <img src="<?php echo $_SESSION['profile_picture'] ?? 'uploads/profile_pics/def_user.jpg'; ?>"
                                    alt="User Profile" class="rounded-full size-23">
                                <div class="flex flex-col justify-center gap-2">
                                    <h2 class="text-black dark:text-white">
                                        <?php echo ($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                                    </h2>
                                    <p class="hidden sm:block text-gray-400 dark:text-gray-300">
                                        <?php echo $_SESSION['email']; ?>
                                    </p>
                                </div>
                            </div>
                            <form action="Settings.php" method="POST">
                                <button type="submit" id="update" name="update" class="bg-sky-500 dark:bg-sky-500 px-8 py-3 text-white rounded-lg hover:bg-sky-400 hover:dark:bg-sky-600 cursor-pointer">Edit</button>
                        </div>
                        <div class="mt-8 grid grid-cols-2 gap-2 sm:gap-6 lg:gap-12">
                            <div>
                                <p class="text-black dark:text-gray-200">First Name</p>
                                <?php echo '<input id="first_name" name="first_name" type="text" value ="' . $_SESSION['first_name'] . '" class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">' ?>
                            </div>
                            <div>
                                <p class="text-black dark:text-gray-200">Last Name</p>
                                <?php echo '<input name="last_name" id="last_name" type="text" value ="' . $_SESSION['last_name'] . '" class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">' ?>
                            </div>
                        </div>
                        <div class="mt-8 grid grid-cols-2 gap-2 sm:gap-6 lg:gap-12">
                            <div>
                                <p class="text-black dark:text-gray-200">Email</p>
                                <?php
                                echo ('<input name="email" id="email" disabled type="text" value="' . $_SESSION['email'] . '
                                " class="disabled:cursor-not-allowed disabled:bg-gray-50 dark:disabled:bg-gray-700 disabled:text-gray-500 dark:disabled:text-gray-300 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">');
                                ?>
                            </div>
                            <div>
                                <p class="text-black dark:text-gray-200">Password</p>
                                <input type="password" name="password" id="password" disabled value="12234565" class="disabled:cursor-not-allowed disabled:bg-gray-50 dark:disabled:bg-gray-700 disabled:text-gray-500 dark:disabled:text-gray-300 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="button" class="w-full bg-red-500 dark:bg-red-700 py-6 rounded-lg text-white hover:bg-red-400 dark:hover:bg-red-800 cursor-pointer">Delete Account</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>


</html>