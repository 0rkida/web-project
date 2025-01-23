DROP TABLE IF EXISTS `stripe_cards`;

CREATE TABLE `stripe_cards` (
                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                `user_id` int(10) unsigned NOT NULL,
                                `cardholder_name` varchar(150) NOT NULL,
                                `active` varchar(5) NOT NULL,
                                `payment_method` varchar(255) NOT NULL,
                                `card_country` varchar(50) NOT NULL,
                                `card_brand` varchar(50) NOT NULL,
                                `card_last4` varchar(10) NOT NULL,
                                `card_exp_month` varchar(10) NOT NULL,
                                `card_exp_year` varchar(10) NOT NULL,
                                `default_card` varchar(5) NOT NULL,
                                `testing` varchar(5) NOT NULL,
                                `ip` varchar(20) NOT NULL,
                                `created_at` datetime NOT NULL,
                                `updated_at` datetime NOT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

