DROP TABLE IF EXISTS `transactions_details`;

CREATE TABLE `transactions_details` (
                                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                        `user_id` int(10) unsigned NOT NULL,
                                        `payment_method` varchar(255) NOT NULL,
                                        `payment_intent_id` varchar(255) NOT NULL,
                                        `transaction_id` varchar(255) NOT NULL,
                                        `charge_id` varchar(255) NOT NULL,
                                        `amount` varchar(50) NOT NULL,
                                        `currency` varchar(50) NOT NULL,
                                        `converted_amount` varchar(50) NOT NULL,
                                        `converted_currency` varchar(50) NOT NULL,
                                        `balance_description` varchar(100) NOT NULL,
                                        `exchange_rate` varchar(50) NOT NULL,
                                        `available_on` varchar(50) NOT NULL,
                                        `payment_fee` varchar(50) NOT NULL,
                                        `payment_net` varchar(50) NOT NULL,
                                        `status` varchar(10) NOT NULL,
                                        `created_at` datetime NOT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
