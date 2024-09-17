<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rize Coffee</title>
    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
</head>

<body>
    <div class="container">
        <div class="navbar">
            <div class="nav-items">
                <a href="/cashier/cashier" class="nav-item">Cashier</a>
                <a href="/cashier/transactions" class="nav-item">Transactions</a>

            </div>

            <div class="profile">
                <div class="username" onclick="window.location.href='{{ route('profile.edit') }}'">
                    {{auth()->user()->name}}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        <img src="/logout.png">
                    </div>
                </form>
            </div>
        </div>


        <div class="main">
            <div class="main-left">
                <div class="toolbar">
                    <div class="search-input">
                        <!-- <input type="text" placeholder="Search here...."> -->
                        <input type="text" id="searchInput" placeholder="Search here...." oninput="searchMenu()">
                    </div>
                    <div class="filter-icon">
                        ||
                    </div>
                    <div class="filter-action">
                        <div class="drop-menu">
                            <select id="categoryFilter" class="theme-orange">
                                <option value="all">All</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Makanan">Makanan</option>
                            </select>
                        </div>
                        <div class="drop-menu">
                            <select id="typeFilter" class="theme-orange">
                                <option value="all">All</option>
                                <option value="Favorit">Favorit</option>
                                <option value="Underrated">Underrated</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="wrapper-card">
                    @foreach ($menu as $product)
                        <div class="card-container" id="card{{$product['id']}}" data-category="{{ $product['category'] }}"
                            data-type="{{ $product['total_sold'] >= 10 ? 'Favorit' : 'Underrated' }}"
                            onclick="addToOrder('{{ $product['id'] }}', '{{ $product['name'] }}', '{{ $product['price'] }}')">
                            <div class="card-info">
                                <div class="card-title">
                                    {{ $product['name'] }}
                                </div>
                                <div class="card-category">
                                    {{ $product['category'] }}
                                </div>
                            </div>
                            <div class="card-price">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </div>
                            <div class="card-stock">
                                <div class="sold">
                                    Terjual {{ $product['total_sold'] }}
                                </div>
                                <div class="stock">
                                    Stock {{ $product['stock'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- mainrigthgcc -->
            <div class="main-right">
                <hr>

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf
                    <div class="order-list-container" id="orderListContainer">
                        <!-- card -->
                    </div>
                    <hr>
                    <div class="ordered-info">
                        <div class="name-ordered">
                            <!-- <input type="text" name="customer" placeholder="Input Customer" required> -->
                            <input type="text" name="customer" placeholder="Input Customer" pattern="[A-Za-z\s]+"
                                title="Only letters and spaces are allowed" required>
                        </div>
                        <div class="table-ordered">
                            <div class="title">Table in - </div>
                            <select name="table" class="select-table" required>
                                @foreach($mejas as $meja)
                                    <option value="{{ $meja->id }}">{{ $meja->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="order-info">
                            <div class="date-order" id="dateOrder"></div>
                            <div class="invoice-order" id="invoiceNumber">
                                WIB
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="total" id="totalPriceInput" value="0">
                    <input type="hidden" name="qty" id="qtyInput" value="0">
                    <input type="hidden" name="orderList" id="orderListInput" value="[]">

                    <div class="total-transactions">
                        <div class="total"> Total</div>
                        <div class="total-price" id="totalPrice">Rp0</div>
                    </div>
                    <div class="pay">
                        <div class="pay-title">Pay</div>
                        <!-- <input type="text" name="pay" id="payInput" required> -->
                        <input type="number" name="pay" id="payInput" step="1" min="0" required>

                    </div>
                    <div class="return">
                        <div class="return-title">Return</div>
                        <div class="return-amount-display" id="returnAmountDisplay">Rp0</div>
                        <input type="hidden" name="return_amount" id="returnAmountInput" value="0">
                    </div>
                    <div class="pay-action">
                        <button type="submit" class="pay-button">Proses</button>
                        @if ($errors->any())
                            <script>
                                alert("{{ $errors->first() }}");
                            </script>
                        @endif
                        @if (session('success'))
                            <script>
                                alert("{{ session('success') }}");
                            </script>
                        @endif
                        <button type="button" class="cancel-button"
                            onclick="window.location.href='cashier'">Batal</button>
                    </div>
                </form>
            </div>
            <!-- main -->

        </div>
    </div>
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            updateProsesButton();
            function updateDateTime() {
                const dateOrderElement = document.getElementById("dateOrder");
                const now = new Date();
                const formattedDateTime = now.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric'
                });
                dateOrderElement.textContent = formattedDateTime;
            }

            updateDateTime();
            setInterval(updateDateTime, 1000); // Update every second
        });



        let orderList = [];
        let totalPrice = 0;

        function updateOrderListInput() {
            document.getElementById("orderListInput").value = JSON.stringify(orderList);
        }
        function updateProsesButton() {
            const processButton = document.querySelector(".pay-button");
            if (orderList.length === 0) {
                processButton.disabled = true;
                processButton.style.backgroundColor = "gray";
            } else {
                processButton.disabled = false;
                processButton.style.backgroundColor = "";
            }
        }


        function addToOrder(id, name, price) {
            let existingItem = orderList.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
                document.getElementById(`qty${id}`).innerText = `Jumlah ${existingItem.quantity}`;
            } else {
                orderList.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                });
                let orderListContainer = document.getElementById("orderListContainer");
                let cardOrder = document.createElement("div");
                cardOrder.setAttribute("data-id", id);

                cardOrder.classList.add("card-order");
                cardOrder.innerHTML = `
        <div class="info-menu">
             <div class="name">${name}</div>
             <div class="price">Rp${parseInt(price).toLocaleString('id-ID')}</div>
        </div>
        <div class="qty-order">
             <div id="qty${id}" class="qty">Jumlah 1</div>
             <button type="button" class="button-min" onclick="decreaseQty('${id}')">-</button>
             <button type="button" class="button-plus" onclick="increaseQty('${id}')">+</button>
        </div>`;

                orderListContainer.appendChild(cardOrder);
            }
            totalPrice += parseInt(price.replace(/\D/g, ''));
            document.getElementById("totalPrice").innerText = `Rp${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById("totalPriceInput").value = totalPrice;
            document.getElementById("qtyInput").value = orderList.reduce((sum, item) => sum + item.quantity, 0);
            updateOrderListInput();

            // Aktifkan tombol proses pembayaran jika orderList tidak kosong
            let processButton = document.querySelector(".pay-button");
            if (orderList.length > 0) {
                processButton.disabled = false;
                processButton.style.backgroundColor = "";
                updateProsesButton();
            }
        }



        function increaseQty(id) {
            let selectedItem = orderList.find(item => item.id === id);
            if (selectedItem) {
                selectedItem.quantity++;
                document.getElementById(`qty${id}`).innerText = `Jumlah ${selectedItem.quantity}`;
                totalPrice += parseInt(selectedItem.price.replace(/\D/g, ''));
                document.getElementById("totalPrice").innerText = `Rp${totalPrice.toLocaleString('id-ID')}`;
                document.getElementById("totalPriceInput").value = totalPrice;
                document.getElementById("qtyInput").value = orderList.reduce((sum, item) => sum + item.quantity, 0);
                updateOrderListInput();
                updateProsesButton();
            }
        }

        function decreaseQty(id) {
            let selectedItemIndex = orderList.findIndex(item => item.id === id);
            if (selectedItemIndex !== -1) {
                let selectedItem = orderList[selectedItemIndex];
                totalPrice -= parseInt(selectedItem.price.replace(/\D/g, ''));
                if (selectedItem.quantity > 1) {
                    selectedItem.quantity--;
                    document.getElementById(`qty${id}`).innerText = `Jumlah ${selectedItem.quantity}`;
                } else {
                    orderList.splice(selectedItemIndex, 1);
                    let cardOrder = document.querySelector(`.card-order[data-id="${id}"]`);
                    cardOrder.remove();  // Menghapus elemen dari card-order
                }
                document.getElementById("totalPrice").innerText = `Rp${totalPrice.toLocaleString('id-ID')}`;
                document.getElementById("totalPriceInput").value = totalPrice;
                document.getElementById("qtyInput").value = orderList.reduce((sum, item) => sum + item.quantity, 0);
                updateProsesButton();
                updateOrderListInput();
            }
        }
        document.getElementById("payInput").addEventListener("input", calculateReturn);


        function calculateReturn() {
            let totalAmount = parseInt(document.getElementById("totalPrice").innerText.replace(/\D/g, ''));
            let payAmount = parseInt(document.getElementById("payInput").value.replace(/\D/g, ''));
            let returnAmount = payAmount - totalAmount;

            // Format nilai returnAmount
            let formattedReturnAmount = returnAmount.toLocaleString('id-ID');

            document.getElementById("returnAmountDisplay").innerText = `Rp${formattedReturnAmount}`;
            document.getElementById("returnAmountInput").value = returnAmount;

            let processButton = document.querySelector(".pay-button");

            // Cek apakah orderList kosong
            if (orderList.length === 0) {
                processButton.disabled = true;
                processButton.style.backgroundColor = "gray";
            } else {
                if (returnAmount < 0) {
                    processButton.disabled = true;
                    processButton.style.backgroundColor = "red";
                } else {
                    processButton.disabled = false;
                    processButton.style.backgroundColor = "";
                }
            }
        }


        // filter
        document.getElementById('categoryFilter').addEventListener('change', filterMenu);
        document.getElementById('typeFilter').addEventListener('change', filterMenu);

        function filterMenu() {
            const kategori = document.getElementById('categoryFilter').value;
            const tipe = document.getElementById('typeFilter').value;
            const menuItems = document.querySelectorAll('.card-container');

            menuItems.forEach(item => {
                const itemKategori = item.getAttribute('data-category');
                const itemTipe = item.getAttribute('data-type');

                let cocokKategori = (kategori === 'all' || kategori === itemKategori);
                let cocokTipe = (tipe === 'all' || tipe === itemTipe);

                if (cocokKategori && cocokTipe) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        // Cek apakah orderList kosong

        let processButton = document.querySelector(".pay-button");
        if (orderList.length === 0) {
            processButton.disabled = true;
            processButton.style.backgroundColor = "gray";
        } else {
            if (returnAmount < 0) {
                processButton.disabled = true;
                processButton.style.backgroundColor = "red";
            } else {
                processButton.disabled = false;
                processButton.style.backgroundColor = "";
            }
        }

        function searchMenu() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const menuItems = document.querySelectorAll('.card-container');

            menuItems.forEach(item => {
                const itemName = item.querySelector('.card-title').textContent.toLowerCase();
                if (itemName.includes(searchInput)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
<style>
    hr {
        width: 100%;
        color: #282828;
    }

    body {
        background-color: #000000;
        padding: 0px;
        margin: 0px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .container {
        width: 100%;
        background-color: #18181B;
        position: fixed;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        width: 100%;
        height: 35px;
        align-items: center;
    }

    .nav-items {
        display: flex;
        gap: 1.7rem;
        margin-left: 20px;
    }

    .nav-item {
        text-decoration: none;
        color: #fff;
        font-size: 16px;
    }

    .profile {
        display: flex;
        margin-right: 20px;
        gap: 20px;
        justify-content: center;
        align-items: center;
    }

    .profile,
    username {
        color: #fff;
        font-size: 16px;
    }

    .logout,
    img {
        /* background-image: url('/logout.png'); */
        /* background-size: cover; */
        width: 30px;
    }

    .main {
        display: flex;
        width: 100vw;
        height: 100vh;
        background-color: #000000;
    }

    .main-left {
        display: flex;
        flex-direction: column;
        width: 75%;
        overflow: hidden;
        background-image: url('/background.png');
        background-size: cover;
        box-sizing: border-box;
    }

    .toolbar {
        display: flex;
        margin: 0.5rem 0.5rem 0rem 0.5rem;
        gap: 0.7rem;
        align-items: center;
        background-color: #18181b;
        border-radius: 4px;
        height: 35px;
        padding-left: 3px;
        box-sizing: border-box;
    }

    .search-input input {
        background-color: #000000;
        border: 1px solid #444444;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        width: 300px;
        height: 13px;
        font-size: 16px;
    }

    .search-input input:focus {
        outline: none;
        border: 1px solid #444444;
    }

    .filter-action {
        display: flex;
        gap: 1rem;
    }

    .theme-orange {
        width: 100px;
        height: 25px;
        background-color: #000;
        border: 1px solid #444444;
        color: #999999;
        border-radius: 4px;
        text-align: center;
        font-size: 14px;
    }


    .wrapper-card {
        gap: 5px;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-start;
        align-content: flex-start;
        height: 100vh;
        overflow: auto;
        flex-direction: row;
        padding-bottom: 77px;
        margin: 0.5rem 0.5rem 0rem 0.5rem;
        /* align-items: center; */
    }


    .card-container {
        background-color: #18181b;
        /* width: 293px; */
        /* height: 107px; */
        width: 24.63%;
        height: 17%;
        border-radius: 4px;
        padding: 20px 20px 20px 20px;
        transition: 0.1s;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-sizing: border-box;
        /* margin: 0.4%; */
    }

    .card-container:hover {
        background-color: #0000ff;
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        color: #fff;
        /* padding-top: 25px; */
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
    }

    .card-category {
        font-size: 12px;
        /* padding-right: 0rem; */
    }

    .card-price {
        font-size: 16px;
        color: #fff;
        margin-top: 12px;
    }

    .card-stock {
        display: flex;
        gap: 1.4rem;
        color: #fff;
        font-size: 12px;
        margin-top: 30px;
        justify-content: flex-start;
    }

    .main-right {
        display: flex;
        width: 30%;
        height: 100%;
        background-color: #18181b;
        flex-direction: column;
        padding: 0px 20px 20px 20px;
        box-sizing: border-box;
    }

    .ordered-info {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100px;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .name-ordered input {
        font-size: 16px;
        background-color: #18181b;
        border: none;
        color: #fff;
        font-weight: 600;
    }

    .name-ordered input:focus {
        outline: none;
        border: none;
    }

    .table-ordered {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .table-ordered .title {
        color: #999999;
        font-size: 12px;
    }

    .table-ordered .select-table {
        background-color: #18181b;
        border: none;
        color: #6675FF;
        font-size: 12px;
    }

    .order-info {
        display: flex;
        justify-content: space-between;
        margin-top: 16px;
    }

    .order-info .date-order {
        color: #9e9e9e;
        font-size: 16px;
    }

    .order-info .invoice-order {
        color: #6675ff;
        font-size: 16px;
    }

    .order-list-container {
        display: flex;
        width: 100%;
        height: 540px;
        flex-direction: column;
        gap: 1.7rem;
        overflow-y: auto;
    }

    .card-order {
        font-size: 16px;
        color: #fff;
        font-weight: 470;
        display: flex;
        flex-direction: column;
    }

    .info-menu {
        display: flex;
        justify-content: space-between;
    }

    .qty-order {


        margin-top: 10px;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .qty {
        margin-right: 40px;
        font-size: 12px;
    }

    .button-min {
        padding: 3px 10px 3px 10px;
        font-size: 20px;
        background-color: #1E1B30;
        border: none;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .button-min:hover {
        background-color: #ff0000;
        color: #fff;
    }

    .button-plus {
        padding: 3px 9px 3px 9px;
        font-size: 20px;
        border: none;
        background-color: #1E1B30;
        color: #6675FF;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .button-plus:hover {
        background-color: #0000ff;
        color: #fff;
    }

    .total-transactions {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
    }

    .total-transactions .total {
        font-size: 16px;
        color: #fff;
        font-weight: 600;
    }

    .total-transactions .total-price {
        font-size: 16px;
        color: #ff6700;
        font-weight: 600;
    }

    .pay {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .pay .pay-title {
        font-size: 16px;
        color: #fff;
        font-weight: 400;
    }

    .pay input {
        background-color: #18181b;
        border: 1px solid #333333;
        border-radius: 4px;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        width: 82%;
        height: 47px;
        margin-top: 10px;
    }

    .pay input:focus {
        outline: none;
    }

    .return {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .return-amount-display {
        font-size: 16px;
        color: #fff;
        font-weight: 600;
    }

    .return-title {
        font-size: 16px;
        color: #fff;
        font-weight: 400;
    }

    .pay-action {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 20px;
    }

    .pay-button {
        width: 40%;
        height: 50px;
        font-size: 16px;
        background: #0000ff;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        transition: 0.5s;
    }

    .pay-button:hover {
        background-color: #1E1B30;
        border: 1px solid #0000ff;
        color: #6675FF;
    }

    .cancel-button {
        width: 40%;
        height: 50px;
        font-size: 16px;
        background: #333;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        transition: 0.5s;
    }

    .cancel-button:hover {
        background-color: #301b1b;
        border: 1px solid #ff0000;
        color: #ff6666;
    }
</style>


</html>