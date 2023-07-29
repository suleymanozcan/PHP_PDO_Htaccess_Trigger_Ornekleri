CREATE TABLE brands (
  id int(11) NOT NULL,
  name varchar(255) DEFAULT NULL,
  url varchar(255) DEFAULT NULL,
  total int(11) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO brands (id, `name`, url, total, created_at, updated_at) VALUES
(27, 'Mercedes', 'mercedes', 0, '2023-07-29 02:25:14', '2023-07-29 02:25:14'),
(28, 'BMW', 'bmw', 0, '2023-07-29 02:25:17', '2023-07-29 04:28:17'),
(29, 'Fiat', 'fiat', 1, '2023-07-29 02:25:20', '2023-07-29 04:23:22'),
(33, 'Ferrari', 'ferrari', 0, '2023-07-29 02:41:52', '2023-07-29 03:24:04');

CREATE TABLE categories (
  id int(11) NOT NULL,
  name varchar(255) DEFAULT NULL,
  url varchar(255) DEFAULT NULL,
  total int(11) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO categories (id, `name`, url, total, created_at, updated_at) VALUES
(10, 'Ticari', 'ticari', 1, '2023-07-29 03:42:53', '2023-07-29 04:23:36'),
(11, 'Binek', 'binek', 0, '2023-07-29 03:42:56', '2023-07-29 04:28:17'),
(12, 'SUV', 'suv', 0, '2023-07-29 03:42:58', '2023-07-29 03:42:58');

CREATE TABLE products (
  id int(11) NOT NULL,
  cat_id int(11) NOT NULL,
  bra_id int(11) NOT NULL,
  name varchar(255) DEFAULT NULL,
  url varchar(255) DEFAULT NULL,
  price decimal(10,2) DEFAULT NULL,
  vat int(11) DEFAULT NULL,
  stock_code varchar(255) NOT NULL,
  stock_quantity int(11) NOT NULL,
  image varchar(255) DEFAULT NULL,
  details text,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO products (id, cat_id, bra_id, `name`, url, price, vat, stock_code, stock_quantity, image, details, created_at, updated_at) VALUES
(8, 10, 29, 'Ürün Adı', 'urun-adi.html', '200.00', 20, 'STK001', 10, '4681884a5196984cf6c2560b6df98b0f.png', 'test\r\naçıklamasıasdasd', '2023-07-29 03:55:30', '2023-07-29 04:29:43');
DELIMITER $$
CREATE TRIGGER `brands_total_delete` AFTER DELETE ON `products` FOR EACH ROW BEGIN
    UPDATE brands
    SET total = total - 1
    WHERE id = OLD.bra_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `brands_total_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    UPDATE brands
    SET total = total + 1
    WHERE id = NEW.bra_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `brands_total_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    IF OLD.bra_id != NEW.bra_id THEN
        UPDATE brands
        SET total = total - 1
        WHERE id = OLD.bra_id;

        UPDATE brands
        SET total = total + 1
        WHERE id = NEW.bra_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_total_delete` AFTER DELETE ON `products` FOR EACH ROW BEGIN
    UPDATE categories
    SET total = total - 1
    WHERE id = OLD.cat_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_total_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    UPDATE categories
    SET total = total + 1
    WHERE id = NEW.cat_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_total_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    IF OLD.cat_id != NEW.cat_id THEN
        UPDATE categories
        SET total = total - 1
        WHERE id = OLD.cat_id;

        UPDATE categories
        SET total = total + 1
        WHERE id = NEW.cat_id;
    END IF;
END
$$
DELIMITER ;
CREATE TABLE `Product_Views` (
`id` int(11)
,`cat_id` int(11)
,`bra_id` int(11)
,`name` varchar(255)
,`url` varchar(255)
,`price` decimal(10,2)
,`vat` int(11)
,`stock_code` varchar(255)
,`stock_quantity` int(11)
,`category_name` varchar(255)
,`categories_url` varchar(255)
,`brand_name` varchar(255)
,`brand_url` varchar(255)
);
DROP TABLE IF EXISTS `Product_Views`;

CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW Product_Views  AS SELECT products.id AS `id`, products.cat_id AS `cat_id`, products.bra_id AS `bra_id`, products.`name` AS `name`, products.url AS `url`, products.price AS `price`, products.vat AS `vat`, products.stock_code AS `stock_code`, products.stock_quantity AS `stock_quantity`, categories.`name` AS `category_name`, categories.url AS `categories_url`, brands.`name` AS `brand_name`, brands.url AS `brand_url` FROM ((products left join categories on((products.cat_id = categories.id))) left join brands on((products.bra_id = brands.id))) ;


ALTER TABLE brands
  ADD PRIMARY KEY (id);

ALTER TABLE categories
  ADD PRIMARY KEY (id);

ALTER TABLE products
  ADD PRIMARY KEY (id),
  ADD KEY cat_id (cat_id),
  ADD KEY bra_id (bra_id);


ALTER TABLE brands
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

ALTER TABLE categories
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE products
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
