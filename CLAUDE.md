# Правила проекта: музыкальный сервис (учебный)

Краткий контекст для ассистентов и разработчиков. Основной источник соглашений — существующий код в `api-server/` (Laravel). Клиент `api-client/` (Angular) пока не развёрнут; при его добавлении ориентироваться на контракт API ниже.

## Структура репозитория

| Путь | Назначение |
|------|------------|
| `api-server/` | Backend: Laravel 12, PHP 8.2+, REST API + Inertia/Vue для веб-части starter-kit |
| `api-client/` | Заготовка фронтенда (план: Angular) |
| `docker/` | Docker Compose: nginx, PHP-FPM, MySQL, прочие сервисы; тома монтируют `api-server` и `api-client` |

Базовый URL API в документации: `http://api.music.local/api` (см. `api-server/public/openapi.yaml`).

## Стек backend

- **Laravel** 12, **Sanctum** — токены для API (`createToken`, `auth:sanctum`).
- **Fortify** — веб-аутентификация и настройки профиля (не путать с кастомными эндпоинтами API).
- **Laravel Pint** — стиль кода, пресет `laravel` (`api-server/pint.json`).
- **Тесты**: PHPUnit 11; `composer test` в `api-server` запускает Pint в режиме проверки и `php artisan test` (SQLite in-memory в `phpunit.xml`).

Перед коммитом в backend: `composer test` или как минимум `composer run test:lint` и тесты по затронутым областям.

## Требования Laravel Pint
При любых правках целимся, чтобы `php vendor/bin/pint` проходил (в частности, следим за правилами, которые приходилось исправлять в рамках этого диалога):
- не допускать лишних пустых строк в конце файла (`single_blank_line_at_eof`)
- не оставлять лишние пустые строки перед statement (`blank_line_before_statement`)
- не оставлять пустые или лишние PHPDoc-теги (`no_empty_phpdoc`, `no_superfluous_phpdoc_tags`)
- соблюдать разделение атрибутов класса (`class_attributes_separation`)

## Маршруты API

- Файл: `api-server/routes/api.php` (префикс `/api` задаётся Laravel по умолчанию).
- Публичные эндпоинты (без `auth:sanctum`): `POST custom-register`, `POST custom-login` — кастомная регистрация и вход с выдачей токена Sanctum.
- Защищённые (`middleware('auth:sanctum')`): ресурс `songs`, `GET user` (текущий пользователь).

Имена в коде — источник истины. В `openapi.yaml` пути могут отличаться (например, `/register` vs `custom-register`); при изменении API обновляйте и маршруты, и OpenAPI, чтобы они совпадали.

## Слои и зависимости

Предпочтительная цепочка для доменной логики:

1. **HTTP** — `App\Http\Controllers\API\*`, наследуют `BaseController` там, где используются `sendResponse` / `sendError`.
2. **Валидация** — `FormRequest` (например `PaginationRequest` для списков); для простых кейсов допустим `Validator::make` в контроллере (как в `RegisterController`).
3. **Сервисы** — интерфейс в `App\Services\`, реализация `readonly class`, привязка через атрибут `#[Bind(Implementation::class)]` на интерфейсе (см. `SongServiceInterface`).
4. **Репозитории** — интерфейс + реализация в `App\Repositories\`, также `#[Bind]` на интерфейсе.
5. **Доменные модели и перечисления** — `App\Domain\`: `PaginationInModel` / `PaginationOutModel`, `SongSortByEnum`, `SortOrderEnum`, модели вроде `SongModel` (не путать с Eloquent `App\Models\Song`).
6. **Мапперы** — `App\Mappers\`: преобразование запросов в доменные DTO (`PaginationMapper::mapFromRequest`) и сущностей БД в доменные модели (`SongMapper::mapFromListDB` и т.д.).

Контроллеры не должны раздуваться бизнес-логикой: списки и правила сортировки — через сервис и репозиторий.

## Форматы JSON-ответов

В проекте используются два близких стиля — не смешивать их в одном эндпоинте без необходимости:

- **Успех / ошибка валидации в стиле `BaseController`**: `success`, `message`, поле `data` (успех) или структура ошибок при `sendError`.
- **Глобальный обработчик исключений** (`App\Exceptions\ApiExceptionHandler`) и трейт `App\Traits\ApiResponses`: ответы с полями вроде `errors` (массив объектов с `status`, `message`, `source`) и HTTP-кодом.

Новые API-методы, проходящие через `BaseController`, должны сохранять формат `sendResponse` / `sendError` для согласованности с OpenAPI и существующими клиентами.

## Исключения и ошибки

Централизованная обработка в `bootstrap/app.php` через `ApiExceptionHandler`: валидация (422), модель не найдена (404), неаутентифицирован (401), прочее (500) с JSON. Не подавляйте исключения там, где ожидается единый JSON-формат.

## Доменные перечисления

Сортировка и подобные вещи — через backed enums (`SongSortByEnum`, `SortOrderEnum`). Значения должны соответствовать реальным колонкам/контракту репозитория.

## Ресурсы и сериализация

Для отдельных сущностей в ответах API используется `Illuminate\Http\Resources\Json\JsonResource` (пример: `SongResource`). Списки с пагинацией возвращают `PaginationOutModel` через `sendResponse` после маппинга предметов в доменные модели там, где это уже принято (`index` у песен).

## Безопасность и окружение

- Секреты только через `.env`, не коммитить.
- В production включены строгие правила паролей (`AppServiceProvider::configureDefaults`).
- Деструктивные команды Artisan в production запрещены через `DB::prohibitDestructiveCommands`.

## Логирование SQL (отладка)

Опционально: `DB_LOG_ACTIVE=true` в `.env` пишет запросы в `storage/logs/db-query.log`. Не включать на продакшене без необходимости.

## Документация API

Поддерживайте актуальность `api-server/public/openapi.yaml` при изменении контрактов (тела запросов, коды ответов, схемы успеха/ошибки).

## Клиент Angular (`api-client`)

Когда появится код: использовать тот же базовый URL API, заголовок `Authorization: Bearer <token>` для защищённых маршрутов, учитывать форматы ответов `BaseController` и ошибок из обработчика исключений.

## Краткий чеклист для новых фич backend

1. Маршруты в `routes/api.php`, middleware Sanctum по необходимости.
2. Контроллер API + при необходимости `FormRequest` и политики доступа.
3. Сервис и репозиторий с интерфейсами и `#[Bind]`.
4. Доменные типы и мапперы; enums для фиксированных наборов значений.
5. Ресурс или согласованная структура `sendResponse`.
6. Обновление `openapi.yaml`.
7. Тесты Feature/Unit и `composer test`.
