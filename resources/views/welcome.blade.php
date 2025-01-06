<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- 引入Bootstrap CSS -->
    <link href="../css/base/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* 设置背景颜色 */
        }
        #app {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 添加阴影效果 */
            background-color: white; /* 设置内容背景颜色 */
        }
    </style>
</head>
<body class="antialiased">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" id="app"></div>
    </div>
</div>
</body>
</html>
