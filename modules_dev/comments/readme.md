##Назначение

Показ комментариев к странице

##Методы

- show('id','table name',$page)
- widget('table name',$limit)

###show

Непосредственно сам показ комментариев к страницы

    <?
    $id = 5; // ID новости или страницы
    $table_name = ''; // можно использовать таблицу БД для разных категорий,
                      // к пример для новостей news а для других страниц page это позволит избежать совпадений по ID
    
    $sl->comments->show($id,$table_name);
    ?>

>Для того чтобы не авторизованный пользователь мог добавить комментарий отключите проверку авторизации в (инициализации модулей) в исключить добавьте строку ```comments/add```

###widget

Показывает последние комментарии, использует кэш и обновляется каждые 5 минут

    <?
    $table_name = ''; // название таблицы
    $lim = 10; // лимит комментов, по стандарту 5
    
    $sl->comments->widget($table_name,$lim);
    ?>

##Настройка связей

Модуль также поддерживает связи, подробно об этом смотрите в документации связи 

- Действие - show
- Параметры - {1}
- Параметры для модуля - название таблицы если необходимо
