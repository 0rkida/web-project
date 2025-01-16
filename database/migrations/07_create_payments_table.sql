CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name varchar(50) DEFAULT NULL,
  customer_email varchar(50) DEFAULT NULL,
  paid_amount float(10,2) NOT NULL,
  paid_amount_currency varchar(10) NOT NULL,
  txn_id varchar(50) NOT NULL,
  payment_status varchar(25) NOT NULL,
  created datetime DEFAULT NULL
)