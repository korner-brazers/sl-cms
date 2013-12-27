##Назначение

Предназначен для формирования таблиц, выпадающих списков, кнопок, текстовых полей и другое. Является обязательным для всех модулей где будут создаваться таблицы, кнопки и другое.

##Методы

###js

Подключения JS файлов на страницу

    $sl->scin->js($string , $pr = false );
    
- $string - Файлы: Принимает как строку так и массив, в строке можно использовать разделитель ```,```
- $pr - Параметры: Принимает как строку так и массив, строка служит как путь к файлу

#####Примеры
    
    <?
    $sl->scin->js(['js/reday','layers','media/open'],'/dir/allfiles');
    
    $sl->scin->js('js/reday,layers,media/open','/dir/allfiles');
    
    $sl->scin->js('js/reday,layers,media/open',['theme'=>'/dir/allfiles']);
    
    $sl->scin->js('js/reday,layers,media/open',['theme'=>'/dir/allfiles','pr'=>1]);
    
    //'pr'=>1  - Означает что результат будет выведен через echo
    ?>

###css

Подключения CSS файлов на страницу

    $sl->scin->css($string , $pr = false );
    
- $string - Файлы: Принимает как строку так и массив, в строке можно использовать разделитель ```,```
- $pr - Параметры: Принимает как строку так и массив, строка служит как путь к файлу

#####Примеры
    
    <?
    $sl->scin->css(['js/reday','layers','media/open'],'/dir/allfiles');
    
    $sl->scin->css('js/reday,layers,media/open','/dir/allfiles');
    
    $sl->scin->css('js/reday,layers,media/open',['theme'=>'/dir/allfiles']);
    
    $sl->scin->css('js/reday,layers,media/open',['theme'=>'/dir/allfiles','pr'=>1]);
    
    //'pr'=>1  - Означает что результат будет выведен через echo
    ?>

###cache_js

Подключение скриптов на страницу используя кеш, если раннее скрипт уже был подключен то повторное подключение не произойдет

    $sl->scin->cache_js($name = false,$param = false,$action = 'show',$pr = false)
    

- $name - Название модуля в котором хранится ```media.js.php```
- $param - Массив ключ/значение, используется для передачи ```$_GET``` данных к файлу ```media.js.php```, формирует строку ```&key=value```
- $action - Необязательный параметр, используется если нужно подключить разные скрипты к определенным действиям
- $pr - true результат будет выведен через ```echo``` по стандарту присвоено false

#####Примеры

    <?
    $sl->scin->cache_js($this->modInfo[0]);
    
    $sl->scin->cache_js(__DIR__);
    
    $sl->scin->cache_js(__DIR__,['lang'=>'off']);
    
    $sl->scin->cache_js(__DIR__,['lang'=>'off'],'edit');
    
    $sl->scin->cache_js(__DIR__,false,'show',true);
    ?>
    
#####Результат

    <script id="cache_media_js_readme_show" type="text/javascript" src="/modules/readme/media.js.php?module=readme&action=show" rel="stylesheet"></script>
    
    <script id="cache_media_js_readme_show" type="text/javascript" src="/modules/readme/media.js.php?module=readme&action=show" rel="stylesheet"></script>
    
    <script id="cache_media_js_readme_show" type="text/javascript" src="/modules/readme/media.js.php?module=readme&action=show&lang=off" rel="stylesheet"></script>
    
    <script id="cache_media_js_readme_edit" type="text/javascript" src="/modules/readme/media.js.php?module=readme&action=edit&lang=off" rel="stylesheet"></script>
    
#####Файл media.js.php

    <?
    header("Content-type: application/x-javascript");
    
    if($_GET['action'] == 'edit'){
        ?>
        $('ul.<?=$_GET['module']?>_apps_list li').live('click',function(){
            var lang = '<?=$_GET['lang']?>';
            
            ...
        })
        <?
    }
    ?>

###cache_css

Подключение стилей на страницу используя кеш, если раннее стиль уже был подключен то повторное подключение не произойдет 

    $sl->scin->cache_css($name = false,$param = false,$action = 'show',$pr = false)
    

- $name - Название модуля в котором хранится ```media.css.php```
- $param - Массив ключ значение, используется для передачи ```$_GET``` данных к файлу ```media.css.php```, формирует строку ```&key=value```
- $action - Необязательный параметр, используется если нужно подключить разные скрипты к определенным действиям
- $pr - true результат будет выведен через ```echo``` по стандарту присвоено false

#####Примеры
    
    <?
    $sl->scin->cache_css($this->modInfo[0]);
    
    $sl->scin->cache_css(__DIR__);
    
    $sl->scin->cache_css(__DIR__,['lang'=>'off']);
    
    $sl->scin->cache_css(__DIR__,['lang'=>'off'],'edit');
    
    $sl->scin->cache_css(__DIR__,false,'show',true);
    ?>
    
#####Результат

    <link id="cache_media_css_readme_show" type="text/css" href="/modules/readme/media.css.php?module=readme&action=show" rel="stylesheet" />
    
    <link id="cache_media_css_readme_show" type="text/css" href="/modules/readme/media.css.php?module=readme&action=show" rel="stylesheet" />
    
    <link id="cache_media_css_readme_show" type="text/css" href="/modules/readme/media.css.php?module=readme&action=show&lang=off" rel="stylesheet" />
    
    <link id="cache_media_css_readme_edit" type="text/css" href="/modules/readme/media.css.php?module=readme&action=edit&lang=off" rel="stylesheet" />
    
#####Файл media.css.php

    <?
    header("Content-type: text/css"); 
    ?>
    .<?=$_GET['module']?>_bg{
        background: #fff url(bg.jpg) no-repeat 50% 50%;
    }
    .<?=$_GET['module']?>_apps{
        width: 255px !important;
        background: #eaeaea;
    }

###checkbox

    $sl->scin->checkbox($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->checkbox('name');
    
    $sl->scin->checkbox('name','value');
    
    $sl->scin->checkbox('name','value','callback: name js function');
    
    $sl->scin->checkbox('name','value',['callback'=>'name js function']);
    
    $sl->scin->checkbox('name','value',[
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ],
        'callback'=>'jsFunction' //функция примет вид jsFunction('name','value')
    ]);
    
    $sl->scin->checkbox([
        'name'=>'form[]',
        'value'=>'1'
    ]);
    ?>
    
###input

    $sl->scin->input($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->input('name');
    
    $sl->scin->input('name','value');
    
    $sl->scin->input('name','value',[
        'name'=> 'form[]',
        'value'=> '',
        'type'=> 'input', // доступно 'url','text','password','date','email','number','tel'
        'attr'=>['style'=>'margin-top: 10px','class'=>'select'],
        'holder'=>'', // равносильно placeholder=""
        'bigedit'=>false, // специальная кнопка которое открывает большое окно для удобного редактирования
        'pattern'=>'' //равносильно pattern="",
        'regex'=>'[^a-z0-9]', //сотрет все кроме букв и цифр
        'check'=>'', //равносильно spellcheck=""
        'invisible'=>false // делает как бы полупрозрачным
    ]);
    
    $sl->scin->input([
        // те же параметры что выше
        ...
    ]);
    ?>
    
###input_live

Текстовое поле для поиска, после ввода текста срабатывает функция которая отсылает запрос с текстом для поиска

    $sl->scin->input_live($where,$fn = false,$json = true)

- $where - Куда отсылать запрос ```/ajax/module/livesearch```
- $fn - JS функция для вызова
- $json - Какой будет возврашен AJAX запрос

#####Примеры

    <?
    $sl->scin->input_live('ajax/module/livesearch');
    
    $sl->scin->input_live('ajax/module/livesearch','jsFunction(data)');
    
    $sl->scin->input_live('ajax/module/livesearch','jsFunction(data)',false);
    ?>
    
> Если jsFunction не указан то сработает функция ```$(this).sl('menu',data)```

###btn

Отображение кнопки

    $sl->scin->btn($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->btn('name');
    
    $sl->scin->btn('name','callback: js function name');
    
    $sl->scin->btn('name',[
        'callback'=>'js function name'
    ]);
    
    $sl->scin->btn('name',[
        'callback'=>[
            '/ajax/module/set',
            'mode', // метод загрузки $.sl('load')
            "alert(data)" // js функция после загрузки
        ]
    ]);
    
    $sl->scin->btn('name',[
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ]
    ]);
    ?>
    
###btn_group

Одна большая кнопка или дополнительное меню в одной панели, такую кнопку можно увидеть в настройках и других модулях  

    $sl->scin->btn_group($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->btn_group('callback: js function name');
    
    $sl->scin->btn_group([
        '/ajax/module/set',
        'mode',
        "alert(data)"
    ]);
    
    $sl->scin->btn_group([
        'name btn'=>[
            '/ajax/module/set',
            'mode',
            "alert(data)"
        ],
        'name btn'=>'callback: js function name',
    ]);
    ?>
    
###floating

Боковая маленькая кнопка

    $sl->scin->floating($n = '')

- $n - Массив или строка

#####Пример

    <? $sl->scin->floating('callback: js function name'); ?>
    
###radio

Когда пользователь нажимает на радио-кнопки, нажатая кнопка становится выделенной, а все остальные - не выделенными 

    $sl->scin->radio($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->radio('name'); // по стандарту будет on и off
    
    $sl->scin->radio('name',['on','off']);
    
    $sl->scin->radio('name',['on','off'],'on');
    
    $sl->scin->radio('name',['on','off'],0,['reverse'=>true]); // reverse - trueдля проверки совпадения выделения по ключу массива
    
    $sl->scin->radio('name',['on','off'],0,'callback: js function name');
    
    $sl->scin->radio('name',['on','off'],0,['callback'=>[
        '/ajax/module/set',
        'mode',
        "alert(data)"
    ]]);
    
    $sl->scin->radio('name',['on','off'],0,[
        'type'=>'' // доступно false,'list','line' по стандарту присвоено 'line'
    ]);
    
    $sl->scin->radio([
        'name'=>'form[]',
        'value'=> 'on',
        'val'=> ['on','off'],
        'type'=> 'line',
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ],
        'callback'=>'',
        'reverse'=>false
    ]);
    ?>

###select

Выпадающий список

    $sl->scin->select($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->select('name'); // по стандарту будет on и off
    
    $sl->scin->select('name',['on','off']);
    
    $sl->scin->select('name',['on','off'],0);
    
    $sl->scin->select('name',['on','off'],0,'callback: js function name');
    
    $sl->scin->select('name',['on','off'],0,['callback'=>[
        'jfFunctionName', // название функции
        'param1', // дополнительный параметр
        "param2"  // дополнительный параметр
    ]]);
    
    
    $sl->scin->radio([
        'name'=>'form[]',
        'value'=> 'on',
        'val'=> ['on','off'],
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ],
        'callback'=>'',
    ]);
    ?>

###textarea

Текстовое поле

    $sl->scin->textarea($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->textarea('name');
    
    $sl->scin->textarea('name','value');
    
    $sl->scin->textarea('name','value',[
        'name'=>'form[]',
        'value'=> 'on',
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ],
        'bigedit'=>false, // специальная кнопка которое открывает большое окно для удобного редактирования
        'check'=>'', //равносильно spellcheck=""
        'invisible'=>false // делает как бы полупрозрачным
    ]);
    
    $sl->scin->textarea([
        'name'=>'form[]',
        'value'=> 'on',
        
        ...
    ]);
    ?>

###slide

Похоже на табы но немного отличается

    $sl->scin->slide($n = [],$op = [])

- $n - Массив название/строка
- $op - Дополнительные параметры

#####Примеры

    <?
    $sl->scin->slide([
        'name tab'=>'string',
        'name tab'=>'string',
        ...
    ]);
    
    $sl->scin->slide([
        'name tab'=>'string',
        'name tab'=>'string',
        ...
    ],[
        'minus'=>45 // отнять количество пикселей от высоты
    ]);
    ?>

###hint

Подсказка

    $sl->scin->hint($n = '')

- $n - Массив или строка

#####Примеры

    <?
    $sl->scin->hint('Подсказка при наведение');
    
    $sl->scin->hint('Подсказка при наведение',[
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ]
    ]);
    
    $sl->scin->hint([
        'value'=>'Подсказка при наведение',
        'attr'=>[
            'style'=>'margin-top: 10px',
            'class'=>'select',
            'onclick'=>'openfile(this)'
        ]
    ]);
    ?>
    
##Семейство TABLE

###table_td_op

Устанавливает свойства к ячейкам

    <?
    // Устанавливает ширину для каждого столбца, 0 - без ширины
    
    $sl->scin->table_td_op(20,80,0,120); 
    
    $sl->scin->table_td_op([20,80,0,120]);
    
    $sl->scin->table_td_op([0=>20,3=>120]); 
    
    // Установка атрибутов
    
    $sl->scin->table_td_op([
        ['class'=>'light'],
        ['class'=>'dark','width'=>'90'],
        ['onclick'=>"alert('data')"],
        120
    ]);
    ?>

###table_td_add_op

Устанавливает свойства к ячейкам как дополнительно если в ```table_td_op``` не было установлено, все те же настройки как и у ```table_td_op```

###table_td

Формирует ячейку TD

    $sl->scin->table_td($content = '',$op = [],$re = true);

- $content - Строка
- $op - Массив attr атрибутов
- $re - Записать результат в общую таблицу

#####Примеры

    <?
    $sl->scin->table_td('string HTML code');
    
    $sl->scin->table_td('string HTML code',[
        'class'=>'dark',
        'width'=>'90'
    ]);
    ?>
    
###table_th

Формирует ячейку TH

    $sl->scin->table_th($name = '',$op = []);

- $name - Название
- $op - Массив attr атрибутов

#####Примеры

    <?
    $sl->scin->table_th('string name');
    
    $sl->scin->table_th('string name',[
        'class'=>'dark',
        'width'=>'90'
    ]);
    ?>

###table_tr

Формирует ячейку TR и TD

    $sl->scin->table_tr($arr = [],$id = false);

- $arr - Массив TD ячеек
- $id - Необязательный параметр, необходимо если нужно удалить TR по его ID

#####Пример

    <?
    $sl->scin->table_tr([
        'td strind',
        'td string',
        ...
    ]);
    ?>

###table_dynamic_row

Добавляет TR строку с название ('Добавить поле')

    $sl->scin->table_dynamic_row($fn = '',$url = false,$top = 0)

- $fn - Название JS функции
- $url - Использовать URL запрос вместо JS функции
- $top - Куда добавить результат, перед ('Добавить поле') или сверху используя номер после какой TR вставить

#####Примеры

    <?
    $sl->scin->table_dynamic_row('jsFunctionName');
    
    $sl->scin->table_dynamic_row('jsFunctionName',false,4);
    
    $sl->scin->table_dynamic_row('/ajax/module/get',true);
    
    $sl->scin->table_dynamic_row('/ajax/module/get',true,4);
    ?>

###table

Формирует всю таблицу TABLE

    $sl->scin->table($op = '')

- $op - Массив attr атрибутов или строка формирующие ```id="str"```

#####Пример

    <?
    $sl->scin->table();
    
    $sl->scin->table('id table');
    
    $sl->scin->table([
        'class'=>'dark',
        'width'=>'90'
    ]);
    ?>

###table_form

Оборачивает таблицу в тег  ```<form></form>```

    $sl->scin->table_form($op = '')

- $op - Массив attr атрибутов или строка формирующие ```id="str"```

#####Примеры

    <?
    $sl->scin->table_form();
    
    $sl->scin->table_form('id form');
    
    $sl->scin->table_form([
        'metgod'=>'get',
        'class'=>'form'
    ]);
    ?>

###table_add_string

Добавляет HTML код перед таблицей

    $sl->scin->table_add_string($str = '')

- $op - Строка

#####Пример

    <? $sl->scin->table_add_string('HTML code'); ?>

###table_head

Формирует заголовок таблицы

    $sl->scin->table_head($head = [])

- $op - Массив или строка

#####Примеры
    
    <?
    $sl->scin->table_head('name row','name row','name row','name row');
    
    $sl->scin->table_head(['name row','name row','name row','name row']);
    ?>

###table_dynamic

Динамическая таблица, объединяет в себе БД и массивы, используется для более легкого и удобного построения таблицы. 

    $sl->scin->table_dynamic($n = [],$btn = [],$tbn = '',$lim = [0,25],$navtbl = false)

- $n - Массив функций
- $btn - Массив кнопок
- $tbn - Название БД таблицы или результат query выборки
- $lim - Лимит ['int(start)','int(lim)','url page','select where']
- $navtbl - query выборка для подсчета результат, по стандарту подсчет идет из последнего запроса БД

#####Примеры
    
    <?
    /**
     * Ручная настройка
     */
    
    $sl->scin->table_dynamic([
        'name'=>['<b>','</b>'],
        'name',
        'name'=>function($val,$id,$row){
            return $val; //значение ячейки name
            return $id; // ID 
            return $row; // массив ячеек с значениями
        }
    ],[
        'Удалить'=>['/ajax/module','mode',"alert(data)"], // при этом строка удаляется
        'Вызвать'=>'callback: jsFunction name',
        'Вызвать'=>[3=>['onclick'=>"alert('{id}:{row-name}')"]],
    ],
    'users', // или $sl->db->select('users')
    [
        1, // страница,
        25, // лимит на страницу,
        "$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'{n}'},'update');" // переход на страницу, адрес может быть любой,
        'admin_ac > 0' // Строка или массив, смотрите в документации DB метод count(); подсчет количество записей для навигации
    ],
    'users' // если используется навигация и вы не сделали запрос $sl->db->count('users')
    );
    
    /**
     * Автоматическая настройка
     */
    
    $sl->scin->table_dynamic("callback: jsFunction alert('{id} : {row}')",[
        'Удалить'=>['/ajax/module','mode',"alert(data)"], // при этом строка удаляется
        'Вызвать'=>'callback: jsFunction name',
        'Вызвать'=>[3=>['onclick'=>"alert('{id}:{row-name}')"]],
    ],
    'users', // с какой таблицы производить выборку
    [
        1, // страница,
        25, // лимит на страницу,
        "$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'{n}'},'update');" // переход на страницу, адрес может быть любой
        'admin_ac > 0' // Строка или массив, смотрите в документации DB метод count(); подсчет количество записей для навигации
    ]
    ,'users');
    ?>
    
###table_display

Формирует всю таблицу в возвращает результат

    $sl->scin->table_display();
    
###table_clear

Очищает таблицу полностью, если нужно создать еще таблицу то необходимо очистить таблицу, если этого не сделать то вторая таблица буквально сольется с первой

    $sl->scin->table_clear();
    
##Полный пример создание таблицы

    <?
    //Устанавливаем размеры колонок
    
    $sl->scin->table_td_op(20,200,0,120);
    
    //По необходимости можем добавить атрибуты
    
    $sl->scin->table_td_add_op([['class'=>'light'],3=>['class'=>'dark']]);
    
    //Добавляем название к колонкам
    
    $sl->scin->table_head('id','name','descr','');
    
    //Добавляем запись, здесь же можно и воспользоваться динамической таблицей table_dynamic
    
    $sl->scin->table_tr([
        '23',
        'Fild name',
        'Full description',
        $sl->scin->btn('Удалить')
    ]);
    
    //По необходимости можем добавить плавающую строку
    
    $sl->scin->table_dynamic_row('jsFunctionName');
    
    //Формируем таблицу
    
    $sl->scin->table();
    
    //По необходимости можем обвернуть в форму 
    
    $sl->scin->table_form();
    
    //Выводим результат
    
    echo $sl->scin->table_display();
    ?>
