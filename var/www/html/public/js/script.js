document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.cart-button').forEach(button => {
        button.addEventListener('click', () => {
            const product = button.closest('li');
            const productId = product.dataset.id;
            const productName = product.querySelector('h3').textContent;
            const productPrice = product.querySelector('.price').textContent;

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push({ id: productId, name: productName, price: productPrice });
            localStorage.setItem('cart', JSON.stringify(cart));

            showPopup('通知', `${productName}がカートに追加されました。`);
            renderCartItems(); // カートの再描画
        });
    });

    renderCartItems();

    document.getElementById('purchase-button')?.addEventListener('click', () => {
        const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        fetch('../../Model/purchase.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cartItems)
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup('成功', '購入が完了しました。');
                localStorage.removeItem('cart');
                setTimeout(() => {
                    location.reload();
                }, 2000); // 2秒後にリロード
            } else {
                showPopup('エラー', `購入に失敗しました: ${data.message}`);
            }
        }).catch(error => {
            console.error('Error:', error);
            showPopup('エラー', '購入中にエラーが発生しました。');
        });
    });
});

function renderCartItems() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartList = document.getElementById('cart-items');

    cartList.innerHTML = ''; // テーブルをクリア

    if (cartItems.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 3;
        td.textContent = 'カートに商品がありません。';
        tr.appendChild(td);
        cartList.appendChild(tr);
    } else {
        cartItems.forEach((item, index) => {
            const tr = document.createElement('tr');
            const nameTd = document.createElement('td');
            const priceTd = document.createElement('td');
            const actionTd = document.createElement('td');
            const deleteButton = document.createElement('button');

            nameTd.textContent = item.name;
            priceTd.textContent = item.price;
            deleteButton.textContent = '削除';
            deleteButton.addEventListener('click', () => {
                removeCartItem(index);
            });

            actionTd.appendChild(deleteButton);
            tr.appendChild(nameTd);
            tr.appendChild(priceTd);
            tr.appendChild(actionTd);
            cartList.appendChild(tr);
        });
    }
}

function removeCartItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCartItems();
    showPopup('通知', '商品がカートから削除されました。');
}

function showPopup(header, message) {
    document.getElementById('popup-header').textContent = header;
    document.getElementById('popup-body').textContent = message;
    document.getElementById('popup-overlay').classList.add('show');
    document.getElementById('popup').classList.add('show');

    document.getElementById('popup-close-button').addEventListener('click', () => {
        document.getElementById('popup-overlay').classList.remove('show');
        document.getElementById('popup').classList.remove('show');
    });
}
