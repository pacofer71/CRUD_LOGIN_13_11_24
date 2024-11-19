create table users(
    id int auto_increment primary key,
    username varchar(50) unique not null,
    email varchar(60) unique not null,
    pass varchar(200) not null,
    perfil enum("Admin", "Normal") default "Normal"
);