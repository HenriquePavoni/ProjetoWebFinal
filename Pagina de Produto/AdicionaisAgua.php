<?php
require __DIR__ . '/../db.php';
session_start();

$showModal = false; // Variável para controlar a exibição do modal

if (!$conn) {
    die("Falha na conexão com o banco de dados!");
}

$sql = "SELECT nome, preco, descricao, foto FROM tb_itens WHERE nome = 'Água sem gás'";
$result = $conn->query($sql);

$item = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_nome = $item['nome'];
    $item_preco = 4.00;
    $item_descricao = $item['descricao'];
    $idUsuario = $_SESSION['user_id'];
    $idItem = 8;
    $quantidade = 1;
    $finalizado = 0;


    $sql = "INSERT INTO tb_itens_pedido (idUsuario, idItem, quantidade, preco, finalizado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('iiids', $idUsuario, $idItem, $quantidade, $item_preco, $finalizado);
    $stmt->execute();

    $showModal = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <title>Cardápio - FastPizza</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos do modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }

        .modal button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <main>
        <div class="Carrinho">
            <div class="card">

                <?php
                $foto = $item['foto'];
                $foto_base64 = base64_encode($foto);
                $foto_data_url = 'data:image/png;base64,' . $foto_base64;

                echo '<div class="rodape">';
                echo '<h1>' . $item['nome'] . '</h1>';
                echo '<h2 class="valor"> R$' . number_format($item['preco'], 2, ',', '.') . '</h2>';
                echo '</div>';
                echo '<h3>' . $item['nome'] . ' - Grande</h3>';
                echo '<div class="pizzaDetails">';
                echo '<p><b>Ingredientes:</b> ' . $item['descricao'] . '</p>';
                echo '<img class="pizza-image" src="' . $foto_data_url . '" alt="Imagem da pizza">';
                echo '</div><br>';
                echo '<hr>';
                ?>

                <form method="POST" action="">
                    <input type="hidden" name="item_nome" value="<?php echo $item['nome']; ?>">
                    <input type="hidden" name="item_preco" value="<?php echo $item['preco']; ?>">
                    <input type="hidden" name="item_descricao" value="<?php echo $item['descricao']; ?>">

                    <button class="btn" type="submit">Adicionar ao carrinho</button>
                </form>

            </div>
        </div>
    </main>

    <?php if ($showModal): ?>
        <div class="modal" id="myModal">
            <div class="modal-content">
                <p>O item foi adicionado ao seu carrinho!</p>
                <button onclick="window.location.href='../Cardapio/cardapio.php'">OK</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        <?php if ($showModal): ?>
            document.getElementById('myModal').style.display = 'flex';
        <?php endif; ?>
    </script>

</body>

</html>