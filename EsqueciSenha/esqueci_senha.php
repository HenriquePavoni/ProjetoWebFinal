<?php
require 'db.php';

session_start();

$mensagem = '';
$erro = '';
$nova_senha = '';
$email = '';
$data_nascimento = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['email']) && isset($_POST['data_nascimento'])) {
        $email = $_POST['email'];
        $data_nascimento = $_POST['data_nascimento'];

        if ($conn) {
            $stmt = $conn->prepare("SELECT id, nome, data_nascimento FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ($user['data_nascimento'] == $data_nascimento) {
                    $_SESSION['user_id'] = $user['id'];
                    $mensagem = "Informações válidas. Agora você pode redefinir sua senha.";
                } else {
                    $erro = "Data de nascimento incorreta.";
                }
            } else {
                $erro = "E-mail não encontrado.";
            }
        } else {
            $erro = "Falha na conexão com o banco de dados!";
        }
    } elseif (isset($_POST['nova_senha']) && isset($_SESSION['user_id'])) {
        $nova_senha = $_POST['nova_senha'];
        $user_id = $_SESSION['user_id'];

        if (strlen($nova_senha) >= 6) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET senha = ? WHERE id = ?");
            $stmt->bind_param("si", $nova_senha_hash, $user_id);
            if ($stmt->execute()) {
                $mensagem = "Senha atualizada com sucesso! Agora você pode <a href='login.php'>fazer login</a>.";
                unset($_SESSION['user_id']);
            } else {
                $erro = "Erro ao atualizar a senha. Tente novamente mais tarde.";
            }
        } else {
            $erro = "A nova senha deve ter no mínimo 6 caracteres.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-container {
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

        .reset-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .reset-header h2 {
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

        .message {
            text-align: center;
            font-size: 16px;
            color: green;
            margin-top: 10px;
        }

        .error-message {
            text-align: center;
            font-size: 16px;
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <h2>Esqueci a Senha</h2>
        </div>

        <?php if (empty($_SESSION['user_id'])): ?>
        <form action="esqueci_senha.php" method="POST">
            <div class="form-group">
                <label for="email">Digite seu e-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>
            </div>

            <div class="form-actions">
                <button type="submit">Verificar</button>
            </div>
        </form>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user_id'])): ?>
        <form action="esqueci_senha.php" method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha" required>
            </div>

            <div class="form-actions">
                <button type="submit">Redefinir Senha</button>
            </div>
        </form>
        <?php endif; ?>

        <?php if ($mensagem): ?>
            <div class="message"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="error-message"><?php echo $erro; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
