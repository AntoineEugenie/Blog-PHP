<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <style>
        /* Importation des polices */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 350px;
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #444;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 1rem;
            font-weight: bold;
            text-align: left;
            color: #666;
        }

        input {
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #1e7d34;
        }

        .login-link {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        a {
            color: #28a745;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <form action="addToDatabase.php" method="post">
        <label for="username">
            Username
            <input type="text" id="username" name="username">
        </label>
        <label for="email">
            Email
            <input type="email" id="email" name="email">
        </label>
        <label for="password">
            Password
            <input type="password" id="password" name="password">
        </label>
        <input type="submit" value="Register">
    </form>
    <div class="login-link">
        Already have an account? Click <a href="login.php">here</a>
    </div>
</div>
</body>
</html>
