USE Test01;

/* 1. CREATE master table for USER's  */
create table users(
	id int unsigned AUTO_INCREMENT not null,
	username varchar(255) not null,
	password char(40) not null,
	PRIMARY KEY (id)
);

/* select * from users; */