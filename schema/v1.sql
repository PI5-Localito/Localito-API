create database if not exists localito;
use localito;

create table if not exists cities(
	id int auto_increment primary key,
    country_code varchar(3) not null,
    city_name varchar(255) not null,
    longitude decimal not null,
    latitude decimal not null
) engine=innodb;

create table if not exists users(
	id int auto_increment primary key,
	name varchar(100) not null,
	last_name varchar(100) not null,
	phone varchar(15) unique,
	email varchar(255) unique,
    password varchar(60) not null,
    avatar varchar(128),
    constraint chk_user 
        check(`phone` is not null or `email` is not null)
) engine=innodb;

create table if not exists buyers(
	id int auto_increment primary key,
	user_id int not null,
	state boolean not null default true,
	constraint fk_user_buyer_id
		foreign key (user_id) references users(id)
		on delete cascade
) engine=innodb;

create table if not exists sellers(
	id int auto_increment primary key,
	user_id int not null,
	state boolean not null default true,
	constraint fk_user_seller_id
		foreign key (user_id) references users(id)
		on delete cascade
) engine=innodb;

create table if not exists stands(
	id int auto_increment primary key,
	seller_id int not null,
    tag varchar(25) not null unique,
	stand_name varchar(127) not null,
	info text default null,
	city int not null,
	constraint fk_stand_seller_id
		foreign key (seller_id) references users(id) on delete cascade,
	constraint fk_stand_city_id
		foreign key (city) references cities(id) on delete cascade
) engine=innodb;

create table if not exists products(
	id int auto_increment primary key,
    stand_id int not null,
    name varchar(127) not null,
    info varchar(255),
    image varchar(255),
    price decimal not null,
    constraint fk_products_stand_id
		foreign key (stand_id) references stands(id)
        on delete cascade
) engine=innodb;

create table if not exists orders(
	id int auto_increment primary key,
    buyer_id int not null,
    seller_id int not null,
    stand_id int not null,
    date datetime default current_timestamp,
    state enum('PENDING', 'REJECTED', 'ACCEPTED', 'FINISHED') default 'PENDING' not null
) engine=innodb;

create table if not exists products_in_orders(
	order_id int not null,
    product_id int not null,
    product_quantity int default '1' not null
) engine=innodb;

create table if not exists messages(
	id int auto_increment primary key,
    user_from int,
    user_to int,
    order_id int,
    body varchar(255),
    message_timestamp timestamp default current_timestamp
) engine=innodb;

