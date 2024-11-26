<?php

session_start();

require __DIR__ . '/../db.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['data_nascimento']) && !isset($_SESSION['usuario_verificado'])) {
        $email = $_POST['email'];
        $data_nascimento = $_POST['data_nascimento'];

        if ($conn) {
            $stmt = $conn->prepare("SELECT id, nome, data_nascimento FROM tb_usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ($user['data_nascimento'] == $data_nascimento) {
                    $_SESSION['usuario_verificado'] = true;
                    $_SESSION['email'] = $email;
                    $mensagem = "Informações válidas. Agora você pode redefinir sua senha.";
                } else {
                    $erro = "Data de nascimento incorreta.";
                }
            } else {
                $erro = "E-mail não encontrado.";
            }
          
        } else {
            $erro = "E-mail não encontrado.";
        }
    }

    if (isset($_POST['nova_senha']) && isset($_SESSION['usuario_verificado']) && $_SESSION['usuario_verificado'] === true) {
        $nova_senha = $_POST['nova_senha'];
        $email = $_SESSION['email'];

        if (strlen($nova_senha) >= 6) {
            $stmt = $conn->prepare("UPDATE tb_usuario SET senha = ? WHERE email = ?");
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $nova_senha_hash, $email);
            if ($stmt->execute()) {
                session_unset();
                session_destroy();
                $mensagem = "Senha atualizada com sucesso! Agora você pode <a href='../Login/login.php'>fazer login</a>.";
            } else {
                $erro = "Erro ao alterar a senha.";
            }
        } else {
            $erro = "Erro na conexão com o banco de dados.";
        }

    } else {
        $erro = "Sessão expirada. Por favor, valide o e-mail novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <style>
        body {
            background-image: url('Imagem\ de\ Fundo\ Página\ de\ Cadastro.jpg');
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="reset-header">
            <h2>Redefinir Senha</h2>
        </div>
      
        <?php if (!isset($_SESSION['usuario_verificado'])): ?>
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

        <?php if (isset($_SESSION['usuario_verificado'])): ?>
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

        <?php if (!empty($mensagem) && isset($_SESSION['email_verificado'])): ?>
            <form action="esqueci_senha.php" method="POST">
                <input type="hidden" name="etapa" value="alterar">
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha" required>
                </div>
                <div class="form-actions">
                    <button type="submit">Alterar Senha</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>