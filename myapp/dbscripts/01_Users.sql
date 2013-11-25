USE Test01;

/* 1. CREATE master table for USER's  */
create table users(
	id int unsigned AUTO_INCREMENT not null,
	username varchar(255) not null,
	password char(40) not null,
	PRIMARY KEY (id)
);

/* select * from users; */

alter table users 
add column active tinyint unsigned not null default 1;



alter table users
add column token char(40) default null,
add column token_used datetime default null,
add column token_uses int not null default 0,
add unique key token(token);

/*
select * from users;
*/