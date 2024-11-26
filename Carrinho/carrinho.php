<?php
require __DIR__ . '/../db.php';
session_start();

if (!$conn) {
    die("Falha na conexão com o banco de dados!");
}

$sql = "SELECT 
            tb_itens.nome, 
            tb_itens.preco, 
            tb_itens.foto, 
            tb_itens_pedido.quantidade
        FROM tb_itens_pedido
        INNER JOIN tb_itens ON tb_itens_pedido.idItem = tb_itens.id
        WHERE tb_itens_pedido.idUsuario = ? AND tb_itens_pedido.finalizado = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$itensCarrinho = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itensCarrinho[] = $row;
    }
}

$totalCompra = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    $sql_delete = "DELETE FROM tb_itens_pedido WHERE idUsuario = ? AND finalizado = 0";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $_SESSION['user_id']);
    $stmt_delete->execute();
    
    header("Location: ../Metodo de Pagamento/pagamento.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Carrinho - FastPizza</title>
</head>
<body>
    <section class="cart">
        <div class="cart-header">
            <img class="carrinho-1" src="images/carrinho-1.png" alt="Ícone do carrinho" width="43">
            <h2>Carrinho</h2>
        </div>

        <?php if (empty($itensCarrinho)): ?>
            <div class="no-items-message">
                <p>Seu carrinho está vazio. Adicione itens para continuar a compra!</p>
            </div>
        <?php else: ?>
            <div class="cart-items" id="cart-items">
                <?php
                foreach ($itensCarrinho as $item) {
                    $precoTotalItem = $item['preco'] * $item['quantidade'];
                    $totalCompra += $precoTotalItem;
                    
                    $foto_base64 = base64_encode($item['foto']);
                    $foto_data_url = 'data:image/png;base64,' . $foto_base64;
                    
                    echo '<div class="cart-item" data-name="' . $item['nome'] . '" data-price="' . $item['preco'] . '">';
                    echo '<h3>' . $item['nome'] . ' - Grande</h3>';
                    echo '<div class="counter">';
                    echo '<span class="quantity">' . $item['quantidade'] . '</span>';
                    echo '</div>';
                    echo '<div class="item-price">';
                    echo '<h3>R$<span class="price">' . number_format($item['preco'], 2, ',', '.') . '</span></h3>';
                    echo '<h4>Total: R$<span class="total-item-price">' . number_format($precoTotalItem, 2, ',', '.') . '</span></h4>';
                    echo '</div>';
                    echo '<img src="' . $foto_data_url . '" alt="Imagem do item" width="50" />';
                    echo '</div>';
                    echo '<hr>';
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="cart-total">
            <h3>Total Produtos:</h3>
            <h3 id="total-produtos">R$<?php echo number_format($totalCompra, 2, ',', '.'); ?></h3>
        </div>

        <div class="calculate-frete">
            <input type="text" id="frete" placeholder="CEP" onfocus="this.placeholder = ''" onblur="this.placeholder = 'CEP'">
            <button onclick="calcularFrete()">Calcular Frete</button>
        </div>

        <div class="cart-total">
            <h3>Total compra (Frete incluso):</h3>
            <h3 class="price-total" id="total-compra">R$<?php echo number_format($totalCompra, 2, ',', '.'); ?></h3>
        </div>

        <div class="calculate-frete1">
            <form method="POST" action="">
                <button type="submit" name="finalizar_compra">Finalizar compra</button>
            </form>
        </div>
    </section>

    <script src="javascript.js"></script>
</body>
</html>
