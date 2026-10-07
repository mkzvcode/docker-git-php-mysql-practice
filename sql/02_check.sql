USE lab;
SHOW TABLES;
SELECT COUNT(*) AS departments_count FROM departments;
SELECT COUNT(*) AS students_count FROM students;
SELECT s.id, s.name AS student, d.name AS department
FROM students s
JOIN departments d ON d.id = s.department_id
ORDER BY s.id;
