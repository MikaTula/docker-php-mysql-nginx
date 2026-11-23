<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>docker-php-mysql-nginx</title>
    <style>
        body { font-family: sans-serif; margin: 2rem auto; max-width: 46rem; line-height: 1.45; }
        code, pre { background: #f2f2f2; }
        code { padding: 0 .25rem; }
        pre { padding: .75rem 1rem; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
        td, th { border-bottom: 1px solid #ddd; text-align: left; padding: .4rem .5rem; vertical-align: top; }
        a { color: #0842a0; }
    </style>
</head>
<body>
    <p><a href="/examples/">К примерам</a></p>
    <h1>docker-php-mysql-nginx</h1>
    <p>Локальное окружение для PHP-проекта: Nginx, PHP-FPM, MySQL, Redis и служебные контейнеры. Исходники лежат на хосте и монтируются в контейнеры, отдельно публиковать код не нужно.</p>
    <p>Команды ниже запускаются из каталога <code>docker</code>.</p>

    <h2>Состав</h2>
    <table>
        <tr><th>Сервис</th><th>Контейнер</th><th>Образ</th><th>Назначение</th></tr>
        <tr><td><code>cm-gateway</code></td><td><code>docker-php-mysql-nginx-cm-gateway-1</code></td><td>Nginx 1.30.7</td><td>Принимает HTTP и отдаёт PHP в FPM</td></tr>
        <tr><td><code>cm-api-server</code></td><td><code>docker-php-mysql-nginx-cm-api-server-1</code></td><td>PHP 8.5.13-FPM</td><td>Код из <code>api-server</code></td></tr>
        <tr><td><code>cm-database</code></td><td><code>docker-php-mysql-nginx-cm-database-1</code></td><td>MySQL 9.7.4</td><td>База <code>cm_api_server</code></td></tr>
        <tr><td><code>cm-redis</code></td><td><code>docker-php-mysql-nginx-cm-redis-1</code></td><td>Redis 8.10.3</td><td>Кэш и очереди</td></tr>
        <tr><td><code>cm-phpmyadmin</code></td><td><code>docker-php-mysql-nginx-cm-phpmyadmin-1</code></td><td>phpMyAdmin 5.2.3</td><td>Веб-интерфейс MySQL</td></tr>
        <tr><td><code>cm-redis-commander</code></td><td><code>docker-php-mysql-nginx-cm-redis-commander-1</code></td><td>Redis Commander 0.9.1</td><td>Веб-интерфейс Redis, образ с GHCR</td></tr>
        <tr><td><code>cm-mailpit</code></td><td><code>docker-php-mysql-nginx-cm-mailpit-1</code></td><td>Mailpit 1.34.0</td><td>Перехватывает исходящую почту</td></tr>
        <tr><td><code>cm-clear-log</code></td><td><code>docker-php-mysql-nginx-cm-clear-log-1</code></td><td>Alpine 3.22</td><td>При каждом запуске стека удаляет файлы логов</td></tr>
    </table>
    <p>Проект Compose называется <code>docker-php-mysql-nginx</code>. Сеть Docker — <code>docker-php-mysql-nginx_cm-network</code>. Внутри неё сервисы видят друг друга по имени сервиса: <code>cm-api-server</code>, <code>cm-database</code>, <code>cm-redis</code>, <code>cm-mailpit</code>. Тома базы и Redis — <code>docker-php-mysql-nginx_db-mysql</code> и <code>docker-php-mysql-nginx_redis</code>.</p>
    <p>В образ PHP установлены Composer 2.9, расширения <code>gd</code>, <code>mysqli</code>, <code>pdo_mysql</code>, <code>redis</code>, <code>zip</code> и Xdebug. <code>openssl</code>, <code>curl</code>, <code>mbstring</code> и <code>fileinfo</code> уже есть в официальном образе. OPcache выключен, чтобы правки PHP-файлов были видны сразу.</p>
    <p><code>cm-api-server</code> и phpMyAdmin стартуют после того, как MySQL ответит на <code>mysqladmin ping</code>.</p>

    <h2>Первый запуск</h2>
    <p>Нужны Docker и Docker Compose v2.</p>
    <pre>docker compose up -d --build</pre>
    <p>В файл <code>C:\Windows\System32\drivers\etc\hosts</code> добавьте строку (на Linux и macOS это <code>/etc/hosts</code>):</p>
    <pre>127.0.0.1 hello-docker.loc</pre>
    <p>Сайт: <a href="http://hello-docker.loc/">http://hello-docker.loc/</a></p>
    <p>Корень сайта — каталог <code>api-server</code>. Статика клиента доступна по <a href="http://hello-docker.loc/app/">http://hello-docker.loc/app/</a> и берётся из каталога <code>api-client</code>. Если файла нет, Nginx отдаёт <code>api-client/index.html</code>.</p>
    <p>Демонстрация MySQL, SQL и Redis: <a href="/examples/">http://hello-docker.loc/examples/</a>. Эта справка: <a href="/examples/readme.php">http://hello-docker.loc/examples/readme.php</a>. Файлы лежат в каталоге <code>examples</code> и смонтированы в Nginx и PHP как <code>/var/www/examples</code>.</p>
    <p>Остановить стек:</p>
    <pre>docker compose stop</pre>
    <p>Остановить и удалить контейнеры, не трогая данные базы и Redis:</p>
    <pre>docker compose down</pre>

    <h2>Адреса</h2>
    <p>Порт сайта <code>80</code> открыт на всех интерфейсах. Остальные веб-интерфейсы слушаются только на <code>127.0.0.1</code>.</p>
    <table>
        <tr><th>Что</th><th>Адрес</th></tr>
        <tr><td>Сайт</td><td><a href="http://hello-docker.loc/">http://hello-docker.loc/</a></td></tr>
        <tr><td>Клиент</td><td><a href="http://hello-docker.loc/app/">http://hello-docker.loc/app/</a></td></tr>
        <tr><td>Примеры</td><td><a href="http://hello-docker.loc/examples/">http://hello-docker.loc/examples/</a></td></tr>
        <tr><td>Эта справка</td><td><a href="http://hello-docker.loc/examples/readme.php">http://hello-docker.loc/examples/readme.php</a></td></tr>
        <tr><td>Документация API</td><td><a href="http://hello-docker.loc/docs/">http://hello-docker.loc/docs/</a></td></tr>
        <tr><td>Список пользователей</td><td><a href="http://hello-docker.loc/api/users/all">http://hello-docker.loc/api/users/all</a></td></tr>
        <tr><td>phpMyAdmin</td><td><a href="http://hello-docker.loc:8081/" target="_blank" rel="noopener">http://hello-docker.loc:8081/</a></td></tr>
        <tr><td>Redis Commander</td><td><a href="http://hello-docker.loc:8082/" target="_blank" rel="noopener">http://hello-docker.loc:8082/</a></td></tr>
        <tr><td>Mailpit</td><td><a href="http://hello-docker.loc:8025/" target="_blank" rel="noopener">http://hello-docker.loc:8025/</a></td></tr>
    </table>
    <p>PHP из контейнера отправляет почту на <code>cm-mailpit:1025</code>. С хоста SMTP Mailpit доступен как <code>hello-docker.loc:1025</code>.</p>
    <p>MySQL и Redis на хост не проброшены. Из PHP к ним подключаются так:</p>
    <ul>
        <li>MySQL: хост <code>cm-database</code>, порт <code>3306</code>, база <code>cm_api_server</code>, пользователь <code>cm_db_user</code>, пароль <code>secret</code></li>
        <li>Redis: хост <code>cm-redis</code>, порт <code>6379</code>, без пароля</li>
    </ul>
    <p>У пользователя <code>root</code> в MySQL пароль пустой. Это только для локального стенда. В phpMyAdmin сервер уже указан (<code>cm-database</code>), вход — пользователь <code>root</code> с пустым паролем.</p>

    <h2>Каталоги</h2>
    <table>
        <tr><th>Путь на хосте</th><th>Куда смонтирован</th></tr>
        <tr><td><code>api-server</code></td><td><code>/var/www/api-server</code> в Nginx и PHP</td></tr>
        <tr><td><code>api-client</code></td><td><code>/var/www/api-client</code> в Nginx</td></tr>
        <tr><td><code>examples</code></td><td><code>/var/www/examples</code> в Nginx и PHP</td></tr>
        <tr><td><code>api-docs/dist</code></td><td><code>/var/www/api-docs</code> в Nginx</td></tr>
        <tr><td><code>logs/nginx</code></td><td>журналы Nginx</td></tr>
        <tr><td><code>logs/api-server</code></td><td>журнал PHP <code>error.log</code></td></tr>
        <tr><td>volume <code>docker-php-mysql-nginx_db-mysql</code></td><td>данные MySQL</td></tr>
        <tr><td>volume <code>docker-php-mysql-nginx_redis</code></td><td>данные Redis</td></tr>
    </table>
    <p>Конфиг виртуального хоста: <code>docker/images/nginx/hosts/hello-docker.loc.conf</code>. Он смонтирован в контейнер, после правки достаточно перезапустить шлюз:</p>
    <pre>docker compose restart cm-gateway</pre>
    <p>Настройки PHP: <code>docker/images/api-server/custom.ini</code>. Xdebug: <code>docker/images/api-server/docker-php-ext-xdebug.ini</code>. Эти файлы попадают в образ при сборке, поэтому после их изменения образ API нужно пересобрать:</p>
    <pre>docker compose up -d --build cm-api-server</pre>

    <h2>Xdebug</h2>
    <p>Xdebug включён в режиме <code>debug</code> и стартует по триггеру, а не на каждый запрос. IDE слушает порт <code>9003</code>. В браузере нужен параметр или расширение, которое отправляет cookie <code>XDEBUG_TRIGGER</code>. Адрес IDE из контейнера — <code>host.docker.internal</code>.</p>

    <h2>Установка</h2>
    <p>Нужны Docker, Docker Compose v2 и Node.js 22.</p>
    <p>Стек, из каталога <code>docker</code>:</p>
    <pre>docker compose up -d --build</pre>
    <p>Справочник API. Каталог <code>api-docs/dist</code> в git не входит. Из каталога <code>api-docs</code>:</p>
    <pre>npm ci
npm run build</pre>
    <p>Страница: <a href="http://hello-docker.loc/docs/">http://hello-docker.loc/docs/</a>. Запросы из справочника идут в <code>api-server/users-api-example.php</code>: список, чтение, создание, изменение и удаление. Данные лежат в PHP-сессии; при первом запросе скрипт записывает пять пользователей. Если шлюз уже работал до появления этого каталога, из <code>docker</code> один раз выполните <code>docker compose up -d cm-gateway</code>.</p>

    <h2>Composer</h2>
    <p>Зависимости описаны в <code>api-server/composer.json</code> (PHP 8.5, <code>chrome-php/chrome</code> 1.16, Guzzle 7). Composer стоит в контейнере API. Каталог <code>api-server</code> смонтирован как рабочий, поэтому lock обновляется так:</p>
    <pre>docker compose exec cm-api-server composer update</pre>
    <p><code>composer.lock</code> должен совпадать с <code>composer.json</code>. Сейчас lock ещё от старых пакетов: его стоит пересобрать этой командой после первой сборки образа.</p>

    <h2>Логи</h2>
    <p><code>cm-clear-log</code> входит в обычный <code>docker compose up</code>. Контейнер один раз удаляет <code>logs/api-server/error.log</code>, <code>logs/nginx/error.log</code> и <code>logs/nginx/access.log</code> и завершается. При следующем <code>up</code> Compose запускает его снова, и логи стираются заново.</p>
    <p>В <code>logs/nginx/error.log</code> при старте попадают служебные строки уровня <code>notice</code> (версия, рабочие процессы). Это не ошибки запросов. Запросы пишутся в <code>logs/nginx/access.log</code>.</p>

    <h2>Полный сброс</h2>
    <p><code>docker/reinit.ps1</code> останавливает контейнеры, если они запущены, удаляет их вместе с томами MySQL и Redis, стирает файлы в <code>logs</code> и снова поднимает стек в фоне со сборкой образов. Код в <code>api-server</code> и <code>api-client</code> не трогает.</p>
    <p>Из каталога <code>docker</code>:</p>
    <pre>.\reinit.ps1</pre>
    <p>Это тот же сценарий, что нужен после смены мажорной версии MySQL: старый каталог данных в volume несовместим с новым сервером. Если данные базы ещё нужны, сделайте дамп до запуска скрипта.</p>

    <h2>Что делает страница примеров</h2>
    <ul>
        <li><strong>MySQL.</strong> Форма пишет строки в таблицу <code>demo_notes</code>. Таблица создаётся при первом открытии <a href="/examples/">/examples/</a>.</li>
        <li><strong>Redis.</strong> Каждый заход увеличивает ключ <code>examples:visits</code>. Сохранённая заметка кладётся в ключ <code>examples:last_note</code>. В Redis Commander это папка <code>examples</code>.</li>
        <li><strong>Почта.</strong> Форма отправляет письмо в Mailpit. Тема и текст уже заполнены, их можно поменять.</li>
    </ul>
</body>
</html>
