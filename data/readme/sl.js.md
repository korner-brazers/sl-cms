##Назначение

Функционал для создания интерфейсов, меню, окна, списки, и другое

##Методы

>Не обращайте внимание что примеры заключены в ```<? ... ?> ```, зато все четко видно

###lang

Служит для перевода текста, принимает как строку так и массив
    
    <?
    $.sl('lang','переведи меня',function(j){
        // j содержит строку I translate
    }); 
    
    $('p').sl('lang'); // переведет все что было в `p`
    
    $.sl('lang',['переведи меня','переведи меня','переведи меня'],function(j){
        // j содержит массив ['I translate','I translate','I translate']
    }); 
    ?>

###update_scroll

Обновляет все скролинги на странице, применяется если контент изменился и нужно обновить скролинг в контенте

    $.sl('update_scroll')
    
###resize

Отслеживает изменения окна и вычитает высоту других элементов

    $.sl('resize',str(),array(),function{})

>Порядок не имеет значение

    <?
    $.sl('resize',function(h,ah,w){
        // h - высота окна
        // ah - высота окна и минус высоту других элементов на страницы
        // w - ширина окна
    })
    
    $.sl('resize','.content') //к каждому элементу применить высоту, смотрите селекторы jquery
    
    $.sl('resize','.content',
        ['.header','#foot'] // отнять ихнею высоту от общей и применить для .content
    )
    
    $.sl('resize', ['.header','#foot'],function(h,ah,w){
        // использовать функция для своих нужд 
    })
    ?>

###scroll_menu

Плавающий список

    $.sl('scroll_menu',str(),array(),int(),function{})

>Порядок не имеет значение

    <?
    $(this).sl('scroll_menu',{
        menu:['select 1','select 2','select 3','select 4'] // список меню
    },
        2 // значение
    );
    ?>

<a class="t_point" onclick="$(this).sl('scroll_menu',{menu:['select 1','select 2','select 3','select 4']},2);">Вызвать</a>

    <?
    $(this).sl('scroll_menu',{
        menu:['select 1','select 2','select 3','select 4'] // список меню
    },2,function(n,v){
        // n - ключ
        // v - значение
        // this - элемент
        alert(n+':'+v)
    });
    ?>

<a class="t_point" onclick="$(this).sl('scroll_menu',{menu:['select 1','select 2','select 3','select 4']},2,function(n,v){alert(n+':'+v)});">Вызвать</a>

    <?
    $(this).sl('scroll_menu',{
        menu:{'a':'select 1','b':'select 2','c':'select 3','d':'select 4'} // можно также использовать и такой список
    },
        'b', // выделен будет список который имеет ключ b
    function(n,v){
        // n - ключ
        // v - значение
        // this - элемент
        alert(n+':'+v)
    });
    ?>

<a class="t_point" onclick="$(this).sl('scroll_menu',{menu:{'a':'select 1','b':'select 2','c':'select 3','d':'select 4'}},'b',function(n,v){alert(n+':'+v)});">Вызвать</a>

Можно также отправлять выбранный список на указанный адрес

    <?
    $(this).sl('scroll_menu',{
        menu:['select 1','select 2','select 3','select 4'], // список меню
        module: [
            '/ajax/module/set', // отправит $_POST запрос со значениями $_POST[0] и $_POST[1]
            'mode',
        ]
    });
    ?>

Порой не всегда можно легко написать список и требует обработки PHP файла для создания меню, для этого достаточно указать адрес откуда брать меню

    <?
    $(this).sl('scroll_menu',{
        load:'/ajax/module/get', // запрос должен вернуть JSON результат
        //load:['/ajax/module/get','mode'], // можно также указать как массив
        module: ['/ajax/module/set', 'mode']
    });
    ?>

#####Пример PHP файла

> Метод отправки меню

    <?
        return ['select 1','select 2','select 3','select 4']; // для модулей
        
        echo json_encode(['select 1','select 2','select 3','select 4']); // ддя других файлов
    ?>

> Метод получения данных

    <?
        echo $_POST[0]; echo $_POST[1]; 
    ?>

###big_select

Большой список для выбора

    $.sl('big_select',str(),array(),function{})

>Порядок не имеет значение

    <?
    $.sl('big_select','Название списка',{
        menu:[
            ['Название пункта','Описание'],
            ['Название пункта','Описание'],
            ['Название пункта','Описание']
        ]
    },function(i){
        alert(i) // i - вернет ключ массива
    })
    ?>

<a class="t_point" onclick="$.sl('big_select','Название списка',{menu:[['Название пункта','Описание'],['Название пункта','Описание'],['Название пункта','Описание']]},function(i){ alert(i) })">Вызвать</a>

Меню как и в ```scroll_menu``` также можно подгрузить из PHP файла

    <?
    $.sl('big_select','Название списка',{
        load:'/ajax/module/get', // запрос должен вернуть JSON результат
        //load:['/ajax/module/get','mode'], // можно также указать как массив
    })
    ?>

#####Пример php файла

    <?
        return [['Название пункта','Описание'],['Название пункта','Описание'],['Название пункта','Описание']]; // для модулей
        
        echo json_encode([['Название пункта','Описание'],['Название пункта','Описание'],['Название пункта','Описание']]); // ддя других файлов
    ?>

Можно также отправлять выбранный список на указанный адрес

    <?
    $.sl('big_select','Название списка',{
        load:'/ajax/module/get',
        module: ['/ajax/module/set', 'mode'] // на какой адрес отправить, передает $_POST[0]
    })
    ?>

#####Пример php файла

    <?
        echo $_POST[0]; 
    ?>

###menu

Небольшая менюшка, имеет 4 стороны для того чтобы не выходить за пределы экрана

    <?
    $(this).sl('menu',{
        'name list':"alert('test')",
        'name list 2':function(){
            alert('test')
        }
    },{
        // настройки если нужно настроить
        parent: 'body',
        width: 120,
        offset: 10,
        position: 'auto' // or cursor
    })
    ?>

<a class="t_point" onclick="$(this).sl('menu',{'name list':'alert(\'test\')','name list 2':function(){alert('test')}})">Вызвать</a>

###tip

Небольшая подсказка которая выскакивает при наведение

    $('a[tip]').sl('tip');
    
    <a href="" tip="Я подсказка">Навести</a>

<a href="#" tip="Я подсказка">Навести</a>

###top_panel

Панель которая находится сверху и на ней ваш логин и две кнопки, эту панель можно также вывести и на сайте

    <?
    $.sl('top_panel',{
        // эти настройки применены по стандарту
        
        id: 'top_panel',
        login: "korner", 
        fun: "$.sl('shell',{name: 'admin_menu'})",
        logout: "$.sl('load','/ajax/auth/logout',function(){ window.location = document.URL })"
    })
    ?>

###preload

Создает предварительную загрузку изображений

    $('.content').sl('top_panel'); // ишет <img /> в .content и создает предзагрузку изображений

###window

Практически аналог окон как в window 7, изменения размеров, перемещения и другое.

    <?
    $('.html').sl('window',{
        w: 300,                 // ширина
        h: 140,                 // высота
        name: 'default',        // название окна
        status: 'none',         // 'none','show','hide','show_hide','close','backsize','fullsize','resize','index','data'
        title: 'Window',        // заголовок окна
        resize: false,          // разрешить изменения размеров окна
        containment: '#wrap', 
        drag: false,            // разрешить перемещать окно
        data: '',               // HTML текст который будет в окне или $('.html')
        size: false,            // разрешить разворачивать окно на весь экран
        btn: {
            'Сохранить':function(w){
                // w - название окна если нужно будет закрыть окно после нажатия кнопки
            }
        },
        autoclose: true,        // закрывать окно после нажатия на любую кнопку
        bg: true,               // использовать задний фон
        error: false,           // использовать окно как ошибку или предупреждение
        preload: true,          // использовать предзагрузку изображений в окне
        scroll:0                // 0 - relative, 1 - top, 2 - bottom
    },function(){
        // вызывается при каком либо изменение окна
    })
    ?>

<a class="t_point" onclick="$.sl('window',{btn:{'Сохранить':null},bg:1,title: 'Привет!',data:'<div class=\'t_p_10\'>Я окно!</div>',drag:1,size:1,resize:1,error:1})">Вызвать окно</a>

#####Доступные классы в окне

- ```.win_h_size``` - выставляет высоту под высоту окна
- ```.smooth``` - сглаживание текста
- ```.scrollbarInit``` - устанавливает скроллинг

###info

Выводит информацию на страницу

    $.sl('info','Показать сообшение');
    
<a class="t_point" onclick="$.sl('info','Показать сообшение');">Вызвать</a>

###shell

Вызывает одно большое окно, предназначен для модулей

    <?
    $.sl('shell',{
        name: 'admin_menu',     // название модуля
        method: 'show',         // метод вызова
        add_param: '/page/2',   // добавить параметры
        post: {static:'open'},  // массив POST запроса
        
    })
    
    $.sl('shell',{name: 'admin_menu'},
        'update' // доступно 'update','hide','close','hide_all_not',  hide_all_not - прячет все шеллы кроме используемого
    )
    
    $.sl('shell','hide_all') // прячит все шеллы
    ?>

<a class="t_point" onclick="$.sl('shell',{name: 'admin_menu'})">Вызвать</a>

#####Доступные классы в шеле

- ```.shell_iframe``` - подгоняет ifarme под размеры шелпа и осуществляет его загрузку
- ```.win_h_size``` - выставляет ширину и высоту под размер шелла
- ```.win_h_size_shell``` - выставляет только высоту под размер шелла
- ```.smooth``` - сглаживание текста
- ```.scrollbarInit``` - устанавливает скроллинг
- ```minus="45"``` - атрибут если нужно отнять высоту от общей

###count

Подсчитывает количество значений в массиве

    $.sl('count',{a:1,b:2,c:3}) // вернет 3

###loading

Показывает загрузку контента, доступно 6 вариаций загрузок

    <?
    $(this).sl('loading',{
        mode: 'show' // доступно 'content','quiet','show','cursor','point','hide'
    },
        'show' // доступно show - показать, hide - спрятать
    function(){
        // вызов функции по завершению действия
    })
    ?>

- <a class="t_point" onclick="$(this).sl('loading',{mode:'content'}); setTimeout(function(){ $(this).sl('loading',{mode:'content'},'hide'); },2000)">Вызвать метод (content) На элементе DOM</a>
- <a class="t_point" onclick="$(this).sl('loading',{mode:'quiet'}); setTimeout(function(){ $(this).sl('loading',{mode:'quiet'},'hide'); },2000)">Вызвать метод (quiet) Тихий режим</a>
- <a class="t_point" onclick="$(this).sl('loading',{mode:'show'}); setTimeout(function(){ $(this).sl('loading',{mode:'show'},'hide'); },2000)">Вызвать метод (show) На весь экран</a>
- <a class="t_point" onclick="$(this).sl('loading',{mode:'cursor'}); setTimeout(function(){ $(this).sl('loading',{mode:'cursor'},'hide'); },2000)">Вызвать метод (cursor) Изменения курсора на ожидания</a>
- <a class="t_point" onclick="$(this).sl('loading',{mode:'point'}); setTimeout(function(){ $(this).sl('loading',{mode:'point'},'hide'); },2000)">Вызвать метод (point) Последняя позиция курсора при клике </a>

###load

Осуществляет загрузку контента используя AJAX запрос

    $.sl('load','string url',str(),array(),function{})

>Порядок не имеет значение

    <?
    $(this).sl('load','/ajax/module/get',{
        type: 'POST',                           // тип отправки
        dataType: 'json',                       // по дефолту установлено html, смотрите в документации Jquery Ajax()
        data: {'name':'string','val':'value'},  // данные для отправки, по дефолту используется $('form').serializeArray() 
        done: function(data){},                 // вызов функции 
        mode: 'content',                        // тип лоадинга, смотрите метод (loading)
        back: true,                             // возвращать результат в $(this) если элемент был указан
        win: false,                             // использует настройки метода (window) и открывает окно с результатам загрузки
        shell: false,                           // использует настройки метода (shell)
        error: function(){},                    // если загрузка не удалась
        ignore: false                           // игнорировать ошибки в скрипте
    },function(data){
        //вызов функции 
    },
        'show' // тип лоадинга, смотрите метод (loading)
    )
    ?>
    
#####Примеры

    <?
    // загрузить данные в выбранный элемент
    
    $('.content').sl('load','/ajax/module/get');
    
    // если нужно только показать загрузку и не возвращать данные в элемент
    
    $('.content').sl('load','/ajax/settings/show',{
        back:false, // запрещаем возврат данных в элемент
        win:{w:700,h:350} // и вызовем окно
    },function(){
        $.sl('info','Загрузка завершена') // вызов функции при окончании загрузки
    });
    ?>

<a class="t_point" onclick="$(this).sl('load','/ajax/settings/show',{back:false,win:{w:700,h:350}},function(){ $.sl('info','Загрузка завершена') });">Вызвать</a>

    <?
    // если нужно передать все данные от формы
    // если это сделать внутри формы и передать $(this) то (load) сам найдет форму и воспользуется функцией .serializeArray()
    
    <form>
        <input type="text" name="test" value="23">
        
        <span onclick="$(this).sl('load','/ajax/module/set',{back:false},'show')">Отправить</span>
    </form>
    ?>


###_promt

Выводит сообщение в окне с текстовыми полями

    <?
    $(this).sl('_promt',{
        w: 400, // ширина окна
        h: 60,  // высота окна
        btn: {
            // если использовать load то по дефолту будет создана кнопка Сохранить
            'Сохранить':function(wn,form,result){
                // wn - название окна
                // form -  элементы формы form[0].value
                // result - результат запроса если использовать module
                // this - будет возвращен $(this)
            }
        },
        input: [
            // поля input смотрите документацию $sl->scin->input())
            {name:'name',value:'string',holder: ''}, // заполнения всех параметров поля
            'name', // только название
        ],
        module: [
            '/ajax/module/set', // адрес куда отправить результат
            'mode', // тип лоадинга
            function(form,result){
                // form -  элементы формы form[0].value
                // result - результат запроса если использовать module
                // this - будет возвращен $(this)
            }
        ],
        load: '/ajax/module/get',
            // или так
        load: [
            '/ajax/module/get',
            'mode'
        ],
        
        // также могут быть указаны настройки (window)
        
        bg: false
    })
    ?>

<a class="t_point" onclick="$(this).sl('_promt',{input:[{name:'name',value:'string',holder: ''},'name',{type:'password',name:'pass'}],btn:{'Сохранить':function(wn,form){ alert('Поле 1:'+form[0].value+';'+'Поле 2:'+form[1].value+';'+'Поле 3:'+form[2].value+';') }}});">Вызвать</a>

###_area

Окно с текстовым полем, удобно когда нужно ввести какой то текстовый список

    <?
    $(this).sl('_area',{
        w: 500, // ширина
        h: 300, // высота
        btn: {
            // если использовать value для загрузки из файла то по дефолту будет создана кнопка Сохранить
            'Сохранить':function(wn,form,result){
                // wn - название окна
                // form -  элементы формы form[0].value
                // result - результат запроса если использовать module
                // this - будет возвращен $(this)
            }
        },
        module: [
            '/ajax/module/set', // адрес куда отправить результат
            'mode', // тип лоадинга
            function(form,result){
                // form -  элементы формы form[0].value
                // result - результат запроса если использовать module
                // this - будет возвращен $(this)
            }
        ],
        area_name: 'area' // название поля,
        value: 'val' // значение поля
        value: [
            '/ajax/module/get', // или подгрузить из файла
            'mode'
        ],
        
        // также могут быть указаны настройки (window)
        
        bg: false
    })
    ?>
    
<a class="t_point" onclick="$(this).sl('_area');">Вызвать</a>

###_confirm

Служит для подтверждения выполнения действия

    $.sl('_confirm',str(),array(),function{})

>Порядок не имеет значение

    <?
    // простой пример
    $.sl('_confirm','Вы действительно хотите выполнить действие?',function(wn){
        // wn - название окна
    });
    ?>

<a class="t_point" onclick="$(this).sl('_confirm','Вы действительно хотите выполнить действие?',function(){ $.sl('info','Да он серьезно !! :D') });">Вызвать</a>

Полные настройки окна

    <?
    $.sl('_confirm',{
        w: 400, // ширина
        h: 100, // высота
        btn: {
            'Да':function(wn){
                // wn - название окна
                // this - кнопка
                $.sl('info','Да он серьезно !! :D')
            },
            'Нет':function(wn){
                $.sl('info','А жаль :(')
            }
        },
        info: 'Вы действительно хотите выполнить действие?',
        
        // также могут быть указаны настройки (window)
        
        title: 'Потвердить',
        bg: false
    });
    ?>
<a class="t_point" onclick="$(this).sl('_confirm',{btn: {'Да':function(wn){$.sl('info','Да он серьезно !! :D')},'Нет':function(wn){$.sl('info','А жаль :(')}},info: 'Вы действительно хотите выполнить действие?',title: 'Потвердить',error:1});">Вызвать</a>

###_tbl_del_tr

Удаляет строку TR в таблице

    <?
    $(this).sl('_tbl_del_tr'); // найдет <tr> используя .closest() и удалит
    
    $(this).sl('_tbl_del_tr',"callback: js function name"); // удалит и вызовет функцию
    
    $(this).sl('_tbl_del_tr',[
        '/ajax/module/set', // адрес для запроса
        'mode', // тип лоадинга
        function(){
            // вызов функции
        }
    ]);
    ?>

###_del_confirm

Тоже что и _confirm но упрошен для подтверждения удаления

    $.sl('_del_confirm',str(),array(),function{})

>Порядок не имеет значение

    <?
    $.sl('_del_confirm',{
        // module - обязательно
        module: [
            '/ajax/module/set',
            'mode',
        ],
        error: true, // подкрасим окно в красный цвет
        
        // также могут быть указаны настройки (window)
        
        bg: false,
        // btn: {}
    },function(data){
        // data - результат запроса
    })
    ?>

<a class="t_point" onclick="$.sl('_del_confirm',function(){},{error: true})">Вызвать</a>

###_window_setting

Используется для каких либо настроек, использует load для загрузки элементов формы и module для сохранения результата

    <?
    $.sl('_window_setting',{
        load:[
            '/ajax/module/get', // элементы формы
            'mode',
        ],
        module:[
            '/ajax/module/set', // куда отправить результат
            'mode',
            function(data){
                // data - результат запроса
            }
        ]
    })
    ?>

#####Пример на PHP

Создаем элементы формы /ajax/module/get

    <?
        <input name="fi" value="23" />
        <input name="og" value="100" />
    ?>
    
Получаем результат /ajax/module/set

    <?
        echo $_POST['fi']; // содержит 23
        echo $_POST['og']; // содержит 100
    ?>







