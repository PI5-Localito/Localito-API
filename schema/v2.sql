create database if not exists localito;
use localito;

create table if not exists admins(
    id int auto_increment primary key,
    name varchar(100) not null,
    last_name varchar(100) not null,
    email varchar(255) unique not null,
    password varchar(60) not null
) engine=innodb;

insert into admins(name, last_name, email, password) values ('root', 'root', 'root@admin.localito', '$2y$10$PReuRWxDbK.IBfVhQ/kpIulMouvcR77fq.WX6w9BCOrOJTtXsy9uO');
