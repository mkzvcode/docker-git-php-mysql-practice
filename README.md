# Практическая работа Docker PHP MySQL Nginx и Git

Автор: mkzvcode. Практическая работа №4 из документа «Практика 2».

Проект демонстрирует сборку PHP-FPM, связь с nginx и MySQL, исключение файлов через `.dockerignore`, переменные окружения и работу с базой через DBeaver. ФИО и группа не предоставлены; перед сдачей укажите свои данные.

## Запуск

Нужны Git, Docker Desktop с Linux containers и Docker Compose.

```powershell
git clone git@github.com:mkzvcode/docker-git-php-mysql-practice.git
cd docker-git-php-mysql-practice
Copy-Item .env.example .env
docker compose up -d --build --wait --wait-timeout 240
docker compose ps
```

В Bash вместо `Copy-Item` используйте `cp .env.example .env`.

Сайт: http://localhost:8080/. MySQL для DBeaver: localhost:3306. База `lab`, пользователь `student`, учебный пароль `student_pass`. Настройки находятся в локальном `.env`; он исключён из Git. `.env.example` содержит только учебные значения для повторения работы.

Внутри сети Compose PHP обращается к `db:3306`, nginx передаёт запросы на `php:9000`. Данные БД хранятся в томе `db_data`. Сервисы этой практики отдельны от «Зоопарка» на портах 8000 и 3307.

## Проверки

```powershell
git status
git log --oneline
git rev-list --count HEAD
git check-ignore .env
git ls-files .env
docker compose config --quiet
docker compose exec php php -l /var/www/html/index.php
docker compose exec php sh -c 'env | grep "^DB_"'
docker compose exec db mysql -u student -pstudent_pass lab -e "SELECT NOW(); SHOW TABLES;"
```

`git ls-files .env` не должен выводить файл. В истории больше восьми содержательных коммитов: инициализация, PHP, Dockerfile, dockerignore, nginx, Compose, эксперимент, шаблон окружения, вынос настроек и SQL-скрипты.

## Эксперимент с dockerignore

Команды выполняются из корня проекта в Git Bash. Используйте только демонстрационные данные:

```bash
echo "only-demo-secret" > app/secret.env
echo "demo-log" > app/debug.log
mkdir -p app/.git
echo "demo-git-config" > app/.git/config
mv app/.dockerignore app/.dockerignore.bak
docker build -t practice2-no-ignore ./app
docker run --rm practice2-no-ignore ls -la /var/www/html
mv app/.dockerignore.bak app/.dockerignore
docker build -t practice2-with-ignore ./app
docker run --rm practice2-with-ignore ls -la /var/www/html
```

Первый образ содержит `.git`, `debug.log`, `secret.env` и служебные файлы; второй содержит только `index.php`. Правило `secret.env` добавлено в `.dockerignore` после первой сборки по заданию. В текущем проекте тестовые файлы удалены. Проверяйте именно отдельные образы через `docker run`: bind mount `./app` в Compose показывает файлы хоста и не доказывает работу `.dockerignore`.

## DBeaver и SQL

Создайте соединение MySQL: localhost, порт 3306, база lab, пользователь student, пароль из `.env`. Для учебного сервера можно использовать JDBC-параметр `sslMode=REQUIRED`, чтобы соединяться по TLS без разрешения получения открытого ключа через незащищённый канал.

Откройте `sql/01_tables.sql` в SQL-редакторе этого соединения и выполните скрипт целиком. Создаются две связанные таблицы `departments` и `students`, 2 отделения и 3 студента. Затем выполните `sql/02_check.sql`, чтобы увидеть количество строк и результат JOIN. Скрипт создания можно выполнить повторно.

Коммитятся SQL-скрипты, а не содержимое Docker-тома. Они позволяют одногруппникам повторить задание на своей базе.

## Сдача

Покажите `git log --oneline`, команды эксперимента и списки файлов обоих образов, `docker compose ps`, страницу в браузере, настройки окружения и результаты SQL в DBeaver. Фактические результаты проверки записаны в `VALIDATION.md`.

## Остановка

```powershell
docker compose down
```

Данные сохраняются. `docker compose down -v` удаляет базу и используется только для полного сброса.
