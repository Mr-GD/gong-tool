create table login_background_picture
(
    id           int auto_increment,
    url          varchar(255) default '' not null comment '地址',
    storage_mode tinyint(1)   default 1  null comment '存储类型 1-本地 2-可道云',
    created_at   int          default 0  not null comment '创建时间',
    constraint login_background_picture_pk
        primary key (id)
)
    comment '登陆背景图';

create table kodbox
(
    id         int auto_increment,
    ext       varchar(255) default '' not null comment '文件夹',
    created_at int          default 0  not null comment '创建时间',
    path       varchar(255) default '' not null comment '路径',
    constraint kodbox_pk
        primary key (id)
)
    comment '可道云文件夹';

create index kodbox_ext_index
    on kodbox (ext);

