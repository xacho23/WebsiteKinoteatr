<?php
session_start();

$host = 'localhost';
$db = 'kinoteatr';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $targetDirectory = "C:/OSPanel/domains/localhost/"; // Указываем директорию для загрузки
        $fileName = basename($_FILES["poster"]["name"]); 
        $fileName1 = basename($_FILES["screenshot1"]["name"]);
        $fileName2 = basename($_FILES["screenshot2"]["name"]);
        $fileName3= basename($_FILES["screenshot3"]["name"]);// Получаем имя файла
        $targetFile = $targetDirectory . $fileName;
        $targetFile1 = $targetDirectory . $fileName1;
        $targetFile2 = $targetDirectory . $fileName2;
        $targetFile3 = $targetDirectory . $fileName3; // Соединяем директорию и имя файла для полного пути
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $imageFileType1 = strtolower(pathinfo($targetFile1, PATHINFO_EXTENSION));
        $imageFileType2 = strtolower(pathinfo($targetFile2, PATHINFO_EXTENSION));
        $imageFileType3 = strtolower(pathinfo($targetFile3, PATHINFO_EXTENSION));

        // Проверяем, является ли файл изображением
        $check = getimagesize($_FILES["poster"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
            header('Location: profile.php');
            exit();
        }

        // Проверяем, существует ли файл уже
        if (file_exists($targetFile)) {
            $_SESSION['message'] = "Sorry, file already exists.";
            $uploadOk = 0;
            header('Location: profile.php');
            exit();
        }


        // Пытаемся загрузить файл
        if ($uploadOk == 0) {
            $_SESSION['message'] = "Sorry, your file was not uploaded.";
        } else {
            if ((move_uploaded_file($_FILES["poster"]["tmp_name"], $targetFile)) && (move_uploaded_file($_FILES["screenshot1"]["tmp_name"], $targetFile1)) && (move_uploaded_file($_FILES["screenshot2"]["tmp_name"], $targetFile2)) && (move_uploaded_file($_FILES["screenshot3"]["tmp_name"], $targetFile3)))  {
                // Все проверки пройдены, сохраняем информацию о фильме в базу данных
                $stmt = $pdo->prepare("INSERT INTO movies (poster, title, description, genre, full_desc, avtor, inroles, strana, dlitel, video_url, screenshot1, screenshot2, screenshot3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fileName, $_POST['title'], $_POST['description'], $_POST['genre'], $_POST['full_desc'], $_POST['avtor'], $_POST['inroles'], $_POST['strana'], $_POST['dlitel'] ,$_POST['video_url'], $fileName1, $fileName2, $fileName3]);
                $_SESSION['message'] = "Фильм успешно добавлен";
            } else {
                $_SESSION['message'] = "Ошибка.";
            }

            
        }
    }
} catch (\PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
} finally {
    header('Location: profile.php');
    exit();
}
?>
