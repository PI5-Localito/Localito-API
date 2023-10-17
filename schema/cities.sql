create table if not exists cities(
	id int auto_increment primary key,
    country_code varchar(3) not null,
    city_name varchar(255) not null,
    longitude decimal not null,
    latitude decimal not null
) engine=innodb;

