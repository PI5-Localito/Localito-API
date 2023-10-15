create database if not exists localito;

use localito;

create table if not exists users(
	id INT auto_increment primary key, 
	name VARCHAR(100) not null, 
	last_name VARCHAR(100) not null, 
	phone VARCHAR(15) not null unique, 
	email VARCHAR(255) not null unique,
	password VARCHAR(50) not null
)ENGINE=InnoDB;
                    
create table if not exists buyers(
	id INT auto_increment primary key,
	user_id INT not null,
	state INT not null default '1',
	constraint fk_user_buyer_id
		foreign key (user_id) references users(id)
		on delete cascade
)ENGINE=InnoDB;
                                    
create table if not exists sellers(
	id INT auto_increment primary key,
	user_id INT not null,
	state INT not null default '1',
	constraint fk_user_seller_id
		foreign key (user_id) references users(id)
		on delete cascade
)ENGINE=InnoDB;
									
create table if not exists cities(
	id int auto_increment primary key,
    country_code VARCHAR(3) not null,
    city_name VARCHAR(255) not null,
    longitude decimal not null,
    latitude decimal not null
)ENGINE=InnoDB;
                                    
create table if not exists stands(
	id INT auto_increment primary key,
	seller_id INT not null,
    uid VARCHAR(25) not null unique,
	stand_name VARCHAR(30) not null,
	info TEXT,
	city INT not null,
	constraint fk_stand_seller_id
		foreign key (seller_id) references users(id)
		on delete cascade,
	constraint fk_stand_city_id
		foreign key (city) references cities(id)
        on delete cascade
)ENGINE=InnoDB;

create table if not exists products(
	id INT auto_increment primary key,
    stand_id INT not null,
    name varchar(150) not null,
    price decimal not null,
    constraint fk_products_stand_id
		foreign key (stand_id) references stands(id)
        on delete cascade
)ENGINE=InnoDB;

create table if not exists orders(
	id INT auto_increment primary key,
    buyer_id INT not null,
    seller_id int not null,
    stand_id int not null,
    date datetime default current_timestamp,
    state enum('pending', 'rejected', 'accepted', 'finished') default 'pending' not null,
    constraint fk_order_buyer_id
		foreign key (buyer_id) references buyers(id),
	constraint fk_order_seller_id
		foreign key (seller_id) references sellers(id)
        on delete cascade
)ENGINE=InnoDB;

create table if not exists products_in_orders(
	order_id int not null,
    product_id int not null,
    product_quantity int default '1' not null,
    constraint fk_PIO_order_id
		foreign key (order_id) references orders(id),
	constraint fk_PIO_product_id
		foreign key (product_id) references products(id)
)ENGINE=InnoDB;

create table if not exists messages(
	id int auto_increment primary key,
    user_from int,
    user_to int,
    order_id int,
    message text,
    message_timestamp timestamp default current_timestamp,
    constraint fk_MSG_order_id
		foreign key (order_id) references orders(id)
)ENGINE=InnoDB;