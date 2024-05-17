function addToCart(event, url, id) {
    event.preventDefault();

    // Récupération de la quantité à partir de l'input
    let quantity = event.target.elements.quantity.value;
    // Conversion de la quantité en entier
    quantity = parseInt(quantity, 10);

    // Création de la requête AJAX
    let xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText);
            if (data.cart) {
                let totalQuantity = 0;
                for (let itemId in data.cart) {
                    totalQuantity += data.cart[itemId].quantity;
                }
                document.getElementById('cart_quantity').textContent = totalQuantity;
            }
        }
    }

    // Envoi de la requête avec la quantité et l'id
    xhr.send(JSON.stringify({ quantity: quantity, id: id }));
}