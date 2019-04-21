-- Création de la table des joueurs
CREATE TABLE `players` (
    id INTEGER not null PRIMARY KEY AUTO_INCREMENT,
    playername VARCHAR(60) not null,
    password VARCHAR(100) not null,
    grade VARCHAR(60) default null,
    coins INTEGER not null default 0
);

-- Création des la table des produits
CREATE TABLE `shop_coins_products` (
    id INTEGER not null PRIMARY KEY AUTO_INCREMENT,
    price FLOAT not null default 0,
    coins INTEGER not null default 0,
    payment_mean VARCHAR(20) NOT NULL
);

-- Création de la table des produits à acheter dans le survie
CREATE TABLE `survie_products` (
    id INTEGER not null PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(20) not null,
    price INTEGER not null default 0,
    amount INTEGER not null default 1
);