<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;

        }

        body {
            min-height: 100vh;
            background-color: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: Arial, Helvetica, sans-serif
        }

        input,
        button {
            outline: none;
        }

        .error {
            width: 500px;
            margin-bottom: 10px;
        }

        .error>p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .box {
            width: 500px;
            background-color: #fff;
            padding: 20px 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;

        }

        .box h1 {
            text-align: center;
            color: #444;
            text-transform: capitalize;
            padding: 20px 0;

        }

        .box form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
        }

        .box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cfcfcf;
            border-radius: 5px;
            height: 45px;
            font-size: 17px;

        }

        .box input::placeholder {
            font-size: 14px;
        }

        .box input:focus {
            border-color: rgb(150, 91, 209);
        }


        .box .submit {
            text-align: center;
            margin: auto;
        }

        .box button {
            width: 180px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #965bd1;
            border: none;
            color: #fff;
            cursor: pointer;
            text-transform: capitalize;
            border-radius: 5px;
            opacity: .9;
            transition: .3s;

        }

        .box .submit button:hover {
            opacity: 1;
        }
    </style>
</head>

<body>



    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p style="color:red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (Session::has('error'))
        <div class="error">
            <p style="color:red;">{{ Session::get('error') }}</p>
        </div>
    @endif
    <div class="box">
        <h1>Login</h1>

        <form action="{{ route('userLogin') }}" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Enter Email">
   
            <input type="password" name="password" placeholder="Enter Password">

            <div class="submit">
                <button>Login</button>
            </div>

        </form>
    </div>

</body>

</html>
