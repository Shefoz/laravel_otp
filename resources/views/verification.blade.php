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
            border-radius: 5px 5px 0 0;

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

        .box button , .timer button {
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
        .timer{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row-reverse;
            width: 500px;
            padding: 10px 30px;
            background-color: #f3f3f3;
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 5px 5px;
            border-top: 2px solid #9999998a;

        }
        .timer .time{
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            border-radius: 50%;
        }
    </style>
</head>

<body>


    <div class="error">
        <p id="message_error" style="color:red;"></p>
        <p id="message_success" style="color:green;"></p>
    </div>
    <div class="box">

        <form method="post" id="verificationForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="number" name="otp" placeholder="Enter OTP" required>
            <div class="submit">
                <button>verify</button>
            </div>


        </form>

    </div>
<div class="timer">
    <p class="time"></p>

    <button id="resendOtpVerification">Resend Verification OTP</button>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#verificationForm').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('verifiedOtp') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.success) {
                            alert(res.msg);
                            window.open("/", "_self");
                        } else {
                            $('#message_error').text(res.msg);
                            setTimeout(() => {
                                $('#message_error').text('');
                            }, 3000);
                        }
                    }
                });

            });

            $('#resendOtpVerification').click(function() {
                $(this).text('Wait...');
                var userMail = @json($email);

                $.ajax({
                    url: "{{ route('resendOtp') }}",
                    type: "GET",
                    data: {
                        email: userMail
                    },
                    success: function(res) {
                        $('#resendOtpVerification').text('Resend Verification OTP');
                        if (res.success) {
                            timer();
                            $('#message_success').text(res.msg);
                            setTimeout(() => {
                                $('#message_success').text('');
                            }, 3000);
                        } else {
                            $('#message_error').text(res.msg);
                            setTimeout(() => {
                                $('#message_error').text('');
                            }, 3000);
                        }
                    }
                });

            });
        });

        function timer() {
            var seconds = 30;
            var minutes = 1;

            var timer = setInterval(() => {

                if (minutes < 0) {
                    $('.time').text('');
                    clearInterval(timer);
                } else {
                    let tempMinutes = minutes.toString().length > 1 ? minutes : '0' + minutes;
                    let tempSeconds = seconds.toString().length > 1 ? seconds : '0' + seconds;

                    $('.time').text(tempMinutes + ':' + tempSeconds);
                }

                if (seconds <= 0) {
                    minutes--;
                    seconds = 59;
                }

                seconds--;

            }, 1000);
        }

        timer();
    </script>


</body>

</html>
