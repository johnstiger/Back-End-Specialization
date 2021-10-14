<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagom || Login</title>
</head>
<style>
    body {
        margin: 0;
        background: #C7988E;
    }

    .header {
        background-color: rgb(80, 47, 4);
    }

    h1 {
        font-weight: 300;
        margin-top: 18px;
        margin-bottom: 44px;
        font-family: system-ui;
    }

    .login {
        padding: 25px;
        float: right;
        border-radius: 10px;
        background-color: white;
        position: relative;
        margin-top: 12vh;
        margin-right: 82px;
        width: 400px;
    }

    form {
        text-align: center;
    }

    input {
        margin-bottom: 34px;
        width: 90%;
        background-color: #F3EFEF;
        padding: 18px;
        border-radius: 10px;
        border: 1px solid #C4C4C4;
        outline: none;
        color: #C7988E;
    }

    button {
        border: none;
        padding: 10px;
        width: 220px;
        border-radius: 25px;
        background: #C7988E;
        color: white;
        font-size: 20px;
    }

    a {
        text-decoration: none;
        color: black;
    }

    a:hover {
        color: #C7988E;
    }

    p {
        font-family: sans-serif;
        font-size: 15px;
    }

    .links {
        text-align: center;
        margin-top: 30px;
    }

    .image {
        float: left;
    }

    img {
        margin-top: -75px;
        margin-left: 40px;
    }

    .text {
        color: white;
        width: 500px;
        margin-top: -274px;
        margin-left: 232px;
    }

    .description {
        font-family: sans-serif;
        font-size: 25px;
    }

    .aboutUs {
        background: white;
        color: black;
        margin-left: 310px;
    }

    .aboutUs:hover {
        background: #C7988E;
        color: white;
    }

    @media screen and (max-width: 350px) {
        .login {
            align-items: center;
            margin-right: -360px !important;
            width: 610px !important;
            height: 100vh;
            margin-top: 30px;
        }
        .input {
            height: 50px;
            font-size: 28px;
        }
        .button {
            width: 630px;
            padding: 25px;
            font-size: 33px;
        }
        p {
            font-size: 25px;
        }
        h1 {
            font-weight: 500;
            text-align: center;
        }
        img {
            margin-top: -151px;
            margin-left: -24px;
        }
        .text {
            width: 80%;
            margin-left: 34px;
            margin-left: auto;
            margin-right: auto;
            letter-spacing: 7px;
        }
        .aboutUs {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            padding: 22px;
            font-size: 30px;
        }
        .description {
            font-size: 39px;
        }
        .inputField {
            height: 50px;
            font-size: 30px;
        }
        .loginButton {
            width: 100%;
            padding: 22px;
            font-size: 35px;
            margin-top: 20px;
        }
    }

    @media screen and (max-width: 375px) {
        img {
            margin-top: -130px;
            margin-left: -5px;
        }
        .text {
            display: block;
            margin-left: auto;
            margin-right: auto;
            word-spacing: 7px;
        }
        .aboutUs {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<body>
    <div class="header">
        <div class="container">
            <div class="image">
                <img src="[{ asset('imgs/dagom-logo.jpg') }}" alt="">
                <div class="text">
                    <p class="description">Dagom is an online thrift shop that offers brand-new and preloved blouses, dresses, trousers, and other clothing needs. </p>
                </div>
                <a href="">
                    <button class="aboutUs">About Us</button>
                </a>
            </div>
            <div class="login">
                <h1>Login</h1>
                <form action="">
                    <input type="email" name="" placeholder="Username" id="" class="inputField">
                    <input type="password" name="" placeholder="Password" id="" class="inputField">
                    <br>
                    <button type="submit" class="loginButton">Log In</button>
                </form>
                <div class="links">
                    <p><a href=""> Forgot Password? </a></p>
                    <p> No account yet? <a href="">Sign Up</a> </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
