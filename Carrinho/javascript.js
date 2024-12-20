function updateQuantity(button, delta) {
    const counter = button.closest('.counter');
    const quantitySpan = counter.querySelector('.quantity');
    let quantity = parseInt(quantitySpan.textContent) + delta;

    if (quantity < 1) quantity = 1;

    quantitySpan.textContent = quantity;

    updateTotal();
}

function updateTotal() {
    const cartItems = document.querySelectorAll('.cart-item');
    let total = 0;

    cartItems.forEach(item => {
        const quantity = parseInt(item.querySelector('.quantity').textContent);
        let price;
        if (item.dataset.name === "Pizza de Quatro Queijos - Grande") {
            price = 45.90;
        } else if (item.dataset.name === "Pizza de Frango com Catupiry - Grande") {
            price = 42.90;
        } else {
            price = parseFloat(item.dataset.price);
        }
        total += quantity * price;
    });

    document.getElementById('total-produtos').textContent = 'R$' + total.toFixed(2);
    document.getElementById('total-compra').textContent = 'R$' + (total + 10).toFixed(2);
}

function calcularFrete() {
    updateTotal();
}

function FinalizarCompra() {
    alert("Compra finalizada com sucesso!");
}
