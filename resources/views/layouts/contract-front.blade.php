<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body{
            background-color: #333;
        }
        .print-pdf{
            border-radius: 15px;
            padding: 10px 20px;
        }
        @media (max-width: 768px) {
            .pdf{
                /*zoom: 30%*/

                -webkit-transform: scale(.3);
                -webkit-transform-origin: 0 0;
                width: 142.857143%;
            }
        }
        @media print {
            .pdf{
                -webkit-transform: scale(1);
            }
        }
        .contract-header{
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
