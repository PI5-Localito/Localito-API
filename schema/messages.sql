create table if not exists messages(
	id int auto_increment primary key,
    user_from int,
    user_to int,
    order_id int,
    message text,
    message_timestamp timestamp default current_timestamp,
    constraint fk_msg_order_id
		foreign key (order_id) references orders(id)
) engine=innodb;


