<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rize Coffe</title>
</head>

<body>
    <div class="container">
        <div class="main-left">
            <div class="top">
                <div class="title">
                    <div class="heading">Rize Coffe Cashier</div>
                    <div class="sub-heading">atur transaksi dan menu dalam satu aplikasi dengan mudah</div>
                </div>
            </div>
            <div class="bottom"></div>
        </div>
        <div class="main-right">
            <div class="form-container">
                <div class="login-title">
                    <h2>Log in</h2>
                </div>
                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" class="form">
                    @csrf
                    <label for="userId"><span> Email </span></label>
                    <input type="email" name="email" id="userId" value="{{ old('email') }}">
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="password"><span> Password </span></label>
                    <input type="password" name="password" id="password" value="{{ old('password') }}">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="check">
                        <input type="checkbox" id="showPasswordCheckbox">
                        <label for="showPasswordCheckbox">Show Password</label>
                    </div>
                    <button type="submit">Login</button>
                </form>


                <!-- <form action="{{ route('login') }}" method="POST" class="form">
                    @csrf
                    <label for="userId"><span> Email </span></label>
                    <input type="email" name="email" id="userId">
                    @error('loginError')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="password"><span> Password </span></label>
                    <input type="password" name="password" id="password">
                    @error('loginError')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="check">
                        <input type="checkbox" id="showPasswordCheckbox">
                        <label for="showPasswordCheckbox">Show Password</label>
                    </div>
                    <button type="submit">Login</button>
                </form> -->
                <script>

                    const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
                    const passwordInput = document.getElementById('password');
                    showPasswordCheckbox.addEventListener('change', function () {
                        if (showPasswordCheckbox.checked) {
                            passwordInput.type = 'text';
                        } else {
                            passwordInput.type = 'password';
                        }
                    });
                    //         window.onload = function() {
                    //         if (!localStorage.getItem('hasRefreshed')) {
                    //         setTimeout(function() {
                    //             localStorage.setItem('hasRefreshed', 'true');
                    //             location.reload();
                    //         }, 10);
                    //         } else {
                    //         localStorage.removeItem('hasRefreshed');
                    //     }
                    // };                    

                </script>
            </div>
        </div>
    </div>
</body>
<style>
    body {
        padding: 0;
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .check {
        position: relative;
        margin-bottom: 4rem;
    }

    .check input[type="checkbox"] {
        position: absolute;
        opacity: 0;
    }

    .check label {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
    }

    .check label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 18px;
        height: 18px;
        border: 1px solid #999;
        border-radius: 100px;
    }

    .check label:after {
        content: ' ';
        position: absolute;
        top: 3px;
        left: 6px;
        width: 5px;
        height: 9px;
        border: solid #000;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        opacity: 0;
    }

    .check input[type="checkbox"]:checked+label:before {
        background-color: #007bff;
    }

    .check input[type="checkbox"]:checked+label:after {
        opacity: 1;
    }


    .container {
        display: flex;
        padding: 0;
        width: 100%;
        height: 100vh;
    }

    .main-left {
        width: 60%;
        height: 100vh;
        background-color: #0000ff;
        display: flex;
        flex-direction: column;
    }

    .top {
        height: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .title {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        margin-left: 120px;
        margin-top: 70px;
    }

    .heading {
        color: #fff;
        font-size: 60px;
        font-weight: 470;
    }

    .sub-heading {
        color: #fff;
        text-align: left;
        display: flex;
        font-size: 20px;
        width: 500px;
        padding-top: 10px;
    }

    .bottom {
        height: 50%;
        background-image: url(app.jpg);
        background-size: 110% auto;
        background-position: 400px 17px;
        background-repeat: no-repeat;
    }

    .main-right {
        width: 40%;
        background-color: #000000;
        z-index: 1000;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .form-container {
        /* margin-top: 70px; */
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        height: 100vh;
    }

    .login-title {
        font-size: 40px;
        color: #fff;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        margin-right: 238px;
        margin-bottom: 30px;
    }

    .login-title h2 {
        display: flex;
        text-align: left;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    form label {
        width: 100%;
        margin-bottom: 5px;
        color: #999999;
        padding-bottom: 10px;
    }

    form input {
        width: 24rem;
        height: 2.7rem;
        outline: none;
        padding: 5px 10px;
        margin-bottom: 2rem;
        border: 1px solid #222222;
        background-color: #000;
        border-radius: 4px;
        color: #fff;
        font-size: 20px;
    }

    button {
        height: 4rem;
        border: none;
        color: #fff;
        margin: 1rem 0;
        font-size: 20px;
        padding: 0.5rem;
        cursor: pointer;
        font-weight: bold;
        border-radius: 4px;
        background: #0000ff;
        transition: 0.2s;
    }

    button:hover {
        background-color: #1E1B30;
        border: 1px solid #0000ff;
        color: #6675FF;

    }

    .alert {
        color: #ff0000;
        padding: 0px;
        margin: 0px;
    }
</style>

</html>