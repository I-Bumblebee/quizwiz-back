@props(['url', 'heading', 'salutation', 'text', 'buttonText'])

<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&family=Raleway:wght@400..900&display=swap" rel="stylesheet">
    <style>
        .container {
            width: 100%;
            height: 50vh;
            background-color: #F6F6F6;
            padding-top: 50px;
            padding-bottom: 150px;
        }

        .content {
            max-width: 455px;
            margin: auto;
        }

        h1 {
            font-family: 'Raleway', sans-serif;
            font-size: 40px;
            line-height: 59px;
            text-align: center;
            font-weight: 700;
            margin: 30px auto 0 auto;
        }

        .logo {
            font-size: 25px;
            font-weight: 600;
            margin:auto;
            width: 100px;
        }

        .logo-wrapper {
            width: 100%;
        }

        p {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 20px;
            margin: 30px auto 0 auto;
        }

        .salutation {
            margin-left: 10px;
        }

        .button {
            display: block;
            text-decoration: none;
            padding: 14px 0;
            background-color: #4B69FD;
            border-radius: 10px;
            color: white !important;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            margin: 30px auto 0 auto;
            width: 130px;
        }

        @media screen and (max-width: 440px) {
            .content {
                padding: 0 40px;
            }

            h1 {
                font-size: 24px;
                line-height: 38px;
            }

            p {
                font-size: 14px;
                line-height: 25px;
            }

            .salutation {
                margin-left: 0;
                margin-bottom: -25px;
            }

        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="logo-wrapper">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="QuizWiz Logo">
            </div>
        </div>
        <h1>{!! $heading !!}</h1>
        <p class="salutation">{{ $salutation }}</p>
        <p>{{ $text }}</p>
        <a href="{{ $url }}" class="button">{{ $buttonText }}</a>
    </div>
</div>
</body>
</html>
