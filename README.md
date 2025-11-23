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

