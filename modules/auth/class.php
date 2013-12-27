<?
/**
 * @auth
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class auth{
    var $member_id = false;

    function init($sl,$moduleInfo = [],$ajaxLoad = false,$GlobalAjaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->GlobalAjaxLoad = $GlobalAjaxLoad;
        $this->conf = SL_DATA.DIR_SEP.'root';
    }
    function logout($url = ''){
        $this->cookie( "user_id", "", 0 );
        $this->cookie( "user_login", "", 0 );
    	$this->cookie( "user_password", "", 0 );
        
    	@session_destroy();
    	@session_unset();
        
        $this->member_id = false;
    	
        if(!$this->ajaxLoad) header( "Location: /" );
    }
    private function set_login($user_ar = []){
        if(!is_array($user_ar)) return false;
            
        $_SESSION['user_id']    = $user_ar['id'];
        $_SESSION['user_login']    = $user_ar['login'];
  		$_SESSION['user_password'] = $user_ar['password'];
        
        $this->cookie( "user_id", $user_ar['id'], 365 );
        $this->cookie( "user_login", $user_ar['login'], 365 );
	    $this->cookie( "user_password", $user_ar['password'], 365 );
        
        $this->member_id = $user_ar;
        
        return $this->member_id;
    }
    private function createUserDb($username,$password = '',$email = ''){
        if($this->sl->db->connect(false)){
            $this->sl->db->alterTableAdd('users',['login'=>['VARCHAR',100],'email'=>['VARCHAR',100],'password'=>['VARCHAR',32],'admin_ac'=>['SMALLINT',1,0]]);
            
            $c_user = $this->sl->db->count('users',"login='".$this->sl->db->escape($username)."'");
            
            if($c_user > 0 ) return $this->sl->db->get_row($this->sl->db->select('users',"login='".$this->sl->db->escape($username)."'"));
            else{
                $this->sl->db->insert('users',[
                    'login'=>$username,
                    'password'=>$password,
                    'email'=>$email,
                    'date'=>'',
                    'admin_ac'=>($username == 'root' ? 1 : 0)
                ]);
                
                return $this->sl->db->select('users',intval($this->sl->db->insert_id()));
            }
        }
        elseif($username == 'root') return ['id'=>1,'login'=>$username,'password'=>$password,'admin_ac'=>1];
    }
    function login($login = '',$password = '',$global = true){
        
        $lang = $this->sl->fn->lang([
            'Пустой логин или пароль',
            'Пароль не совпадает',
            'Пользователь с таким логином не найден'
        ]);
        
        if(isset($_POST['login'])){
            $login = $_POST['login'];
            $password = $_POST['password'];
        }
        
        if(empty($login) || empty($password)){
            if($this->ajaxLoad) $this->sl->fn->info($lang[0]);
            else return false;
        }
        
        $md_password = md5(md5($password));
        $login = trim($login);
        
        if($login == 'root'){
            $root_pass = $this->sl->fn->conf('get',$this->conf);
            
            if($md_password == $root_pass['password']){
                return $this->set_login($this->createUserDb('root',$md_password));
            } 
            else{
                if($this->ajaxLoad) $this->sl->fn->info($lang[1]);
                else return false;
            }
        }
        
        $confGlobal = $this->sl->settings->get('auth_global');
        
        if($global && $confGlobal) $conect = $this->sl->curl->get('sl-cms.com/ajax/conect/check_login/'.urlencode($login).'/'.md5($password)); 

        if(!$conect['error'] && $user_json = json_decode($conect['content'],true)){
            if($user_json['success']) return $this->set_login($this->createUserDb($login,$md_password,$user_json['email']));
        }
        
        $user_select = $this->sl->db->get_row($this->sl->db->select('users',"login='".$this->sl->db->escape($login)."'"));
        
        if($user_select){
            if($md_password == $user_select['password']) $this->set_login($user_select);
            else{
                if($this->ajaxLoad) $this->sl->fn->info($lang[1]);
                else return false;
            }
        } 
        else{
            if($this->ajaxLoad) $this->sl->fn->info($lang[2]);
            else return false;
        }
        
        
        return true;
    }
    function check_member(){
        if(!$this->member_id) return $this->check();
    }
    function check(){
        if( isset( $_SESSION['user_id'] ) ) {
                        
            if($this->sl->db->connect(false) && intval($_SESSION['user_id']) > 0){
                $user_select = $this->sl->db->select('users',intval($_SESSION['user_id']));
                if($_SESSION['user_password'] == $user_select['password']) $this->set_login($user_select);
            }
            else{
                if($_SESSION['user_login'] == 'root'){
                    if($_SESSION['user_password'] == $this->sl->fn->conf('get',$this->conf)['password']) $this->set_login(['id'=>$_SESSION['user_id'],'login'=>$_SESSION['user_login'],'password'=>$_SESSION['user_password'],'admin_ac'=>1]);
                }
            }
            
        } elseif( isset( $_COOKIE['user_id'] ) ) {
            
            if($this->sl->db->connect(false) && intval($_COOKIE['user_id']) > 0){
                $user_select = $this->sl->db->select('users',intval($_COOKIE['user_id']));
                if($_COOKIE['user_password'] == $user_select['password']) $this->set_login($user_select);
            }
            else{
                if($_COOKIE['user_login'] == 'root'){
                    if($_COOKIE['user_password'] == $this->sl->fn->conf('get',$this->conf)['password']) $this->set_login(['id'=>$_COOKIE['user_id'],'login'=>$_COOKIE['user_login'],'password'=>$_COOKIE['user_password'],'admin_ac'=>1]);
                }
            }
        }
        
        if(!$this->member_id){
            $this->cookie( "user_id", "", 0 );
            $this->cookie( "user_login", "", 0 );
        	$this->cookie( "user_password", "", 0 );
            
        	@session_destroy();
        	@session_unset();
        }
        else return $this->member_id;
    }

    private function host($host) {
	
    	if( $host == '' ) return;
    	$host = str_replace( "http://", "", $host );
    	if( strtolower( substr( $host, 0, 4 ) ) == 'www.' ) $host = substr( $host, 4 );
    	$host = explode( '/', $host );
    	$host = reset( $host );
    	$host = explode( ':', $host );
    	$host = reset( $host );
    	
    	return $host;
    }

    function cookie($name, $value, $time) {
        
        $host = $this->host($_SERVER['HTTP_HOST']);
        
	    if( $time ) {    $time = time() + ($time * 86400);    } 
		else        {    $time = FALSE; }
	
	    if( PHP_VERSION < 5.2 ) {
		
		    setcookie( $name, $value, $time, "/", $host . "; HttpOnly" );
	
	    } else {
		
		    setcookie( $name, $value, $time, "/", $host, NULL, TRUE );
	
	    }
    }
    function new_ac(){
        $lang = $this->sl->fn->lang([
            'Пароль не может быть пустым',
            'ROOT уже создан, пожалуйста войдите под логином root и измените данные'
        ]);
        
        if(!$this->sl->fn->conf('get',$this->conf)['password']){
            if($_POST['password'] == '') $this->sl->fn->info($lang[0]);
            else{
                $this->sl->fn->conf('set',$this->conf,['password'=>md5(md5($_POST['password']))]);
                
                if($this->sl->db->connect(false)){
                    $this->sl->db->alterTableAdd('users',['login'=>['VARCHAR',100],'email'=>['VARCHAR',100],'password'=>['VARCHAR',32],'admin_ac'=>['SMALLINT',1,0]]);
                    $this->sl->db->update('users',['password'=>md5(md5($_POST['password']))],"login='root'");
                }
            
                $this->sl->curl->get('sl-cms.com/ajax/conect/new_root/'.urlencode($_SERVER["HTTP_HOST"]));
            } 
        } 
        else $this->sl->fn->info($lang[1]);
    }
    function show(){
        if($this->modInfo[4]) return;
        
        $lang = $this->sl->fn->lang([
            'ROOT не установлен!',
            'Вы не авторизованы или сессия истекла, обновите страницу или авторизуйтесь заново'
        ]);
        
        if(!$this->sl->fn->conf('get',$this->conf)['password']){
            if($this->GlobalAjaxLoad) $this->sl->fn->info($lang[0]);
            elseif(defined('ADMINFILE')) include_once __DIR__.DIR_SEP.'install.php';
        }
        elseif(!$this->check()){
            if($this->GlobalAjaxLoad) $this->sl->fn->info($lang[1]);
            elseif(defined('ADMINFILE')) include_once __DIR__.DIR_SEP.'login.php';
        }
        
        if(defined('ADMINFILE') && $this->member_id['admin_ac'] == 0) include_once __DIR__.DIR_SEP.'login.php';
    }
    function widget(){
        ob_start();
        include __DIR__.DIR_SEP.'widget.php';
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
    function registrNew($s = false){
        
        $confGlobal = $this->sl->settings->get('auth_global');
        
        $lang = $this->sl->fn->lang([
            'Ошибка данных',
            'Заполните все поля',
            'Запрещенный символ',
            'Пароль должен состоять из более 6 символов',
            'Слишком длинный логин',
            'Некорректный email адрес',
            'Пользователь c таким Логином или Email адресом уже зарегистрирован',
            'Никнейм',
            'Пароль'
        ]);
        
        if($s){
            $post = isset($_POST) ? $_POST : $this->sl->fn->info($lang[0]);
            
            $login    = trim($post['login']);
            $password = trim($post['password']);
            $email    = trim($post['email']);
            
            if(empty($login) || empty($password) || empty($email)) $this->sl->fn->info($lang[1]);
                
            if($confGlobal) $this->sl->fn->server('conect/registrNew',['POST'=>['login'=>$login,'password'=>$password,'email'=>$email]]);
            else{
                if(preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $login )) $this->sl->fn->info($lang[2]);
                if(strlen($password) < 6) $this->sl->fn->info($lang[3]);
                if(strlen( $login ) > 20 ) $this->sl->fn->info($lang[4]);
                if( empty( $email ) OR strlen( $email ) > 50 OR @count(explode("@", $email)) != 2) $this->sl->fn->info($lang[5]);
                
                $user = $this->sl->db->get_row($this->sl->db->select('users',"login='".$this->sl->db->escape($login)."' OR email='".$this->sl->db->escape($email)."'"));
                
                if($user) $this->sl->fn->info($lang[6]);
                
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $this->sl->db->insert('users',[
                        'email'=>$email,
                        'login'=>$login,
                        'password'=>md5(md5($password)),
                        'date'=>''
                    ]);
                }
                else $this->sl->fn->info($lang[5]);
            }
            
            $this->login($login,$password);
            
            return ['success'=>true];
        }
        
        $this->sl->scin->table_td_op(0,100);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('login'),
            $lang[7]
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('password','',['type'=>'password']),
            $lang[8]
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('email'),
            'E-mail'
        ]);
        
        $this->sl->scin->table();
        
        return $this->sl->scin->table_display();
    }
    function install(){
        if($this->sl->fn->check_ac('admin')) return $this->sl->stpl->install_error();
        
        $lang = $this->sl->fn->lang([
            'Использовать глобальную авторизацию',
            'Установка завершена!',
            'Проверьте настройки, в них должен появится параметр'
        ]);
        
        $this->sl->settings->set('auth_global',1,$lang[0],1);
        
        return $this->sl->stpl->install($lang[2].' ( auth_global )');
    }
}
?>