<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理首页</title>
    <link rel="stylesheet" href="{{ asset('asset/admin/css/home/index.css') }}">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h1>后台管理系统</h1>
            </div>
            <nav>
                <ul class="vertical-menu">
                    <li><a href="#" data-page="dashboard"><span>首页</span></a></li>
                    <li>
                        <a href="#" class="has-submenu"><span>用户管理</span><i class="arrow"></i></a>
                        <ul class="submenu">
                            <li><a href="#" data-page="users/list"><span>用户列表</span></a></li>
                            <li>
                                <a href="#" class="has-submenu"><span>用户分组</span><i class="arrow"></i></a>
                                <ul class="submenu-l2">
                                    <li><a href="#" data-page="users/groups/vip"><span>VIP用户</span></a></li>
                                    <li><a href="#" data-page="users/groups/normal"><span>普通用户</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><span>订单管理</span></a></li>
                    <li><a href="#"><span>设置</span></a></li>
                </ul>
            </nav>
        </aside>
        <div class="content-wrapper">
            <div class="main-content">
                <main>
                    <section>
                        <h2>欢迎来到后台管理系统</h2>
                        <p>这里是后台管理的首页，您可以在这里查看和管理各种数据。</p>
                    </section>
                </main>
            </div>
            <div class="rendering">
                <!-- 动态内容将在这里加载 -->
            </div>
        </div>
        <footer class="fixed-footer">
            <p>版权所有 &copy; 2023</p>
        </footer>
    </div>
    <script src="{{ asset('asset/admin/js/home/index.js') }}"></script>
</body>
</html>
