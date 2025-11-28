CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(255) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE books (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(255) NOT NULL,
 author VARCHAR(255) NOT NULL,
 category VARCHAR(100) NOT NULL,
 isbn VARCHAR(20) NOT NULL,
 shelf_location VARCHAR(50),
 total_copies INT NOT NULL DEFAULT 1,
 available_copies INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE issued_books (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 book_id INT NOT NULL,
 issue_date DATE NOT NULL,
 due_date DATE NOT NULL,
 return_date DATE NULL,
 status ENUM('issued','returned','reserved') NOT NULL DEFAULT 'issued',
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (book_id) REFERENCES books(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

