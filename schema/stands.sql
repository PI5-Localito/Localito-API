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
    price decimal not null,
    constraint fk_products_stand_id
		foreign key (stand_id) references stands(id)
        on delete cascade
) engine=innodb;
