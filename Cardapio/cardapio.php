<?php
require __DIR__ . '/../db.php';
session_start();

if (!$conn) {
  die("Falha na conexão com o banco de dados!");
}

$sql = "SELECT nome, preco, descricao FROM tb_itens";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $itens = $result->fetch_all(MYSQLI_ASSOC);
} else {
  echo "Nenhum item encontrado.";
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
  <link rel="stylesheet" href="cardapio.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <title>Cardápio - PizzaRibeirão</title>
</head>

<body>

  <!-- NavBar -->

  <div>
    <nav class="navbar navbar-expand-lg" style="background-color: #ff7018;">
      <div class="container-fluid text-white">
        <a class="navbar-brand text-white" href="../Home/home.html">FastPizza</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link text-gray" aria-current="page" href="../Cardapio/cardapio.html">Cardapio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" aria-current="page" href="../Carrinho/carrinho.html">Carrinho</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" aria-current="page" href="../Metodo de Pagamento/pagamento.html">Metodo de
                Pagamento</a>
            </li>
          </ul>
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link text-white" aria-current="page" href="../Login/Login.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <main>
    <div class="menu">
      <h1>Cardápio</h1>
    </div>

    <div class="bttns">
      <a href="#"><button class="bttnsStyle">PIZZA TRADICIONAL</button></a>
      <a href="#"><button class="bttnsStyle">PIZZA DOCE</button></a>
      <a href="#"><button class="bttnsStyle">BEBIDAS</button></a>
    </div>

    <section class="pizzasSection">
      <div>
        <h2>Pizza Tradicional:</h2>
      </div>

      <!--Começo de uma pizza-->
      <?php
      $sql = "SELECT nome, preco, descricao FROM tb_itens";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $itens = $result->fetch_all(MYSQLI_ASSOC);
        echo '<hr>';
        echo '<div class="pizzaTitle">';
        echo '<h3>' . $itens[0]['nome'] . ' - Grande</h3>';  
        echo '<h3 class="priceStyle">R$' . $itens[0]['preco'] . '</h3>';  
        echo '</div>';
        echo '<div class="pizzaDetails">';
        echo '<p><b>Ingredientes:</b> ' . $itens[0]['descricao'] . '</p>';  
        echo '<img class="pizzaImg" src="assets/pizza-1.png" alt="">'; 
        echo '<a href="../Pagina de Produto/AdicionaisQueijo.html"><img width="38px" src="assets/add-button.png" alt=""></a>';
        echo '</div><br>';
        echo '<hr>';
        
        echo '<div class="pizzaTitle">';
        echo '<h3>' . $itens[1]['nome'] . ' - Grande</h3>'; 
        echo '<h3 class="priceStyle">R$' . $itens[1]['preco'] . '</h3>'; 
        echo '</div>';
        echo '<div class="pizzaDetails">';
        echo '<p><b>Ingredientes:</b> ' . $itens[1]['descricao'] . '</p>';  
        echo '<img class="pizzaImg" src="assets/pizza-2.png" alt="">';  
        echo '<a href="../Pagina de Produto/AdicionaisFrangoCatupiry.html"><img width="38px" src="assets/add-button.png" alt=""></a>';
        echo '</div><br>';
        echo '<hr>';

        echo '<div class="pizzaTitle">';
        echo '<h3>' . $itens[2]['nome'] . ' - Grande</h3>';
        echo '<h3 class="priceStyle">R$' . $itens[2]['preco'] . '</h3>';
        echo '</div>';
        echo '<div class="pizzaDetails">';
        echo '<p><b>Ingredientes:</b> ' . $itens[2]['descricao'] . '</p>';
        echo '<img class="pizzaImg" src="assets/pizza-3.png" alt="">';
        echo '<a href="../Pagina de Produto/AdicionaisMarguerita.html"><img width="38px" src="assets/add-button.png" alt=""></a>';
        echo '</div><br>';
        echo '<hr>';
      } else {
        echo "Nenhum item encontrado.";
      }
      
      ?>

    </section>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>