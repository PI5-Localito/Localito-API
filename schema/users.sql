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
