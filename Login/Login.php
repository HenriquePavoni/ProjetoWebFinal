<?php
require __DIR__ . '/../db.php';

session_start();

$login_erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM tb_usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];

            header("Location: ../Home/home.html");
            exit;
        } else {
            $login_erro = "E-mail ou senha inválidos!";
        }
    } else {
        $login_erro = "Falha na conexão com o banco de dados!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurante</title>

    <style>
        body {
            background-image: url('Imagem\ de\ Fundo\ Página\ de\ Cadastro.jpg');
            background-size: 100%;
            font-family: "Lexend", sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header img {
            max-width: 80px;
        }

        .login-header h2 {
            font-size: 24px;
            color: #333;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group label {
            width: 90%;
            text-align: left;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 90%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007BFF;
        }

        .form-actions {
            text-align: center;
            margin-top: 10px;
        }

        .form-actions button {
            background: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .form-actions button:hover {
            background: #0056b3;
        }

        .form-footer {
            text-align: center;
            margin-top: 15px;
        }

        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Bem-vindo!</h2>
        </div>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <?php if ($login_erro): ?>
                <div class="error-message"><?php echo $login_erro; ?></div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit">Entrar</button>
            </div>
        </form>

        <div class="form-footer">
            <p>Não tem uma conta? <a href="../Cadastro/Cadastro.php">Cadastre-se</a></p>
            <p><a href="esqueci_senha.php">Esqueceu a senha?</a></p>
        </div>
    </div>
</body>
</html>
