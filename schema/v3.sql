alter table stands drop foreign key fk_stand_seller_id;

alter table stands add constraint fk_stand_seller_id
        foreign key (seller_id) references sellers(id) on delete cascade;
