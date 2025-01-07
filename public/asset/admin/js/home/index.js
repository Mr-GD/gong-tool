document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('.vertical-menu a');
    const submenuLinks = document.querySelectorAll('.has-submenu');

    // 处理子菜单的展开/收起
    submenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentLi = this.closest('li');
            const arrow = this.querySelector('.arrow');
            
            // 切换当前菜单的展开/收起状态
            parentLi.classList.toggle('active');
            
            // 处理箭头旋转
            if (parentLi.classList.contains('active')) {
                arrow.style.transform = 'translateY(-50%) rotate(90deg)';
            } else {
                arrow.style.transform = 'translateY(-50%) rotate(0deg)';
            }
            
            // 如果当前菜单被收起，同时收起其下的所有子菜单
            if (!parentLi.classList.contains('active')) {
                const subMenus = parentLi.querySelectorAll('.submenu li.active, .submenu-l2 li.active');
                subMenus.forEach(sub => {
                    sub.classList.remove('active');
                    // 重置子菜单中的箭头
                    const subArrow = sub.querySelector('.arrow');
                    if (subArrow) {
                        subArrow.style.transform = 'translateY(-50%) rotate(0deg)';
                    }
                });
            }
        });
    });

    // 处理叶子节点的点击（没有子菜单的链接）
    menuLinks.forEach(link => {
        if (!link.classList.contains('has-submenu')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const page = this.getAttribute('data-page');
                
                if (page) {
                    fetch(`/admin/${page}`)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector('.rendering').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('加载页面失败:', error);
                        });
                }

                // 移除所有菜单项的active-page类
                menuLinks.forEach(item => item.classList.remove('active-page'));
                // 给当前点击的菜单添加active-page类
                this.classList.add('active-page');
            });
        }
    });
});