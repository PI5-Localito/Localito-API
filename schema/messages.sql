create table if not exists messages(
	id int auto_increment primary key,
    user_from int,
    user_to int,
    order_id int,
    body varchar(255),
    message_timestamp timestamp default current_timestamp
) engine=innodb;


