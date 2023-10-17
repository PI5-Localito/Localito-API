create table if not exists orders(
	id int auto_increment primary key,
    buyer_id int not null,
    seller_id int not null,
    stand_id int not null,
    date datetime default current_timestamp,
    state enum('PENDING', 'REJECTED', 'ACCEPTED', 'FINISHED') default 1 not null
) engine=innodb;

create table if not exists products_in_orders(
	order_id int not null,
    product_id int not null,
    product_quantity int default '1' not null
) engine=innodb;

