<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование завершено</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #333;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .container {
            text-align: center;
            border: 2px solid red;
            padding: 25px;
            background-color: rgba(0,0,0,0.3);
            animation: fadeIn 2s ease-in-out;
            border-radius: 20px;
            margin: 20px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #ff0000;
            animation: slideIn 2s ease-in-out;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1em;
            color: #fff;
            background-color: #ff0000;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            animation: buttonAppear 2s ease-in-out;
        }

        .button:hover {
            background-color: #cc0000;
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes buttonAppear {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Бронирование завершено успешно</h1>
        <p>Билеты были отправлены вам по почте</p>
        <a href="index.php" class="button">На главную</a>
    </div>
</body>
</html>
