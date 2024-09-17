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



        <div class="navbar">
            <!-- <div class="nav-items">
                <a href="" class="nav-item"> User and Activity</a>
            </div> -->
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

            <!-- </div> -->
        </div>
        <div class="main" style="width:100%">
            <div class="main-left" style="width:">
                <div class="toolbar">

                    <div class="tool-left">
                        <div class="search-input">
                            <input type="text" id="userSearchInput" placeholder="Search here....">
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const searchInput = document.getElementById('userSearchInput');
                                const userTable = document.getElementById('user-table');
                                const rows = userTable.getElementsByTagName('tr');

                                searchInput.addEventListener('input', function () {
                                    const filter = searchInput.value.toLowerCase();
                                    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                                        const cells = rows[i].getElementsByTagName('td');
                                        const nameCell = cells[1]; // The name is in the second cell (index 1)
                                        const name = nameCell.textContent || nameCell.innerText;

                                        if (name.toLowerCase().indexOf(filter) > -1) {
                                            rows[i].style.display = "";
                                        } else {
                                            rows[i].style.display = "none";
                                        }
                                    }
                                });
                            });
                        </script>

                        <div class="filter-icon">
                            ||
                        </div>
                        <div class="filter-action">
                            <div class="drop-menu">

                                <div class="date-realtime" id="realtime-clock">

                                </div>
                                <!-- <select class="theme-orange">
                                    <option>cashier</option>
                                    <option selected>manager</option>
                                </select> -->
                                <!-- <select id="roleFilter" class="theme-orange">
                                    <option value="all">All</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">admin</option>
                                </select> -->
                            </div>
                            <div class="drop-menu">
                            </div>
                        </div>
                    </div>
                    <div class="tool-right">
                        <div class="btn-add">
                            <!-- <button class="addbutton">+ Add User</button> -->
                            <!-- <button class="addbutton" onclick="openAddUserForm()">+ Add User</button> -->
                        </div>
                    </div>
                </div>

                <div class="wrapper-card">

                    <table id="user-table">
                        <tr>
                            <td class="tr-head">Id</td>
                            <td class="tr-head">Name</td>
                            <td class="tr-head">Email</td>
                            <td class="tr-head">Role</td>
                            <td class="tr-head">Created at</td>
                            <td class="tr-head">Update at</td>
                        </tr>
                        @foreach ($users as $user)
                            {{-- Periksa apakah pengguna memiliki peran 'cashier' --}}
                            @if($user->usertype === 'cashier')
                                <tr id="user-{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->usertype }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>


                    <script>
                        // Menggunakan JavaScript untuk menampilkan konfirmasi sebelum penghapusan
                        document.querySelectorAll('.button-delete').forEach(button => {
                            button.addEventListener('click', function () {
                                const userName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                                if (confirm(`Apakah Anda yakin menghapus akun dengan user name ${userName}?`)) {
                                    this.closest('.delete-form').submit();
                                }
                            });
                        });
                    </script>
                </div>


            </div>

            <!-- mainrigthgcc -->
            <div class="main-right" style="">
                <hr>
                <div class="ordered-info">
                    <div class="table-ordered">
                        <h4 style="color: white; padding:0; margin:0;">Log Activity</h2>
                    </div>
                </div>


                <div class="order-list-container">
                    <!-- card -->
                    <div class="card-order">

                        @php
                            $activityLogs = \App\Models\ActivityLog::with('user')->whereHas('user', function ($query) {
                                $query->where('usertype', 'cashier');
                            })->orderBy('created_at', 'desc')->get();
                        @endphp

                        @foreach($activityLogs as $log)
                            @if($log->user->usertype === 'cashier')
                                <div class="top-row">
                                    <p style="padding-bottom:0px;">{{ $log->user->name }}</p>
                                    <p style="padding-bottom:0px; color:gray; ">{{ $log->user->usertype }}</p>
                                </div>
                                <div class="bottom-row">
                                    <p style="padding-bottom:15px; color:gray;">{{ ucfirst($log->activity_type) }}</p>
                                    <span class="name"
                                        style="padding-bottom:15px;">{{ $log->created_at->format('d-m-Y, h:i A') }}</span>
                                </div>
                            @endif
                        @endforeach

                        <!-- <div class="top-row">
                            <p style="padding-bottom:20px;">Syaqila natasha</p>
                            <p style="padding-bottom:20px; color:gray; ">cashier</p>
                        </div>
                        <div class="bottom-row">
                            <p style="padding-bottom:15px; color:gray;">Login</p>
                            <span class="name" style="padding-bottom:15px;">17-02-2024, 12:00 AM</span>
                        </div> -->
                    </div>
                    <!-- endcard -->
                    <!-- main -->

                </div>
            </div>
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
        padding: 3px 9px;
        margin-right: 4px;
    }

    .clear-button {
        background-color: gray;
        color: white;
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

    .tool-left {
        display: flex;
        gap: 0.7rem;
    }

    .tool-right {
        display: flex;
        width: 50%;
        justify-content: flex-end;
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

    }

    .ordered-info {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 50px;
        margin-top: 20px;

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
        overflow: scroll;

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

    .edit-role {
        background-color: #000;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 5px 10px;
        margin-bottom: 40px;
        margin-top: 7px;
    }

    .add-role {
        background-color: #000;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 5px 10px;
        margin-bottom: 40px;
        margin-top: 7px;
    }

    .add-submit {
        width: 100%;
    }

    .edit-submit {
        width: 100%;
    }

    .button-adduser .add-submit {
        background-color: #0000ff;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 5px 10px;
        padding: 8px 19px;
    }

    .button-edituser .edit-submit {
        background-color: #0000ff;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 5px 10px;
        padding: 8px 19px;
    }

    .button-adduser .add-close {
        background-color: #222222;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 8px 19px;
    }

    .edit-form {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        /* align-items: center; */
        width: 100%;
    }

    .add-form {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        /* align-items: center; */
        width: 100%;
    }

    .add-form input {
        width: 100%;
        height: 28px;
        margin-top: 5px;
        margin-bottom: 10px;
        background-color: #000;
        outline: none;
        border: none;
        border-radius: 4px;
        color: #e7e7e7;
    }

    .edit-form input {
        width: 100%;
        height: 28px;
        margin-top: 5px;
        margin-bottom: 10px;
        background-color: #000;
        outline: none;
        border: none;
        border-radius: 4px;
        color: #e7e7e7;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #18181b;
        margin: 15% auto;
        padding: 27px;
        border-radius: 4px;
        width: 20%;
        height: fit-content;
        color: #fff;
    }

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

    .action-form {
        display: flex;
    }
</style>

</html>