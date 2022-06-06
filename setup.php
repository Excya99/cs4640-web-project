<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register(function($classname) {
    include "Classes/$classname.php";
});

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);

// In case setup.php needs to be ran again and wipe database 
$db->query("drop table if exists stocks;");
$db->query("drop table if exists portfolios;");
$db->query("drop table if exists users;");

$db->query("create table users (
    id int not null auto_increment,
    name text not null,
    email text not null,
    password text not null,
    primary key (id));");

// Note: some attributes not truly represented due to free API limitations (total_value)
$db->query("create table portfolios (
    portfolio_id int not null,
    portfolio_name text not null,
    total_value double not null,
    total_cost double not null,
    user_id int,
    primary key (portfolio_id),
    foreign key (user_id) references users(id));");

// Note: some attributes not truly represented due to free API limitations (price)
$db->query("create table stocks (
    stock_id int not null auto_increment,
    stock_ticker text not null,
    price double not null,
    original_cost double not null,
    acquire_date text not null,
    portfolio_id int,
    primary key (stock_id),
    foreign key (portfolio_id) references portfolios(portfolio_id));");

echo "<h1 align='center'>Successfully created all database tables!</h1>"; //indicates database setup success