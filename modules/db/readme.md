##Назначение

Класс для работы с базой данных Mysql

##Методы

###changeconnect

Подключение к новой базе используя свои настройки

    $sl->db->changeconnect($newconnect = [],$show_error = true)

- $newconnect - Массив с данными для подключения
- $show_error - Показывать ошибку

#####Пример

    <?
    $sl->db->changeconnect([
        'ip'=>'localhost',
        'user'=>'root',
        'pass'=>'',
        'dbname'=>'test'
    ]);
    ?>

###backconnect

Вернуть обратно подключение по стандарту

    $sl->db->backconnect()

> Подключится обратно к базе по настройкам которые установил админ

###query

Выполняет запрос к базе данных 

    $sl->db->query($query,$show_error = true)

- $query - Mysql запрос
- $show_error - Показывать ошибку

#####Пример

    <? $sl->db->query('SELECT * FROM '.$sl->db->prefix('users').' WHERE admin_ac > 0'); ?>
    
###select

Улучшенная вариация выборки и более удобная

    $sl->db->select($tbl,$str = false,$show_error = true)

- $tbl - Название таблицы
- $str - int(),string(),array()

#####Пример

    <?
    $sl->db->select('users'); // выбрать все
    
    $sl->db->select('users',3); // выбрать по ID при этом будет обработано через $sl->db->get_row()
    
    $sl->db->select('users','admin_ac > 0'); // выбрать где admin_ac > 0
    
    $sl->db->select('users',[
        'SELECT'=>false, // по дефолту *
        'WHERE'=>'admin_ac > 0',
        'ORDER'=>'id DESC',
        'GROUP'=>'name',
        'LIMIT'=>[
            1,      // страница
            25      // количество записей
        ],
        'LIMIT'=>25 // или просто лимит
    ]);
    
    $sl->db->select('users',[
        'LIKE'=>[
            'name', // название столбца
            'admin' // какое имя искать
        ]
    ]);
    ?>

###insert

Вставка новой записи в таблицу

    $sl->db->insert($tbl,$sql,$show_error = true)

- $tbl - Название таблицы
- $sql - Массив ключ/значение

#####Пример

    <?
    // Все значения фильтруются через $sl->db->escape() - mysqli_real_escape_string
    
    $sl->db->insert('users',[
        'name'=>'admin',
        'admin_ac'=>1,
        'date'=>''   // если оставить пустым то будет автоматически вставлена дата формата Y-m-d H:i:s
    ]);
    ?>

> Если ключ не найден в таблице то он будет создан и иметь тип VARCHAR 250

###update

Обновление записи

    $sl->db->update($tbl,$sql,$where,$quote = true,$show_error = true)

- $tbl - Название таблицы
- $sql - Массив ключ/значение
- $where - Строка или int()
- $quote - Использовать $sl->db->escape()

#####Пример

    <?
    // Обновить запись где id = 5
    
    $sl->db->update('users',[
        'admin_ac'=>0,
        'date'=>'' // если оставить пустым то будет автоматически вставлена дата формата Y-m-d H:i:s
    ],5);
    
    //Обновить запись где admin_ac > 0
    
    $sl->db->update('users',[
        'admin_ac'=>0
    ],'admin_ac > 0');
    ?>

###delete

Удаление записи

    $sl->db->delete($tbl,$where = false,$show_error = true)

- $tbl - Название таблицы
- $where - Строка или int()

#####Пример

    <?
    // Удалить запись где id = 5
    
    $sl->db->delete('users',5);
    
    //Удалить запись где admin_ac > 0
    
    $sl->db->update('users','admin_ac > 0');
    ?>

###count

Подсчет количество записей

    $sl->db->count($tbl,$where = false)

- $tbl - Название таблицы
- $where - Строка или массив

#####Пример

    <?
    $sl->db->count('users','admin_ac > 0'); // подсчитать количество записей где 'admin_ac > 0'
    
    $sl->db->count('users',['WHERE'=>'admin_ac > 0']); // подсчитать количество записей где 'admin_ac > 0'
    
    // подсчитать количество записей где WHERE `name` LIKE '%admin%'
    
    $sl->db->count('users',[
        'LIKE'=>[
            'name', // название столбца
            'admin' // какое имя искать
        ]
    ]); 
    
    // Изменить правила подсчета
    
    $sl->db->count('users',[
        'SELECT'=>'COUNT(*) as с' // по дефолту COUNT(id) as count
    ]);
    ?>

###alterTableAdd

Создание таблицы с полями если ее не существует

    $sl->db->alterTableAdd($tbl,$row)

- $tbl - Название таблицы
- $row - Название столбцов, массив

#####Пример

    <?
    $this->sl->db->alterTableAdd('static',[
        'название столбца'=>[
            'VARCHAR', // тип столбца
            300,       // длина
            0          // значение по умолчанию, не везде выставляется
        ]
    ]);
    
    $this->sl->db->alterTableAdd('static',[
        'title'=>['VARCHAR',300],
        'enabled'=>['SMALLINT',1,0],
        'visible'=>['SMALLINT',1,0],
        'descr'=>['VARCHAR',300],
        'date'=>['DATETIME NOT NULL',false,'0000-00-00 00:00:00'],
        'temp'=>['VARCHAR',100],
        'full_news'=>['TEXT NOT NULL',false],
        'short_news'=>['TEXT NOT NULL',false],
    ]);
    ?>

###get_while

Аналогично метода while()

    $sl->db->get_while($callback,$query_id = false)

- $callback - Функция
- $query_id - Последняя выборка или название таблицы

#####Пример

    <?
    // вариант первый
    
    $sl->db->select('users'); // предварительно делаем выборку
    
    $sl->db->get_while(function($row){
        echo $row['name'];
    });
    
    // вариант второй без метода $sl->db->select('users')
    
    $sl->db->get_while(function($row){
        echo $row['name'];
    },[
        'users', // название таблицы
        'admin_ac > 0' // все те же методы как для $sl->db->select()
    ]);
    
    // вариант третий выделить все записи из таблицы users
    
    $sl->db->get_while(function($row){
        echo $row['name'];
    },'users');
    ?>

###like

Простой метод для поиска в таблицах

    $sl->db->like($tbl,$row,$like,$lim = [])

- $tbl - Название таблицы
- $row - Название столбца
- $like - Что искать
- $lim - Лимит, int() или array()

#####Пример

    <? $sl->db->like('users','name','admin',5); ?>

###show_field

Вытаскивает название столбцов из таблицы, возвращает массив

    $sl->db->show_field($tbl)

- $tbl - Название таблицы

#####Пример

    <?
    $sl->db->show_field('users');
    
    // вернет ['id','cid','name','admin_ac','date']
    ?>

###get_row

Обрабатывает ряд результата запроса и возвращает ассоциативный массив ```mysqli_fetch_assoc```

    $sl->db->get_row($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query

###get_array

Возвращает массив, соответствующий обработанному ряду результата запроса и сдвигает внутренний указатель данных вперед.  ```mysqli_fetch_array```

    $sl->db->get_array($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query

###num_rows

Возвращает количество строк в результате ```mysqli_num_rows```

    $sl->db->num_rows($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query

###insert_id

Возвращает автоматически генерируемый ID, используя последний запрос ```mysqli_insert_id```

    $sl->db->insert_id($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query

###escape

Экранирует специальные символы в строках для использования в выражениях SQL ```mysqli_real_escape_string```

    $sl->db->escape($string)

- $string - Строка

###free

Освобождает память от результата запроса ```mysqli_free_result```

    $sl->db-free($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query

###close

Закрывает соединение с базой данных ```mysqli_close```

    $sl->db->close($query_id = false)

- $query_id - Выборка query не обязательно, будет использоваться последний запрос query









