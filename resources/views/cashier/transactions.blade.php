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


        <div id="invoice-popup" class="popup" style="display: none;">
            <div class="popup-content">
                <span class="close-popup" onclick="closeInvoice()">&times;</span>
                <h2>Invoice</h2>
                <div class="invoice-details">
                    <p><strong>ID:</strong> <span id="invoice-id"></span></p>
                    <p><strong>Customer:</strong> <span id="invoice-customer"></span></p>
                    <p><strong>Table:</strong> <span id="invoice-table"></span></p>
                    <p><strong>Cashier:</strong> <span id="invoice-cashier"></span></p>
                    <p><strong>Date:</strong> <span id="invoice-date"></span></p>
                </div>
                <h3>Items:</h3>
                <ul id="invoice-items"></ul>
                <p><strong>Total Amount:</strong> Rp <span id="invoice-total-amount"></span></p>
                <p><strong>Pay:</strong> Rp <span id="invoice-pay"></span></p>
                <p><strong>Return:</strong> Rp <span id="invoice-return"></span></p>
            </div>
        </div>



        <!-- Konten utama -->
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
                        <input type="text" placeholder="Search customer..." id="customer-search"
                            oninput="searchCustomer()">
                    </div>
                    <div class="filter-icon">
                        ||
                    </div>
                    <div class="filter-action">
                        <div class="drop-menu">
                            <select class="theme-orange" id="date-filter" onchange="filterTransactions()">
                                <option value="newest" selected>Newest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </div>
                        <div class="drop-menu">
                            <div class="date-realtime" id="realtime-clock">

                            </div>
                        </div>
                    </div>
                </div>


                <div class="wrapper-list">
                    <table>
                        <thead>
                            <tr>
                                <!-- <th>Id</th> -->
                                <th>Customer</th>
                                <!-- <th>Table</th> -->
                                <th>Menu</th>
                                <th>Qty All</th>
                                <th>Total Amount</th>
                                <th>Pay</th>
                                <!-- <th>Return</th> -->
                                <!-- <th>Cashier</th> -->
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!-- <tbody> -->
                        <tbody id="transaction-list">
                            @foreach($transactions as $transaction)
                                <tr data-date="{{ $transaction->transaction_time }}">

                                    <!-- <td>{{ $transaction->id }}</td> -->
                                    <td>{{ $transaction->customer }}</td>
                                    <!-- <td>Table - {{ $transaction->table_id }}</td> -->
                                    <td>
                                        <ul>
                                            @foreach($transaction->items as $item)
                                                <li>{{ $item->menu->name }} <span class="td_price"> Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    x {{ $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $transaction->qty }}</td>
                                    <td class="td_total_amount">Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    <td class="td_total_amount">Rp {{ number_format($transaction->pay, 0, ',', '.') }}
                                        <!-- <td class="td_total_amount">Rp -->
                                        <!-- {{ number_format($transaction->return_amount, 0, ',', '.') }} -->
                                        <!-- </td> -->
                                        <!-- <td class="td_date">{{ $transaction->user->name }}</td> -->
                                    <td class="td_date">{{ $transaction->transaction_time }}</td>
                                    <td>
                                        <!-- <button class="invoice-button">Invoice</button> -->
                                        <button class="invoice-button"
                                            onclick="showInvoice({{ $transaction->id }})">Invoice</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>

            <div class="main-right">
                <hr>
                <h4 class="title-table">List Table</h4>
                <div class="table-card-container">
                    @foreach($mejaAll as $meja)
                        @if($meja->status == 'occupied')
                            <div class="table-card">
                                <div class="table-name">
                                    <div class="name-table">Table {{ $meja->number }}</div>
                                    <div class="action-table">
                                        <form method="POST" action="{{ route('changeTableStatus', ['id' => $meja->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn-clear">Clear Table</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-info">
                                    <div class="status">{{ $meja->status }}</div>
                                </div>
                            </div>

                        @endif
                    @endforeach


                    {{-- selain "occupied" --}}
                    @foreach($mejaAll as $meja)
                        @if($meja->status != 'occupied')
                            <div class="table-card">
                                <div class="table-name">
                                    <div class="name-table">Table {{ $meja->number }}</div>
                                    <div class="action-table">
                                    </div>
                                </div>
                                <div class="table-info">
                                    <div class="status">{{ $meja->status }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>


        </div>
    </div>
    <script>
        function searchCustomer() {
            const searchTerm = document.getElementById('customer-search').value.toLowerCase();
            const tableRows = document.querySelectorAll('#transaction-list tr');

            tableRows.forEach(row => {
                const customerName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (customerName.includes(searchTerm)) {
                    row.style.display = 'table-row'; // Tampilkan baris jika nama pelanggan cocok
                } else {
                    row.style.display = 'none'; // Sembunyikan baris jika nama pelanggan tidak cocok
                }
            });
        }

        // Function to update the datetime realtime
        function updateRealtimeClock() {
            const now = new Date();
            const formattedTime = now.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            document.getElementById('realtime-clock').textContent = formattedTime;
        }

        // Update the clock every second
        setInterval(updateRealtimeClock, 1000);

        // Call the function once to set the initial time
        updateRealtimeClock();
        //  <div class="price">Rp${parseInt(price).toLocaleString('id-ID')}</div>
        function showInvoice(transactionId) {
            fetch(`/cashier/transactions/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    const invoicePopup = document.getElementById('invoice-popup');
                    invoicePopup.innerHTML = `
                        <div>
                            <h2>Rize Coffee</h2>
                            <p>Date: ${data.date}</p>
                            <h2>Invoice</h2>
                            <p>ID: ${data.id}</p>
                            <p>Customer: ${data.customer}</p>
                            <p>Table: ${data.table}</p>
                            <p>Cashier: ${data.cashier}</p>
                            <h3>Items:</h3>
                            <ul>
                                ${data.items.map(item => `
                                    <li>${item.menu} - Rp ${parseInt(item.price).toLocaleString('id-ID')} x ${item.quantity}</li>
                                `).join('')}
                            </ul>
                            <p>Total Amount: Rp ${parseInt(data.total_amount).toLocaleString('id-ID')}</p>
                            <p>Pay: Rp ${parseInt(data.pay).toLocaleString('id-ID')}</p>
                            <p>Return: Rp ${parseInt(data.return).toLocaleString('id-ID')}</p>
                            <button class="no-print" onclick="printInvoice()">Print</button>
                            <button class="no-print" onclick="closeInvoice()">Close</button>
                        </div>
                    `;
                    invoicePopup.style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        function closeInvoice() {
            const invoicePopup = document.getElementById('invoice-popup');
            invoicePopup.innerHTML = ''; // Clear the contents
            invoicePopup.style.display = 'none';
        }

        function printInvoice() {
            const invoicePopup = document.getElementById('invoice-popup');
            const originalContents = document.body.innerHTML;
            const printContents = invoicePopup.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            closeInvoice();
        }
        function filterTransactions() {
            const filter = document.getElementById('date-filter').value;
            const table = document.getElementById('transaction-list');
            const rows = Array.from(table.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return filter === 'newest' ? dateB - dateA : dateA - dateB;
            });

            rows.forEach(row => table.appendChild(row));
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterTransactions();
        });
    </script>

</body>

</html>


<style>
    .date-realtime {
        color: #999999;
    }

    @media print {
        .no-print {
            display: none;
        }
    }

    .header {
        color: #ff0000;
    }

    .popup {
        width: 20%;
        height: fit-content;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        font-family: 'Courier New', Courier, monospace;
        color: #000;
    }

    .popup-content {
        max-width: 400px;
        max-height: 400px;
        overflow-y: auto;
    }

    .close-popup {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 24px;
        color: #333;
    }

    .invoice-details {
        margin-bottom: 20px;
    }

    .invoice-details p {
        margin: 5px 0;
        font-weight: bolder;
    }

    .invoice-details strong {
        font-weight: bold;
    }

    .invoice-details span {
        color: #555;
    }

    .invoice-items {
        list-style: none;
        padding: 0;
    }

    .invoice-items li {
        margin-bottom: 5px;
    }

    .invoice-items li span {
        float: right;
        color: #555;
    }

    .invoice-total-amount,
    .invoice-pay,
    .invoice-return {
        font-weight: bold;
        color: #333;
    }


    .td_date {
        color: #777777;
    }


    .td_total_amount {
        color: #ff6700;
    }

    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }


    .title-table {
        font-size: 20px;
        color: #fff;
    }

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

    /* .profile {
        display: flex;
        margin-right: 20px;
    }

    .profile,
    username {
        color: #fff;
        font-size: 16px;
    } */
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
        align-items: center;
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


    .wrapper-list {
        padding: 10px;
        gap: 5px;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-start;
        align-content: flex-start;
        height: 100%;
        overflow: auto;
        flex-direction: row;
        padding-bottom: 77px;
        margin: 0.5rem 0.5rem 0rem 0.5rem;
        background-color: #18181b;
        border-radius: 4px;
        /* align-items: center; */
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        color: #4b4b4b;
        text-align: left;
        padding: 8px;
    }

    td {
        /* border: 1px solid #dddddd; */
        text-align: left;
        padding: 8px;
        color: #fff;
    }

    .invoice-button {
        background-color: #4b4b4b;
        border: none;
        color: white;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    .main-right {
        display: flex;
        width: 30%;
        height: 100%;
        background-color: #18181b;
        flex-direction: column;
        padding: 0px 20px 20px 20px;
        box-sizing: border-box;
        overflow: scroll;
    }

    .table-card-container {}

    .table-card {
        display: flex;
        flex-direction: column;
        /* gap: 0.4rem; */
        margin-bottom: 10px;
    }

    .table-name {
        display: flex;
        justify-content: space-between;
        padding-bottom: 0px;
    }

    .name-table {
        color: #fff;
    }

    /* .action-table, */
    .btn-clear {
        background: #4A4A4A;
        outline: none;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 5px 9px;
    }

    .table-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .status {
        color: #939393;
    }

    .occupied-by {
        color: #939393;
    }
</style>


</html>
<!-- <table>
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Table</th>
                                <th>Menu</th>
                                <th>Qty All</th>
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->customer }}</td>
                                    <td>Table - {{ $transaction->table_id }}</td>
                                    <td>
                                        <ul>
                                            @foreach($transaction->items as $item)
                                                <li>{{ $item->menu->name }} <span class="td_price"> Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    x {{ $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $transaction->qty }}</td>
                                    <td class="td_total_amount">Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                    <td class="td_date">{{ $transaction->transaction_time }}</td>
                                    <td>
                                        <button class="invoice-button"
                                            onclick="showInvoice('{{ $transaction->customer }}', '{{ $transaction->qty }}', '{{ $transaction->total }}', '{{ $transaction->transaction_time }}')">Invoice</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> -->