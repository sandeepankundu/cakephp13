USE Test01;

create table posts(
	id int not null auto_increment,
	title varchar(255) not null,
	body text not null,
	created datetime not null,
	modified datetime not null,
	primary key (id)
);

insert into posts(title, body, created, modified)
values
('Understanding ABC', 'post body 1', NOW(), NOW()),
('Understanding XYZ', 'post body 2', NOW(), NOW()),
('Understanding PQR', 'post body 3', NOW(), NOW()),
('Understanding Helpers', 'post body 4', NOW(), NOW());

select * from posts;