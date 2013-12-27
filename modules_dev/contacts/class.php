<?
/**
 * @contacts
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class contacts{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function __call($m,$p){
        
    }
    function send(){
        $this->sl->settings->check('contacts_email');
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        
        $lang = $this->sl->fn->lang([
            'Поле (Имя) не заполнено',
            'Поле (Email) не заполнено',
            'Поле (Тема) не заполнено',
            'Поле (Сообшение) не заполнено',
            'Поле (email) не коректно'
        ]);
        
        if($name == '') $this->sl->fn->info($lang[0]);
        if($email == '') $this->sl->fn->info($lang[1]);
        if($subject == '') $this->sl->fn->info($lang[2]);
        if($message	 == '') $this->sl->fn->info($lang[3]);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sl->fn->info($lang[4]);
        }
        
        $this->sl->mail->autoCheck(true);
        $this->sl->mail->From($email);
        $this->sl->mail->To( $this->sl->settings->get('contacts_email')); 
        $this->sl->mail->Subject( $subject ); 
        $this->sl->mail->Body( $message ); 
        $this->sl->mail->ReplyTo( $email );
        $this->sl->mail->Send();
        
        return $this->sl->mail->Get(); 
    }
    function show(){
        if(!$this->sl->install->check($this->modInfo[0])) return $this->sl->install->show($this->modInfo[0],$this->show_install());
        
        $lang = $this->sl->fn->lang([
            'Отправить',
            'Сообшение отправлено',
        ]);
        
        $this->sl->tpl->btn = $this->sl->scin->btn($lang[0],['callback'=>['/ajax/contacts/send','',"$(this).text('".$lang[1]."').removeAttr('onclick')"]]);
        
        return $this->sl->tpl->return_full('contacts_form');
    }
    function install($arr){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($arr[1][0] == 0 || $arr[1][0] == 1) $this->sl->fn->install_tpl($this->modInfo[0],$arr[1][0] == 1 ? true : false);
        
        $this->sl->settings->set('contacts_email',$arr[0][0],$this->sl->fn->lang('Email на который отправлять контакты'),1);
    }
    function show_install(){
        if($this->modInfo[5]) return;
        
        $lang = $this->sl->fn->lang([
            'Да - установить новые шаблоны если их нет',
            'Да - установить новые шаблоны даже если они установлены',
            'Нет - у меня уже установлены и настроены шаблоны новостей',
            'Установка шаблонов',
            'Для отображения контактной формы необходим шаблон ',
            'Ваш Email',
            'Укажите электронный ящик на который должны приходить контакты',
        ]);
        
        return [
            [
                'title'=>$lang[5],
                'descr'=>$lang[6],
                'input'=>'suport@mail.ru'
            ],[
                'title'=>$lang[3],
                'descr'=>$lang[4],
                'radio'=>['type'=>'list','val'=>[$lang[0],$lang[1],$lang[2]],'reverse'=>true],
            ]
        ];
    }
}
?>