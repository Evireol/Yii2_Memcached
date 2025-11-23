Проверка Memcache
-------------------
(Кэширование на 30 секунд)
~~~
http://localhost:8080/index.php?r=cache
~~~

<img width="677" height="126" alt="image" src="https://github.com/user-attachments/assets/bb3e9f62-9ca0-4487-a542-41a4567b1231" />



Обращене по API к списку дел
-------------------

### Отправка в список

~~~
PS \yii2\yii2app> curl.exe -X POST http://localhost:8080/index.php?r=api/add-todo -d "text=Купить хлеб"
{"success":true,"task":{"id":1763858311,"text":"Купить хлеб","created_at":"2025-11-23 00:38:31"}}
~~~

<img width="1081" height="42" alt="image" src="https://github.com/user-attachments/assets/3e614e06-3645-491c-a100-f8baa16c225d" />


### Получение списка всех задач

~~~
http://localhost:8080/index.php?r=api/list
~~~

~~~
[{"id":1763858311,"text":"Купить хлеб","created_at":"2025-11-23 00:38:31"},{"id":1763898846,"text":"заправить машину","created_at":"2025-11-23 11:54:06"}]
~~~

### Получение конкретной задачи

Создание
~~~
PS \yii2\yii2app> curl.exe -X POST http://localhost:8080/index.php?r=api/add-todo -d "text=забрать документы"
{"success":true,"task":{"id":1763902144,"text":"забрать документы","created_at":"2025-11-23 12:49:04"}}
~~~

Отображение
~~~
http://localhost:8080/index.php?r=api/view&id=1763902144
~~~

Найдено по запросу:
~~~
{"id":1763902144,"text":"забрать документы","created_at":"2025-11-23 12:49:04"}
~~~


### Изменение статуса задачи

~~~
PS \yii2\yii2app> curl.exe -X POST "http://localhost:8080/index.php?r=api/update-status&id=1763902892" -d "status=completed"
~~~

### Удаление задачи

~~~
PS \yii2\yii2app> curl.exe -X delete "http://localhost:8080/index.php?r=api/delete&id=1763898846"
~~~


Коннект с Базой данных (MySQL)
-------------------

### Создание миграции
~~~
PS \compose\yii2> docker exec -it yii2_app php yii migrate
Yii Migration Tool (based on Yii v2.0.53)

Creating migration history table "migration"...Done.
No new migrations found. Your system is up-to-date.
~~~
~~~
PS \compose\yii2> docker exec -it yii2_app php yii migrate/create create_todo_table
Yii Migration Tool (based on Yii v2.0.53)

Create new migration '/app/migrations/m251123_172737_create_todo_table.php'? (yes|no) [no]:y
New migration created successfully.
~~~
~~~
PS \compose\yii2> docker exec -it yii2_app php yii migrate
Yii Migration Tool (based on Yii v2.0.53)

Total 1 new migration to be applied:
        m251123_172737_create_todo_table

Apply the above migration? (yes|no) [no]:y
*** applying m251123_172737_create_todo_table
    > create table {{%todo}} ... done (time: 0.087s)
*** applied m251123_172737_create_todo_table (time: 0.551s)
~~~


### Если миграция не создалась (уже есть такая БД):

# Остановить и удалить контейнеры

Очистка:
~~~
docker-compose down
~~~
~~~
docker volume rm yii2_mysql_data
~~~
~~~
docker-compose up -d
~~~

Воссоздание в SQL:
~~~
CREATE DATABASE IF NOT EXISTS testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'testuser'@'%' IDENTIFIED BY 'testpass';

GRANT ALL PRIVILEGES ON testdb.* TO 'testuser'@'%';

-- Применить изменения
FLUSH PRIVILEGES;

~~~

Сохранение в кэш
-------------------

### Проверка источника полученнных данных

~~~
2025-11-23 21:07:57 [172.18.0.1][-][-][info][yii\db\Command::execute] INSERT INTO `todo` (`text`) VALUES ('сгрупировать задачи')
    in /app/controllers/ApiController.php:34

2025-11-23 21:08:01 [172.18.0.1][-][-][info][app\controllers\ApiController::actionList] Cache MISS: loading from DB
    in /app/controllers/ApiController.php:48

2025-11-23 21:08:03 [172.18.0.1][-][-][info][app\controllers\ApiController::actionList] Cache HIT: loaded from cache
    in /app/controllers/ApiController.php:52
~~~