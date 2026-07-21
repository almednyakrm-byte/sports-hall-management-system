CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
);

CREATE TABLE memberships (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  membership_plan VARCHAR(255) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  booking_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id)
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  payment_method VARCHAR(255) NOT NULL,
  payment_date DATE NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
  ('user1', 'user1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'user'),
  ('guest1', 'guest1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'guest');

INSERT INTO memberships (user_id, membership_plan, start_date, end_date) VALUES
  (1, 'Monthly', '2022-01-01', '2022-01-31'),
  (2, 'Yearly', '2022-01-01', '2023-01-01'),
  (3, 'Daily', '2022-01-01', '2022-01-01');

INSERT INTO bookings (user_id, booking_date, start_time, end_time) VALUES
  (1, '2022-01-01', '10:00:00', '12:00:00'),
  (2, '2022-01-02', '14:00:00', '16:00:00'),
  (3, '2022-01-03', '10:00:00', '11:00:00');

INSERT INTO payments (user_id, payment_method, payment_date, amount) VALUES
  (1, 'Credit Card', '2022-01-01', 100.00),
  (2, 'PayPal', '2022-01-02', 200.00),
  (3, 'Cash', '2022-01-03', 50.00);