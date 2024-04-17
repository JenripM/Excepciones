drop database if exists auditoria;
create database auditoria;
use auditoria;


create table CONEXION(
    IdConexion int auto_increment not null,
    servidor varchar(20) not null,
    nombreBase varchar(20) not null,
    usuario varchar(20),
    contraseña varchar(20),
    tipoConexion varchar(20) not null,
    estado tinyint not null
)