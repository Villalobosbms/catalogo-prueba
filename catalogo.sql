create database catalogo;

use catalogo;

create table producto(
	id int auto_increment primary key,
    nombre varchar(255) not null,
    precio float not null,
    cantidad int not null
);


