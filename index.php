<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
        }
        .header {
            background: rgba(255,255,255,0.15);
            color: blue;
            padding: 24px 0 16px 0;
            text-align: center;
            font-size: 2rem;
            letter-spacing: 2px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .login-container {
            background: #fff;
            max-width: 350px;
            margin: 60px auto 0 auto;
            padding: 32px 28px 24px 28px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-container h2 {
            margin-bottom: 18px;
            color: #2575fc;
            letter-spacing: 1px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #cfd8dc;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafd;
            transition: border 0.2s;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border: 1.5px solid #2575fc;
            outline: none;
        }
        .login-container button {
            width: 100%;
            padding: 10px 0;
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-container button:hover {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
        }
        .error {
            color: #e53935;
            margin-top: 10px;
            font-size: 0.98rem;
            min-height: 22px;
        }
        @media (max-width: 500px) {
            .login-container {
                max-width: 95vw;
                padding: 18px 8vw 18px 8vw;
            }
            .header {
                font-size: 1.3rem;
                padding: 18px 0 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <span>Task Manager</span>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="post" autocomplete="off">
            <input type="text" name="loginName" id="loginName" placeholder="Name" required>
            <input type="password" name="loginPassword" id="loginPassword" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Name or password incorrect.</div>
        <?php endif; ?>
        <?php if (isset($_GET['login_needed'])): ?>
            <div class="error">Login needed to access the dashboard.</div>
        <?php endif; ?>
    </div>
</body>
</html>