USE lab;

CREATE TABLE IF NOT EXISTS departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    department_id INT NOT NULL,
    CONSTRAINT fk_students_department FOREIGN KEY (department_id)
        REFERENCES departments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO departments (id, name) VALUES (1, 'Разработка'), (2, 'Сети')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO students (id, name, department_id)
VALUES (1, 'Анна', 1), (2, 'Иван', 1), (3, 'Мария', 2)
ON DUPLICATE KEY UPDATE name = VALUES(name), department_id = VALUES(department_id);
