create table login_background_picture
(
    id           int auto_increment,
    url          varchar(255) default '' not null comment '地址',
    storage_mode tinyint(1)   default 1  null comment '存储类型 1-本地 2-可道云',
    created_at   int          default 0  not null comment '创建时间',
    constraint login_background_picture_pk
        primary key (id)
) comment '登陆背景图';

create table kodbox
(
    id         int auto_increment,
    ext        varchar(255) default '' not null comment '文件夹',
    created_at int          default 0  not null comment '创建时间',
    path       varchar(255) default '' not null comment '路径',
    constraint kodbox_pk
        primary key (id)
) comment '可道云文件夹';

create index kodbox_ext_index
    on kodbox (ext);

CREATE TABLE `admin`
(
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `username`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
    `password`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
    `created_at`   int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updated_at`   int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    `email`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
    `email_verify` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '邮箱验证 0-未通过 1-通过',
    PRIMARY KEY (`id`),
    KEY            `admin_account` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台用户表';
