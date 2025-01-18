CREATE TABLE `transactions` (
                                `id` varchar(255) NOT NULL,
                                `customer_id` varchar(255) NOT NULL,
                                `product` varchar(255) NOT NULL,
                                `amount` varchar(255) NOT NULL,
                                `currency` varchar(255) NOT NULL,
                                `status` varchar(255) NOT NULL,
                                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `transactions`
    ADD PRIMARY KEY (`id`);
COMMIT ;
