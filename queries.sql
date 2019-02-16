-- Добавляет список категорий
INSERT INTO `category` (`name`) 
    VALUES 
        ('Доски и лыжи'),
        ('Крепления'),
        ('Ботинки'),
        ('Одежда'),
        ('Инструменты'),
        ('Разное');

-- Добавляет пользователей
INSERT INTO `user` (`registration_date`, `email`, `username`, `password`, `avatar_path`, `contacts`) 
    VALUES 
        ('2018-12-18 12:40:30', 'ivan9999@gmail.com', 'Иван', 'qwerty', '/avatars/etovasya.jpg', 'мой телефон 0000'),
        ('2019-02-01 07:12:05', 'vera42@gmail.com', 'Вера Павловна', 'sInvd586ExMT1eV', '/avatar/25361.jpg', 'г. Павлов, ул. Менделеева, 13-48'),
        ('2019-02-16 10:40:00', 'superstar@outerdark.com', 'Melkor', 'iluvatarsucks', NULL, '88005553535'),
        ('2019-01-01 19:20:11', 'peterivanov@gmail.com', 'Пётр Иванов', '123456', '/avatars/egfdgtt1542.jpg', 'г. Норильск, ул. Пушкина, 2а');

-- Добавляет список объявлений
INSERT INTO `lot` (`start_date`, `name`, `description`, `img_path`, `start_price`, `end_date`, `step`, `author_id`, `winner_id`, `category_id`)     VALUES 
    (CURRENT_TIMESTAMP, '2014 Rossignol District Snowboard', 'В отличном состоянии. Берите, не пожалеете!', 'img/lot-1.jpg', 10999, '2019-02-25 00:00:00', 100, 1, NULL, 1),
    (CURRENT_TIMESTAMP, 'DC Ply Mens 2016/2017 Snowboard', 'Не бит, не крашен. Продаю срочно!', 'img/lot-2.jpg', 159999, '2019-02-18 00:00:00', 500, 3, NULL, 1),
    (CURRENT_TIMESTAMP, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Не подошли.', 'img/lot-3.jpg', 8000, '2019-03-01 00:00:00', 50, 3, NULL, 1),
    (CURRENT_TIMESTAMP, 'Ботинки для сноуборда DC Mutiny Charocal', 'Отличные ботинки. Оказались малы!', 'img/lot-4.jpg', 10999, '2019-02-28 00:00:00', 150, 1, NULL, 3),
    ('2019-01-02 16:40:07', 'Куртка для сноуборда DC Mutiny Charocal', 'Красивый чёрный цвет. Новая. Размер не подошёл.', 'img/lot-5.jpg', 7500, '2019-01-08 21:10:00', 100, 3, 2, 4),
    (CURRENT_TIMESTAMP, 'Маска Oakley Canopy', 'Скроет ваши недостатки.', 'img/lot-6.jpg', 5400, '2019-03-08 00:00:00', 50, 3, 2, 6);

-- Добавляет ставки для объявлений
INSERT INTO `bid` (`date`, `price`, `user_id`, `lot_id`) 
    VALUES 
        ('2019-02-17 12:15:03', 8100, 1, 3),
        ('2019-01-08 20:17:40', 7600, 4, 5),
        ('2019-01-08 21:00:01', 7700, 2, 5),
        ('2019-02-17 06:30:12', 160499, 3, 2),
        ('2019-02-17 16:15:03', 8200, 2, 3);

-- Получает список категорий
SELECT `name` FROM `category`;

-- Получает открытые лоты
SELECT `l`.`name`, `l`.`start_price`, `l`.`img_path`, MAX(`b`.`price`) AS `max_price`, `c`.`name` AS `category`
    FROM `lot` AS `l`
    JOIN `category` AS `c` 
    ON l.`category_id` = `c`.`id`
    LEFT JOIN `bid` AS `b` 
    ON `b`.`lot_id` = `l`.`id`
    WHERE `l`.`winner_id` IS NULL
    GROUP BY `l`.`id`;

-- Показывает лот по его id
SELECT  `l`.`id`,  `l`.`start_date`,  `l`.`name`,  `l`.`description`,  `l`.`img_path`,  `l`.`start_price`,  `l`.`end_date`,  `l`.`step`,  `l`.`author_id`,  `l`.`winner_id`, `c`.`name` AS `category`
    FROM `lot` AS `l` 
    JOIN `category` AS `c` 
    ON `l`.`category_id` = `c`.`id` 
    WHERE `l`.`id` = 1;

-- Обновляет название лота по его идентификатору
UPDATE `lot` SET `name` = 'Маска Oakley Canopy 666' 
    WHERE `id` = 6;

-- Получает список самых свежих ставок для лота по его идентификатору
SELECT `date`, `price`, `user_id`, `lot_id`
    FROM `bid` 
    WHERE `lot_id` = 3 
    ORDER BY `date` DESC;

