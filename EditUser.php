    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: SignIn.php");
        exit;
    }
    if (!isset($_GET['user_id'])) {
        $_SESSION['error'] = "Missing user ID";
        header("Location: Dashboard.php");
        exit;
    }
    $user_id = $_GET['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        $_SESSION['error'] = "User not found";
        header("Location: Dashboard.php");
        exit;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {

        if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['status']) && isset($_POST['role'])) {

            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $status = $_POST['status'] ?? '';
            $role = $_POST['role'] ?? '';

            if (
                $first_name == '' || $last_name == '' || $email == '' ||
                $status == '' || $role == ''
            ) {
                $_SESSION['error'] = "User not found";
                header("Location: Dashboard.php");
                exit;
            }

            try {
                $stmt = $pdo->prepare("UPDATE Users SET first_name = :first_name, last_name = :last_name, email = :email, status = :status, role = :role WHERE user_id = :user_id");
                $stmt->execute([
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email' => $_POST['email'],
                    'status' => $_POST['status'],
                    'role' => $_POST['role'],
                    'user_id' => $user_id
                ]);
                $_SESSION['success'] = "User updated successfully";
                header("Location: EditUser.php?user_id=" . urlencode($user_id));
                exit;
            } catch (Exception $e) {
                die("Error updating user: " . $e->getMessage());
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://kit.fontawesome.com/95f824aa24.js" crossorigin="anonymous"></script>
        <title>Edit User</title>
    </head>

    <body class="min-h-screen bg-slate-100 dark:bg-gray-800 flex">
        <div class=" mx-auto w-full">
            <div class="flex flex-col">
                <div class="w-full bg-sky-500 py-3 px-4 rounded-t-lg flex justify-between items-center top-0 mb-5">
                    <h2 class="text-lg font-bold text-white">Edit</h2>
                    <div>
                        <a href="./Dashboard.php"><img src="./imgs/svg/xmark.svg" alt="x-mark" class="size-10 dark:invert"></a>
                    </div>
                </div>
                <div class="container mx-auto">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="bg-green-500 text-white px-4 py-3 rounded mb-4">
                            <?php echo $_SESSION['success']; ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-500 text-white px-4 py-3 rounded mb-4">
                            <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div>
                        <div class="mt-16 w-full bg-white dark:bg-gray-800 px-8 py-12">
                            <div class="flex flex-row justify-between items-center">
                                <div class="flex gap-4 items-center">
                                    <img src="<?php echo $user['profile_picture'] ?? 'uploads/profile_pics/def_user.jpg'; ?>"
                                        alt="User Profile" class="rounded-full size-23">
                                    <div class="flex flex-col justify-center gap-2">
                                        <h2 class="text-black dark:text-white text-2xl font-bold">
                                            <?php echo ($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </h2>
                                        <p class="hidden sm:block text-gray-400 dark:text-gray-300 text-md">
                                            <?php echo $user['email']; ?>
                                        </p>
                                    </div>
                                </div>
                                <form action="EditUser.php?user_id=<?php echo $_GET['user_id']; ?>" method="POST">
                            </div>
                            <div class="mt-8 grid grid-cols-2 gap-2 sm:gap-6 lg:gap-12">
                                <div>
                                    <p class="text-black dark:text-gray-200">First Name</p>
                                    <?php echo '<input id="first_name" name="first_name" type="text" value ="' . $user['first_name'] . '" class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">' ?>
                                </div>
                                <div>
                                    <p class="text-black dark:text-gray-200">Last Name</p>
                                    <?php echo '<input name="last_name" id="last_name" type="text" value ="' . $user['last_name'] . '" class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">' ?>
                                </div>
                            </div>
                            <div class="mt-8 grid grid-cols-2 gap-2 sm:gap-6 lg:gap-12">
                                <div>
                                    <p class="text-black dark:text-gray-200">Email</p>
                                    <?php
                                    echo ('<input name="email" id="email" type="text" value="' . $user['email'] . '
                                    " class="disabled:cursor-not-allowed disabled:bg-gray-50 dark:disabled:bg-gray-700 disabled:text-gray-500 dark:disabled:text-gray-300 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">');
                                    ?>
                                </div>
                                <div>
                                    <p class="text-black dark:text-gray-200">Password</p>
                                    <input type="password" name="password" id="password" disabled value="12234565" class="disabled:cursor-not-allowed disabled:bg-gray-50 dark:disabled:bg-gray-700 disabled:text-gray-500 dark:disabled:text-gray-300 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">
                                </div>

                            </div>
                            <div class="mt-8 grid grid-cols-2 gap-2 sm:gap-6 lg:gap-12">
                                <div>
                                    <p class="text-black dark:text-gray-200">Status</p>
                                    <select name="status" id="stra"
                                        class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">
                                        <option value="Active" class="dark:bg-gray-800"
                                            <?php
                                            if ($user['status'] === 'Active') echo 'selected';
                                            ?>>Active</option>
                                        <option value="Banned" class="dark:bg-gray-800"
                                            <?php
                                            if ($user['status'] === 'Banned') echo 'selected';
                                            ?>>Banned</option>
                                        <option value="Suspended" class="dark:bg-gray-800"
                                            <?php
                                            if ($user['status'] === 'Suspended') echo 'selected';
                                            ?>>Suspended</option>
                                    </select>
                                </div>
                                <div>
                                    <p class="text-black dark:text-gray-200">Role</p>
                                    <select name="role" id="role"
                                        class="focus:outline focus:outline-sky-500 bg-gray-100 dark:bg-gray-700 my-3 py-4 px-2 rounded-lg w-full text-black dark:text-white">
                                        <option value="Admin" class="dark:bg-gray-800"
                                            <?php if ($user['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                                        <option value="User" class="dark:bg-gray-800"
                                            <?php if ($user['role'] === 'User') echo 'selected'; ?>>User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-8">
                                <button type="submit" name="edit" id="edit" class="w-full bg-sky-500 dark:bg-sky-500 py-6 rounded-lg text-white hover:bg-sky-400 hover:dark:bg-sky-600 cursor-pointer">Confirm Edit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>