<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    if ($_POST['first_name'] && $_POST['last_name'] && $_POST['email'] && $_POST['password']  && $_POST['status'] && $_POST['role']) {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $status = $_POST['status'] ?? '';
        $role = $_POST['role'] ?? '';

        if (
            $first_name == '' || $last_name == '' || $email == '' ||
            $password == '' || $status == '' || $role == ''
        ) {
            die('all fields are required');
        }
        try {
            $sql = "INSERT INTO Users (first_name,last_name,email,password_hash,status,role)
            VALUES (:first_name,:last_name,:email,:password_hash,:status,:role)";
            $stmt = $pdo->prepare($sql);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password_hash' => $password_hash,
                'status' => $status,
                'role' => $role
            ]);
        } catch (Exception $e) {
            echo ('' . $e->getMessage());
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
    <title>Dashboard</title>
</head>

<body class="min-h-screen bg-slate-100 dark:bg-gray-800 flex">
    <aside class="hidden lg:block w-[300px] min-h-screen ">
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
                            <li class="mb-1 w-full rounded-lg bg-sky-300 dark:bg-sky-600">
                                <a href="Dashboard.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/home.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="font-bold text-white dark:text-gray-100">Dashboard</p>
                                </a>
                            </li>
                            <li class="mb-1 w-full">
                                <a href="UploadData.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/folders.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="text-black dark:text-white">Folders</p>
                                </a>
                            </li>
                            <li class="mb-1 w-full">
                                <a href="Settings.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-400 dark:hover:bg-sky-500">
                                    <img src="./imgs/svg/users.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="text-black dark:text-white">Settings</p>
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
    <main class="py-40 flex-1 py-10">
        <div class="container mx-auto ">
            <div class="mb-5 flex justify-between">
                <div class="flex gap-5">
                    <div class="flex gap-2 items-center">
                        <p class="text-black dark:text-white">Search:</p>
                        <input type="text" placeholder="Search" class="py-1 px-2 dark:text-white border-gray-400 dark:border-gray-400 border-2 rounded-lg focus:outline-sky-500">
                    </div>
                    <div class="flex gap-2 items-center hidden md:flex">
                        <p class="text-black dark:text-white">Status:</p>
                        <select name="statusDrop" id="statusDrop" class="py-1.5 px-3 dark:text-white border border-gray-400 border-2 rounded-lg">
                            <option value="All" selected class="dark:bg-gray-800">All</option>
                            <option value="Active" class="dark:bg-gray-800">Active</option>
                            <option value="Banned" class="dark:bg-gray-800">Banned</option>
                            <option value="Suspended" class="dark:bg-gray-800">Suspended</option>
                        </select>
                    </div>
                </div><el-dialog>
                    <dialog id="dialog" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                        <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
                        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                            <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 sm:mt-0 sm:ml-4 text-left">
                                            <h3 id="dialog-title" class="text-base font-semibold dark:text-white">Add an account</h3>
                                            <form method="post" id="form" name="form" action="Dashboard.php">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="dark:text-gray-400">First Name</p>
                                                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                    </div>
                                                    <div>
                                                        <p class="dark:text-gray-400">Last Name</p>
                                                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <p class="dark:text-gray-400">Email</p>
                                                        <input type="text" name="email" id="email" placeholder="Email" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                        <p id="email_hint" class="text-gray-500 text-sm mt-1">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <p class="dark:text-gray-400">Password</p>
                                                        <input type="text" name="password" id="password" placeholder="Password" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                        <p id="password_hint" class="text-gray-500 text-sm mt-1">
                                                    </div>
                                                    <div class="col-span-2 flex gap-4">
                                                        <div class="flex-1">
                                                            <p class="dark:text-gray-400">Status</p>
                                                            <select name="status" id="status" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                                <option value="" selected disabled hidden class="dark:bg-gray-800">choose</option>
                                                                <option value="Active" class="dark:bg-gray-800">Active</option>
                                                                <option value="Suspended" class="dark:bg-gray-800">Suspended</option>
                                                                <option value="Banned" class="dark:bg-gray-800 ">Banned</option>
                                                            </select>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="dark:text-gray-400">Role</p>
                                                            <select name="role" id="role" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400">
                                                                <option value="" selected disabled hidden class="dark:bg-gray-800">choose</option>
                                                                <option value="Admin" class="dark:bg-gray-800">Admin</option>
                                                                <option value="User" class="dark:bg-gray-800">User</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-400">Are you sure you want to create this account? All of the data will be permanently added.</p>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-200 dark:bg-gray-700/25 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit" id="add" name="add" class="inline-flex w-full justify-center rounded-md bg-sky-400 dark:bg-sky-500 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-300 hover:dark:bg-sky-400 sm:ml-3 sm:w-auto">Create</button>
                                    <button type="button" command="close" commandfor="dialog" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-400 dark:bg-white/10 px-3 py-2 text-sm font-semibold text-white inset-ring inset-ring-white/5 hover:bg-gray-800/20 hover:dark:bg-white/20 sm:mt-0 sm:w-auto">Cancel</button>
                                </div>
                                </form>
                            </el-dialog-panel>
                        </div>
                    </dialog>
                </el-dialog>
                <button command="show-modal" commandfor="dialog" class="border border-sky-500 py-2 px-6 rounded-lg text-sm bg-sky-500 text-white hover:bg-sky-400 hover:dark:bg-sky-600">add account
                </button>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-white uppercase">
                        <tr>
                            <th scope="col" class="py-3 px-6">Id</th>
                            <th scope="col" class="py-3 px-6">Full Name</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                            <th scope="col" class="py-3 px-6 hidden min-[1000px]:table-cell">Hash Password</th>
                            <th scope="col" class="py-3 px-6 hidden min-[1120px]:table-cell">Status</th>
                            <th scope="col" class="py-3 px-6">Role</th>
                            <th scope="col" class="py-3 px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT user_id, first_name, last_name, email, password_hash, status, role FROM Users";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo ('    <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 hover:bg-gray-50 hover:dark:bg-gray-700">');
                            echo ('<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">' . $row['user_id'] . '</th>');
                            echo ('   <td class="px-6 py-4 dark:text-gray-400">' . $row['email'] . '</td>');
                            echo ('    <td class="px-6 py-4 dark:text-gray-400">' . $row['first_name'] . ' ' . $row['first_name'] . '</td>');
                            echo ('   <td class="px-6 py-4 dark:text-gray-400 hidden min-[1000px]:table-cell">' . $row['password_hash'] . '</td>');
                            echo ('<td class="px-3 py-4 hidden min-[1120px]:table-cell">');
                            echo ('   <span class="px-3 py-1 text-sm font-medium rounded-full ' .  (strtolower($row['status']) === 'active' ? 'bg-green-100 text-green-700' : (strtolower($row['status']) === 'banned' ? 'bg-red-100 text-red-700' :
                                'bg-yellow-100 text-yellow-700')) . '">' . $row['status'] . '</span>');
                            echo (' </td>');
                            echo ('<td class="px-6 py-4 dark:text-gray-400">' . $row['role'] . '</td>');
                            echo ('<td class="px-6 py-4 text-right">');
                            echo ('<a href="#" class="font-medium text-sky-500 dark:text-blue-500 hover:underline">Edit</a>');
                            echo ('</td>');
                            echo ('</tr>');
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="js/addUser.js"></script>
</body>

</html>