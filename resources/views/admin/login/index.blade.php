<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 添加 jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>登陆页</title>
    <!-- 添加 Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- 添加字体 -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 0.5s ease-in-out;
            z-index: -1;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4));
            z-index: -1;
        }

        /* 添加新的样式 */
        .login-container {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
</head>
<body class="min-h-screen relative">
<!-- 背景图片容器 -->
<div class="bg-image"></div>
<div class="overlay"></div>

<!-- 添加加载动画 -->
<div class="loading-spinner">
    <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-600"></div>
</div>

<div class="min-h-screen flex flex-col items-center justify-center px-4 relative login-container">
    <!-- 登录卡片 -->
    <div class="max-w-md w-full space-y-8 bg-white/10 backdrop-blur-xl p-10 rounded-2xl shadow-2xl">
        <!-- Logo区域 -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-indigo-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                Login
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                请登录您的管理员账户
            </p>
        </div>
        <!-- 登录表单 -->
        <form class="mt-8 space-y-6" action="" method="POST">
            <div class="space-y-4">
                <div class="relative">
                    <label for="account" class="text-sm font-medium text-gray-700 block mb-2">账号</label>
                    <input id="account" name="account" type="text" required
                           class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out"
                           placeholder="账号">
                </div>
                <div class="relative">
                    <label for="password" class="text-sm font-medium text-gray-700 block mb-2">密码</label>
                    <input id="password" name="password" type="password" required
                           class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out"
                           placeholder="请输入密码">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                        记住我
                    </label>
                </div>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">
                    忘记密码？
                </a>
            </div>
            <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                登录
            </button>
        </form>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 获取随机背景图
    function setRandomBackground() {
        const bgImage = document.querySelector('.bg-image');
        const loginContainer = document.querySelector('.login-container');
        const loadingSpinner = document.querySelector('.loading-spinner');

        bgImage.style.opacity = '0';
        loginContainer.style.opacity = '0';

        $.ajax({
            // url: 'https://api.vvhan.com/api/bing?type=json&rand=sj',
            // method: 'GET',
            url: '/admin/LandingPage/getRandomImage',
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                const img = new Image();
                img.onload = function () {
                    bgImage.style.backgroundImage = `url(${response.data.url})`;
                    bgImage.style.opacity = '1';

                    // 背景加载完成后，隐藏加载动画并显示登录表单
                    loadingSpinner.style.display = 'none';
                    loginContainer.style.opacity = '1';
                };
                img.onerror = function () {
                    loadingSpinner.style.display = 'none';
                    loginContainer.style.opacity = '1';
                };
                img.src = response.data.url;
            },
            error: function (xhr, status, error) {
                console.error('Ajax请求失败:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                // 发生错误时也显示登录表单
                loadingSpinner.style.display = 'none';
                loginContainer.style.opacity = '1';
            }
        });
    }

    // 确保 DOM 加载完成后执行
    $(document).ready(function () {
        console.log('页面加载完成，准备设置背景...');
        setRandomBackground();
    });
</script>
</body>
</html>
