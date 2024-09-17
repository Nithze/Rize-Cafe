<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rize Coffe</title>
    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
</head>

<body>

    <div class="container">
        <div id="addMenuModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddMenuForm()">&times;</span>
                <h2>Tambah Menu</h2>
                <form class="add-form" action="{{ route('addMenu') }}" method="POST" id="addMenuForm">
                    @csrf
                    <label for="name">Nama Menu:</label><br>
                    <input clastype="text" id="name" name="name" required minlength="4"
                        value="{{ old('name') }}"><br><br>

                    <label for="category">Kategori:</label><br>
                    <select id="category" name="category" required class="add-category">
                        <option value="">Pilih Kategori</option>
                        <option value="Makanan" {{ old('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="Minuman" {{ old('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    </select><br><br>

                    <label for="stock">Stok:</label><br>
                    <input type="number" id="stock" name="stock" required value="{{ old('stock') }}"><br><br>

                    <label for="price">Harga:</label><br>
                    <input type="text" id="price" name="price" required value="{{ old('price') }}"><br><br>

                    <div class="button-addmenu">
                        <button class="add-submit" type="submit">Tambah</button>
                    </div>
                </form>
            </div>

        </div>

        <script>
            function closeAddMenuForm() {
                document.getElementById('addMenuModal').style.display = 'none';
            }
        </script>

        <script>
            function openAddMenuForm() {
                document.getElementById('addMenuModal').style.display = 'block';
            }

            function closeAddMenuForm() {
                document.getElementById('addMenuModal').style.display = 'none';
            }

            document.addEventListener("DOMContentLoaded", function () {
                @if ($errors->any())
                    document.getElementById("addMenuModal").style.display = "block";
                @endif
            });
        </script>


        <div id="editMenuModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditMenuForm()">&times;</span>
                <h2>Edit Menu</h2>
                <form class="edit-form" id="editMenuForm">
                    @csrf
                    <!-- Input fields for edit menu -->
                    <label for="edit_name">Nama Menu:</label><br>
                    <input type="text" id="edit_name" name="edit_name" required minlength="4" value=""><br><br>

                    <label for="edit_category">Kategori:</label><br>
                    <select id="edit_category" name="edit_category" required class="edit-category">
                        <option value="">Pilih Kategori</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                    </select><br><br>

                    <label for="edit_stock">Stok:</label><br>
                    <input type="number" id="edit_stock" name="edit_stock" required value=""><br><br>

                    <label for="edit_price">Harga:</label><br>
                    <input type="text" id="edit_price" name="edit_price" required value=""><br><br>

                    <div class="button-editmenu">
                        <button class="edit-submit" type="submit">Simpan</button>
                    </div>
                </form>
            </div>

            <script>

                function openEditMenuForm(menuId) {
                    fetch(`getMenu/${menuId}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('edit_name').value = data.name;
                            document.getElementById('edit_category').value = data.category;
                            document.getElementById('edit_stock').value = data.stock;
                            document.getElementById('edit_price').value = data.price;

                            document.getElementById('editMenuModal').style.display = 'block';

                            handleSubmitEditMenu(menuId);
                        })
                        .catch(error => console.error('Error:', error));
                }
                function handleSubmitEditMenu(menuId) {
                    document.getElementById('editMenuForm').addEventListener('submit', function (event) {
                        event.preventDefault(); // Menghentikan pengiriman form

                        var formData = new FormData(this);

                        fetch(`editMenu/${menuId}`, {
                            method: 'POST',
                            body: formData,
                        })
                            .then(response => {
                                if (response.redirected) {
                                    window.location.href = response.url;
                                } else {
                                    return response.json();
                                }
                            })
                            .then(data => {
                                if (data) {
                                    alert(data.message);
                                    if (data.success) {
                                        closeEditMenuForm();
                                        window.location.href = "/manager/managerproduk";
                                    }
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                }



                // Function to close modal form edit
                function closeEditMenuForm() {
                    document.getElementById('editMenuModal').style.display = 'none';
                }

            </script>
        </div>





        <div class="navbar">
            <div class="nav-items">
                <a href="/manager/managerlog" class="nav-item">Log Activity</a>
                <a href="/manager/managerproduk" class="nav-item">Menu</a>
                <a href="/manager/managertransactions" class="nav-item">Transactions</a>
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
            <!-- ------------------- -->
        </div>
        <div class="main" style="width:100%">
            <div class="main-left" style="width:">
                <div class="toolbar" style=" ">
                    <div class="tool-left">
                        <div class="search-input">
                            <input type="text" id="searchInput" placeholder="Search here...." onkeyup="searchMenu()">
                            <script>
                                function searchMenu() {
                                    var input = document.getElementById("searchInput");
                                    var filter = input.value.toUpperCase();

                                    var table = document.getElementsByTagName("table")[0];
                                    var tr = table.getElementsByTagName("tr");

                                    for (var i = 1; i < tr.length; i++) {
                                        var td = tr[i].getElementsByTagName("td")[1];
                                        if (td) {
                                            var txtValue = td.textContent || td.innerText;
                                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                                tr[i].style.display = "";
                                            } else {
                                                tr[i].style.display = "none";
                                            }
                                        }
                                    }
                                }
                            </script>
                        </div>
                        <div class="filter-icon">
                            ||
                        </div>
                        <div class="filter-action">
                            <div class="drop-menu">
                                <select id="categoryFilter" onchange="filterMenu()" class="theme-orange">
                                    <option value="all">Semua</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Makanan">Makanan</option>
                                </select>

                                <script>
                                    function filterMenu() {
                                        var input, filter, table, tr, td, i, txtValue;
                                        input = document.getElementById("searchInput");
                                        filter = input.value.toUpperCase();
                                        table = document.getElementsByTagName("table")[0];
                                        tr = table.getElementsByTagName("tr");

                                        // Mendapatkan nilai dari dropdown kategori
                                        var categoryFilter = document.getElementById("categoryFilter");
                                        var selectedCategory = categoryFilter.value;

                                        // Loop melalui semua baris tabel
                                        for (i = 1; i < tr.length; i++) {
                                            // Mengambil kolom kedua (nama menu)
                                            td = tr[i].getElementsByTagName("td")[1];
                                            // Mengambil kolom ketiga (kategori menu)
                                            categoryTd = tr[i].getElementsByTagName("td")[2];
                                            if (td) {
                                                txtValue = td.textContent || td.innerText;
                                                categoryValue = categoryTd.textContent || categoryTd.innerText;

                                                // Mengatur apakah baris harus ditampilkan berdasarkan pencarian dan filter kategori
                                                if ((txtValue.toUpperCase().indexOf(filter) > -1 || filter === '') && (selectedCategory === 'all' || categoryValue === selectedCategory)) {
                                                    tr[i].style.display = "";
                                                } else {
                                                    tr[i].style.display = "none";
                                                }
                                            }
                                        }
                                    }
                                </script>
                            </div>
                            <div class="drop-menu">
                                <!-- <select class="theme-orange">
                                    <option>Favorit</option>
                                    <option selected>Underrated</option>
                                </select> -->
                            </div>
                        </div>
                    </div>
                    <div class="tool-right">
                        <!-- <button class="addbutton">add</button> -->
                        <div class="btn-add">
                            <button class="addbutton" onclick="openAddMenuForm()">+ Add Menu</button>
                        </div>

                    </div>
                </div>

                <div class="wrapper-card">

                    <table>
                        <tr>
                            <td class="tr-head">id</td>
                            <td class="tr-head">menu</td>
                            <td class="tr-head">kategori</td>
                            <td class="tr-head">stock</td>
                            <td class="tr-head">price</td>
                            <td class="tr-head">created_at</td>
                            <td class="tr-head">action</td>
                        </tr>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->id }}</td>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->category }}</td>
                                <td>{{ $menu->stock }}</td>
                                <td>Rp.{{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td>{{ $menu->created_at }}</td>
                                <td>
                                    <div class="action-ed">
                                        <form action="{{ route('deleteMenu', $menu->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button-delete">Hapus</button>
                                        </form>
                                        <!-- <button onclick="openEditMenuForm()" class="button-edit">Edit</button> -->
                                        <button onclick="openEditMenuForm('{{ $menu->id }}')"
                                            class="button-edit">Edit</button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    @if(session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                alert("{{ session('success') }}");
                            });
                        </script>
                    @endif

                </div>

            </div>

            <!-- mainrigthgcc -->
            <div class="main-right" style="">
                <hr>
                <div class="ordered-info">
                    <h4 style="color: white; padding:0;">Table List</h4>
                </div>
                <div class="order-list-container">
                    <!-- card -->
                    <div class="card-order">
                        <div class="table-list">
                            @foreach($meja as $meja)
                                <div class="table-item">
                                    <div class="top-row">
                                        <p style="padding-bottom:5px; color:gray;">Table {{ $meja->number }}</p>
                                        <p style="padding-bottom:5px; color:gray;">Updated</p>
                                    </div>
                                    <div class="bottom-row">
                                        <p style="padding-bottom:5px;">{{ $meja->status }}</p>
                                        <span class="name" style="padding-bottom:5px;">{{ $meja->updated_at }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- <div class="top-row">
                            <p style="padding-bottom:20px; color:gray;">No Table</p>
                            <p style="padding-bottom:20px; color:gray; ">Updated</p>
                        </div>
                        <div class="bottom-row">
                            <p style="padding-bottom:15px;">No. 01</p>
                            <span class="name" style="padding-bottom:15px;">date</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
</body>
<style>
    .btn-add .addbutton {
        width: 100px;
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

    table {
        border-collapse: collapse;
        width: 100%;
    }

    .tr-head {
        color: gray;
    }

    .addbutton {
        background-color: blue;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100px;
        height: 25px;

    }

    .clear-button {
        background-color: gray;
        /* Change the button color to gray */
        color: white;
        /* Optional: Change the text color to white */
        border: none;
        border-radius: 10%;
        cursor: pointer;
    }


    .top-row,
    .bottom-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-row p,
    .bottom-row p {
        margin: 0;
    }

    .bottom-row .name {
        margin-left: auto;
        /* This will push the name to the right */
    }

    td {
        padding: 8px;
        text-align: left;
        color: white;
    }

    /* Mengatur border untuk sel yang berisi data */
    td:nth-child(1),
    td:nth-child(2),
    td:nth-child(3),
    td:nth-child(4),
    td:nth-child(5) {
        border: none;
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

    .addbutton {
        background-color: blue;
        /* Change the button color to gray */
        color: white;
        /* Optional: Change the text color to white */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 50px;
        height: 25px;
        margin: 6px;
    }

    .toolbar {
        display: flex;
        margin: 0.5rem 0.5rem 0rem 0.5rem;
        gap: 0.7rem;
        align-items: center;
        background-color: #18181b;
        border-radius: 4px;
        height: 45px;
        padding-left: 3px;
        box-sizing: border-box;
        justify-content: space-between;
    }

    .tool-right {
        display: flex;
        width: 50%;
        justify-content: flex-end;
    }

    .tool-left {
        display: flex;
        gap: 0.7rem;
    }



    .search-input input {
        background-color: #000000;
        border: 1px solid #444444;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        width: 300px;
        height: 19px;
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

    .button-edit {
        background-color: gray;
        /* Warna merah */
        padding: 5px 20px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        margin-right: 5px;

    }

    .button-edit:hover {
        background-color: blue;
        /* Warna merah gelap */
    }

    .button-delete {
        background-color: #f44336;
        /* Warna merah */
        padding: 5px 20px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        margin-right: 5px;
    }

    .button-delete:hover {
        background-color: #d32f2f;
        /* Warna merah gelap */
    }


    .theme-orange {
        width: 100px;
        height: 30px;
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
        background-color: #18181b;
        border-radius: 4px;
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
        /* gap: 1.4rem; */
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
        box-sizing: border-box;
    }

    .ordered-info {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-bottom: 20px;
        /* margin-top: 10px; */
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
        height: 100%;
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
    }

    .button-plus {
        padding: 3px 9px 3px 9px;
        font-size: 20px;
        border: none;
        background-color: #1E1B30;
        color: #6675FF;
        font-weight: 700;
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

    .return-amount {
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

    .cencel-button {
        width: 40%;
        height: 50px;
        font-size: 16px;
        background: #333;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 4px;
    }

    .action-ed {
        display: flex;
    }

    /* Style untuk form edit menu */
    .edit-form {
        display: flex;
        flex-direction: column;
    }

    .edit-form label {
        margin-bottom: 5px;
    }

    .edit-form input {
        height: 28px;
        margin-bottom: 10px;
        background-color: #000;
        outline: none;
        border: none;
        border-radius: 4px;
        color: #fff;
    }

    .button-editmenu {
        margin-top: 15px;
    }

    .edit-submit {
        background-color: #0000ff;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 8px 12px;
        cursor: pointer;
        width: 100%;
    }

    .edit-submit:hover {
        background-color: #3333ff;
    }

    .add-category {
        background-color: #000;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 7px 10px;
        margin-bottom: 10px;
        margin-top: 7px;

    }

    /* Style untuk modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Style untuk konten modal */
    .modal-content {
        background-color: #18181b;
        margin: 15% auto;
        padding: 20px;
        border-radius: 4px;
        width: 20%;
        /* Mengatur lebar konten modal */
        color: #fff;
    }

    /* Style untuk tombol close */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .add-category {
        background-color: #000;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 7px 10px;
        margin-bottom: 10px;
        margin-top: 7px;

    }

    .edit-category {
        background-color: #000;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 7px 10px;
        margin-bottom: 10px;
        margin-top: 7px;

    }


    /* Style untuk modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Style untuk konten modal */
    .modal-content {
        background-color: #18181b;
        margin: 15% auto;
        padding: 20px;
        border-radius: 4px;
        width: 20%;
        /* Mengatur lebar konten modal */
        color: #fff;
    }

    /* Style untuk tombol close */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Style untuk form tambah menu */
    .add-form {
        display: flex;
        flex-direction: column;
    }

    .add-form label {
        margin-bottom: 5px;
    }

    .add-form input {
        height: 28px;
        margin-bottom: 10px;
        background-color: #000;
        outline: none;
        border: none;
        border-radius: 4px;
        color: #fff;
    }

    .button-addmenu {
        margin-top: 15px;
    }

    .add-submit {
        background-color: #0000ff;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 8px 12px;
        cursor: pointer;
        width: 100%;
    }

    .add-submit:hover {
        background-color: #3333ff;
    }

    .table-item {
        margin-bottom: 15px;
    }
</style>

</html>