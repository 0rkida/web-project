CREATE TABLE `customers` (
                             `id` varchar(255) NOT NULL,
                             `first_name` varchar(255) NOT NULL,
                             `last_name` varchar(255) NOT NULL,
                             `email` varchar(255) NOT NULL,
                             `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `customers`
    ADD PRIMARY KEY (`id`);
COMMIT ;

