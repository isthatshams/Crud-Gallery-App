<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";
date_default_timezone_set('Asia/Amman');

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit;
}

$sql = "SELECT COUNT(*) AS total_rows FROM Images;";
$stmt = $pdo->query($sql);
$totalRows = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRows = $totalRows['total_rows'];

if (isset($_POST['log_out'])) {
    session_unset();
    session_destroy();
    header('Location: SignIn.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {

    if (!isset($_SESSION['user_id'])) die("User not logged in.");

    $target_dir = "uploads/images/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
    if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] != UPLOAD_ERR_OK) {
        die("No file uploaded or there was an upload error.");
    }

    $file = $_FILES["fileToUpload"];
    $filename = basename($file["name"]);
    $target_file = $target_dir . $filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($file["tmp_name"]);
    if ($check === false) die("File is not an image.");
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) die("File type not allowed.");

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        try {
            $sql = "INSERT INTO Images (user_id, description, image_location, image_filename, uploaded_at)
                    VALUES (:user_id, :description, :image_location, :image_filename, :uploaded_at)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':description' => $_POST['description'] ?? '',
                ':image_location' => '/' . $target_dir,
                ':image_filename' => $filename,
                ':uploaded_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        die("Error uploading file.");
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
                            <li class="mb-1 w-full rounded-lg bg-sky-300 dark:bg-sky-600">
                                <a href="UploadData.php" class="flex gap-x-3 px-3 py-2 rounded-lg hover:bg-sky-300 dark:hover:bg-sky-600">
                                    <img src="./imgs/svg/folders.svg" alt="Logo" class="size-6 invert dark:invert-0">
                                    <p class="font-bold text-white dark:text-gray-100">Folders</p>
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
                                <button type="submit" id="log_out" name="log_out" class="w-full border border-red-500 bg-red-500 dark:bg-red-700 text-white dark:text-white py-3 text-center rounded-lg hover:bg-red-400 dark:hover:bg-red-800">Log out
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <main class="pt-20 flex-1">
        <el-dialog>
            <dialog id="dialog" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
                <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                    <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 sm:mt-0 sm:ml-4 text-left">
                                    <h3 id="dialog-title" class="text-base font-semibold dark:text-white mb-2">Upload a Picture</h3>
                                    <form method="post" id="pic-form" name="pic-form" action="UploadData.php" enctype="multipart/form-data">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="col-span-2">
                                                <input type="file" name="fileToUpload" id="fileToUpload" class="hidden">
                                                <label for="fileToUpload" class="w-full block border-2 border-dashed h-[200px] text-center justify-center flex items-center border-sky-400 dark:border-sky-500 text-sm font-semibold text-gray-400 dark:text-gray-500 hover:border-sky-300 hover:dark:border-sky-600">
                                                    Upload a picture
                                                </label>
                                            </div>
                                            <div class="col-span-2">
                                                <p class="dark:text-gray-400">Description</p>
                                                <textarea name="description" id="description" placeholder="Description" class="w-full border border-gray-400 rounded-md py-1 px-2 dark:text-white focus:outline-sky-400 "></textarea>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-400">Are you sure you want to upload this account? All of the data will be permanently added.</p>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700/25 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" id="upload" name="upload" class="inline-flex w-full justify-center rounded-md bg-sky-400 dark:bg-sky-500 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-300 hover:dark:bg-sky-400 sm:ml-3 sm:w-auto">Upload</button>
                            <button type="button" command="close" commandfor="dialog" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-400 dark:bg-white/10 px-3 py-2 text-sm font-semibold text-white inset-ring inset-ring-white/5 hover:bg-gray-800/20 hover:dark:bg-white/20 sm:mt-0 sm:w-auto">Cancel</button>
                        </div>
                        </form>
                    </el-dialog-panel>
                </div>
            </dialog>
        </el-dialog>
        <div class="container mx-auto">
            <div class="flex flex-col">
                <div class="w-full bg-sky-500 py-3 px-4 rounded-t-lg flex justify-between items-center sticky top-0 mb-5">
                    <h2 class="text-lg font-bold text-white hidden md:block ">Photos</h2>
                    <button class="md:hidden">
                        <img src="./imgs/svg/menu.svg" alt="menu-icon" class="size-6 invert dark:invert-0">
                    </button>

                    <div>
                        <button command="show-modal" commandfor="dialog" class="border border-sky-500 py-2 px-6 rounded-lg text-sm bg-sky-400 dark:bg-sky-600 text-white hover:bg-sky-300 hover:dark:bg-sky-700">add Picture
                    </div>
                    <div class="flex items-center">
                        <label class="text-lg text-white cursor-pointer" for="filter">Date</label>
                        <?php
                        $order = "ASC";
                        if (isset($_GET['Date']) && $_GET['Date'] == "ASC") {
                            $order = "DESC";
                        } else {
                            $order = "ASC";
                        }
                        echo ('<a href="UploadData.php?Date=' . $order . '" id="filter" class="cursor-pointer">');
                        echo ('<img src="imgs/svg/arrow.svg" alt="arrow" class="size-6 invert ' .
                            ($order == "ASC" ? "" : "rotate-180")
                            . '">');
                        ?>
                        </a>
                    </div>
                </div>
                <div class="flex flex-wrap">
                    <?php
                    $sql = "SELECT * FROM Images ORDER BY uploaded_at $order";
                    $stmt = $pdo->query($sql);
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $perQuarter = ceil($totalRows / 4);
                    for ($i = 0; $i < 4; $i++) {
                        echo '<div class="w-full sm:w-1/2 lg:w-1/4 px-1">';
                        $start = $i * $perQuarter;
                        $end = min($start + $perQuarter, $totalRows);

                        for ($j = $start; $j < $end; $j++) {
                            echo ('<div class = "flex flex-col my-2 pb-2 bg-white 
                            dark:bg-black rounded-lg shadow-md dark:text-white">');
                            echo '<img src="http://' . $_SERVER['HTTP_HOST'] . "/CrudApp" .
                                $rows[$j]['image_location'] . $rows[$j]['image_filename'] .
                                '" class="mb-2 w-full rounded-lg shadow-md">';
                            echo ('<h3 class="px-2">' . $rows[$j]['image_filename'] .
                                '</h3>');
                            echo ('<p class="px-2">Uploaded At: ' . $rows[$j]['uploaded_at'] .
                                '</p>');
                            echo ('</div>');
                        }
                        echo ('</div>');
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>