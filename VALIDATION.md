# Фактическая проверка практической работы

Проверено 7 октября 2026 года на Windows, Docker Desktop с Linux containers.

## Сборка и запуск

- `docker compose config --quiet` завершился успешно.
- `docker compose up -d --build --wait --wait-timeout 240` собрал PHP и запустил три сервиса.
- `db` имеет статус `healthy`; `php` и `nginx` работают.
- nginx доступен на `127.0.0.1:8080`, MySQL — на `127.0.0.1:3306`.
- Проверка `php -l /var/www/html/index.php` прошла без ошибок.
- HTTP-запрос к `http://localhost:8080/` вернул 200.
- В браузере отображаются время из MySQL, hostname контейнера, PHP 8.2.34, PDO-драйвер `mysql`, база `lab`, пользователь `student` и хост `db`.
- Переменные `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` поступают в PHP через Compose.

## Эксперимент с .dockerignore

Были реально собраны два отдельных образа и выполнен `ls -la /var/www/html` через `docker run --rm`.

| Образ | Файлы в /var/www/html |
|---|---|
| `practice2-no-ignore` | `.git`, `.dockerignore.bak`, `Dockerfile`, `debug.log`, `secret.env`, `index.php` |
| `practice2-with-ignore` | только `index.php` |

Во время первой сборки `.dockerignore` был временно переименован. Перед второй сборкой он восстановлен с правилом `secret.env`. Демонстрационные файлы после проверки удалены из исходников.

## DBeaver и база

DBeaver Community 26.2.2 подключён к локальной базе через MySQL Connector/J 8.2.0. Соединение `Practice2-JDBC`: localhost:3306, база lab, пользователь student, JDBC-свойство `sslMode=REQUIRED`.

`sql/01_tables.sql` открыт в SQL-редакторе и выполнен целиком сочетанием Alt+X. DBeaver показал 5 выполненных запросов и 5 добавленных строк. Затем в том же соединении выполнен `sql/02_check.sql`; доступны вкладки результатов `departments_count` и `students_count`.

Дополнительная проверка через MySQL-клиент контейнера подтвердила:

```text
Tables_in_lab
departments
students

departments_count: 2
students_count: 3

Анна  | Разработка
Иван  | Разработка
Мария | Сети
```

Скриншот DBeaver не получен: средство захвата Windows возвращало тайм-аут. Выполнение SQL и результаты подтверждены доступным текстовым состоянием интерфейса и независимым запросом к базе.

## Git и публикация

- История содержит больше 8 содержательных коммитов по этапам задания.
- `.gitignore` присутствует; `git check-ignore .env` выводит `.env`.
- `git ls-files .env` ничего не выводит: локальный `.env` не опубликован.
- Для повторения работы опубликован `.env.example` с учебными значениями.
- Публичный репозиторий: https://github.com/mkzvcode/docker-git-php-mysql-practice
- Git remote использует SSH: `git@github.com:mkzvcode/docker-git-php-mysql-practice.git`.

В README перед сдачей нужно дописать ФИО и группу.
