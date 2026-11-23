# docker-php-mysql-nginx

Локальное окружение для PHP-проекта: Nginx, PHP-FPM, MySQL, Redis и служебные контейнеры. Исходники лежат на хосте и монтируются в контейнеры, отдельно публиковать код не нужно.

Команды ниже запускаются из каталога `docker`.

## Состав

| Сервис | Контейнер | Образ | Назначение |
|---|---|---|---|
| `cm-gateway` | `docker-php-mysql-nginx-cm-gateway-1` | Nginx 1.30.7 | Принимает HTTP и отдаёт PHP в FPM |
| `cm-api-server` | `docker-php-mysql-nginx-cm-api-server-1` | PHP 8.5.13-FPM | Код из `api-server` |
| `cm-database` | `docker-php-mysql-nginx-cm-database-1` | MySQL 9.7.4 | База `cm_api_server` |
| `cm-redis` | `docker-php-mysql-nginx-cm-redis-1` | Redis 8.10.3 | Кэш и очереди |
| `cm-phpmyadmin` | `docker-php-mysql-nginx-cm-phpmyadmin-1` | phpMyAdmin 5.2.3 | Веб-интерфейс MySQL |
| `cm-redis-commander` | `docker-php-mysql-nginx-cm-redis-commander-1` | Redis Commander 0.9.1 | Веб-интерфейс Redis, образ с GHCR |
| `cm-mailpit` | `docker-php-mysql-nginx-cm-mailpit-1` | Mailpit 1.34.0 | Перехватывает исходящую почту |
| `cm-clear-log` | `docker-php-mysql-nginx-cm-clear-log-1` | Alpine 3.22 | При каждом запуске стека удаляет файлы логов |

Проект Compose называется `docker-php-mysql-nginx`. Сеть Docker — `docker-php-mysql-nginx_cm-network`. Внутри неё сервисы видят друг друга по имени сервиса: `cm-api-server`, `cm-database`, `cm-redis`, `cm-mailpit`. Тома базы и Redis — `docker-php-mysql-nginx_db-mysql` и `docker-php-mysql-nginx_redis`.

В образ PHP установлены Composer 2.9, расширения `gd`, `mysqli`, `pdo_mysql`, `redis`, `zip` и Xdebug. `openssl`, `curl`, `mbstring` и `fileinfo` уже есть в официальном образе. OPcache выключен, чтобы правки PHP-файлов были видны сразу.

`cm-api-server` и phpMyAdmin стартуют после того, как MySQL ответит на `mysqladmin ping`.

## Установка

Нужны Docker, Docker Compose v2 и Node.js 22. Команды Docker запускаются из каталога `docker`.

Поднять стек:

```bash
docker compose up -d --build
```

Справочник API. Каталог `api-docs/dist` в git не входит. Из каталога `api-docs`:

```bash
npm ci
npm run build
```

`npm ci` ставит `@scalar/api-reference` и Vite по `package-lock.json`. `npm run build` пишет статику в `api-docs/dist`. Страница — http://hello-docker.loc/docs/ . Запросы из справочника идут в `api-server/users-api-example.php`: список, чтение, создание, изменение и удаление. Данные лежат в PHP-сессии; при первом запросе скрипт записывает пять пользователей. Если шлюз уже был запущен до появления этого тома, из каталога `docker` один раз выполните `docker compose up -d cm-gateway`. Дальше новые файлы в `dist` видны сразу.

В файл `C:\Windows\System32\drivers\etc\hosts` добавьте строку (на Linux и macOS это `/etc/hosts`):

```
127.0.0.1 hello-docker.loc
```

Адреса страниц собраны ниже и на http://hello-docker.loc/examples/readme.php .

## Первый запуск

Сайт: http://hello-docker.loc/

Корень сайта — каталог `api-server`. Статика клиента доступна по http://hello-docker.loc/app/ и берётся из каталога `api-client`. Если файла нет, Nginx отдаёт `api-client/index.html`.

Демонстрация MySQL, SQL и Redis: http://hello-docker.loc/examples/ . Краткая справка для браузера: http://hello-docker.loc/examples/readme.php . Файлы лежат в каталоге `examples` и смонтированы в Nginx и PHP как `/var/www/examples`.

Справочник API: http://hello-docker.loc/docs/ .

Остановить стек:

```bash
docker compose stop
```

Остановить и удалить контейнеры, не трогая данные базы и Redis:

```bash
docker compose down
```

## Адреса

Порт сайта `80` открыт на всех интерфейсах. Остальные веб-интерфейсы слушаются только на `127.0.0.1`.

| Что | Адрес |
|---|---|
| Сайт | http://hello-docker.loc/ |
| Клиент | http://hello-docker.loc/app/ |
| Примеры | http://hello-docker.loc/examples/ |
| Справка | http://hello-docker.loc/examples/readme.php |
| Документация API | http://hello-docker.loc/docs/ |
| Список пользователей | http://hello-docker.loc/api/users/all |
| phpMyAdmin | http://127.0.0.1:8081/ |
| Redis Commander | http://127.0.0.1:8082/ |
| Mailpit | http://127.0.0.1:8025/ |

PHP из контейнера отправляет почту на `cm-mailpit:1025`. С хоста SMTP Mailpit доступен как `127.0.0.1:1025`.

MySQL и Redis на хост не проброшены. Из PHP к ним подключаются так:

- MySQL: хост `cm-database`, порт `3306`, база `cm_api_server`, пользователь `cm_db_user`, пароль `secret`
- Redis: хост `cm-redis`, порт `6379`, без пароля

У пользователя `root` в MySQL пароль пустой. Это только для локального стенда. В phpMyAdmin сервер уже указан (`cm-database`), вход — пользователь `root` с пустым паролем.

## Каталоги

| Путь на хосте | Куда смонтирован |
|---|---|
| `api-server` | `/var/www/api-server` в Nginx и PHP |
| `api-client` | `/var/www/api-client` в Nginx |
| `examples` | `/var/www/examples` в Nginx и PHP |
| `api-docs/dist` | `/var/www/api-docs` в Nginx |
| `logs/nginx` | журналы Nginx |
| `logs/api-server` | журнал PHP `error.log` |
| volume `docker-php-mysql-nginx_db-mysql` | данные MySQL |
| volume `docker-php-mysql-nginx_redis` | данные Redis |

Конфиг виртуального хоста: `docker/images/nginx/hosts/hello-docker.loc.conf`. Он смонтирован в контейнер, после правки достаточно перезапустить шлюз:

```bash
docker compose restart cm-gateway
```

Настройки PHP: `docker/images/api-server/custom.ini`. Xdebug: `docker/images/api-server/docker-php-ext-xdebug.ini`. Эти файлы попадают в образ при сборке, поэтому после их изменения образ API нужно пересобрать:

```bash
docker compose up -d --build cm-api-server
```

## Xdebug

Xdebug включён в режиме `debug` и стартует по триггеру, а не на каждый запрос. IDE слушает порт `9003`. В браузере нужен параметр или расширение, которое отправляет cookie `XDEBUG_TRIGGER`. Адрес IDE из контейнера — `host.docker.internal`.

## Composer

Зависимости описаны в `api-server/composer.json` (PHP 8.5, `chrome-php/chrome` 1.16, Guzzle 7). Composer стоит в контейнере API. Каталог `api-server` смонтирован как рабочий, поэтому lock обновляется так:

```bash
docker compose exec cm-api-server composer update
```

`composer.lock` должен совпадать с `composer.json`. Сейчас lock ещё от старых пакетов: его стоит пересобрать этой командой после первой сборки образа.

## Логи

`cm-clear-log` входит в обычный `docker compose up`. Контейнер один раз удаляет `logs/api-server/error.log`, `logs/nginx/error.log` и `logs/nginx/access.log` и завершается. При следующем `up` Compose запускает его снова, и логи стираются заново.

## Полный сброс

`docker/reinit.ps1` останавливает контейнеры, если они запущены, удаляет их вместе с томами MySQL и Redis, стирает файлы в `logs` и снова поднимает стек в фоне со сборкой образов. Код в `api-server` и `api-client` не трогает.

Из каталога `docker`:

```powershell
.\reinit.ps1
```

Это тот же сценарий, что нужен после смены мажорной версии MySQL: старый каталог данных в volume несовместим с новым сервером. Если данные базы ещё нужны, сделайте дамп до запуска скрипта.
