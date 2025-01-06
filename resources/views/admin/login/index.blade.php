<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登陆页</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/components/common.js', 'resources/js/pages/login.js'])

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

        /* 添加新的全局样式 */
        .login-container * {
            outline: none !important;
            box-shadow: none !important;
        }

        /* 最严格的光标控制 */
        .login-container *:not(input[type="text"]):not(input[type="password"]) {
            outline: none !important;
            box-shadow: none !important;
            -webkit-tap-highlight-color: transparent !important;
            -webkit-appearance: none !important;
            --tw-ring-offset-width: 0 !important;
            --tw-ring-offset-color: transparent !important;
            --tw-ring-color: transparent !important;
            --tw-ring-offset-shadow: none !important;
            --tw-ring-shadow: none !important;
        }

        /* 输入框的焦点效果 */
        .login-container input[type="text"],
        .login-container input[type="password"] {
            outline: auto !important;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            border-color: #6366f1 !important;
        }

        /* 移除所有按钮和链接的焦点效果 */
        .login-container button,
        .login-container a,
        .login-container input[type="checkbox"] {
            user-select: none !important;
        }
    </style>
</head>
<body class="min-h-screen relative">
    <div id="app"></div>
    <!-- 背景图片容器 -->
    <div class="bg-image" :style="{ backgroundImage: `url(${backgroundImage})` }"></div>
    <div class="overlay"></div>

    <!-- 添加加载动画 -->
    <div class="loading-spinner" v-if="isLoading">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-600"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 relative login-container no-select" :style="{ opacity: loginContainerOpacity }">
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
            <form class="mt-8 space-y-6 focus:outline-none focus:ring-0" action="" method="POST">
                <div class="space-y-4">
                    <div class="relative">
                        <label for="account" class="text-sm font-medium text-gray-700 block mb-2 select-none">账号</label>
                        <input id="account" name="account" type="text" required
                               class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out"
                               placeholder="账号">
                    </div>
                    <div class="relative">
                        <label for="password" class="text-sm font-medium text-gray-700 block mb-2 select-none">密码</label>
                        <input id="password" name="password" type="password" required
                               class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out"
                               placeholder="请输入密码">
                    </div>
                </div>
                <div class="flex items-center justify-between select-none">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-0 focus:outline-none select-none"
                               tabindex="-1">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700 select-none">
                            记住我
                        </label>
                    </div>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-0 select-none" tabindex="-1">
                        忘记密码？
                    </a>
                </div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-0 select-none"
                        tabindex="-1">
                    登录
                </button>
            </form>
        </div>
    </div>
</body>
</html>
