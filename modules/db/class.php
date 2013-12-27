<?
/**
 * @db
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class db{
    var $db_id           = false;
	var $connected       = false;
    var $conf            = [];
    var $dynamic         = true;
    var $query_id        = false;
    var $query_num       = 0;
    var $last_tbl_name   = false;
    var $error           = false;
    var $stopConnected   = false;
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false,$gAjax = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->gAjax = $gAjax;
        $this->conf_path = SL_DATA.DIR_SEP.MULTI_DN.'_db_connect';
        $this->conf = $this->sl->fn->conf('get',$this->conf_path);
    }
    function __call($name, $arr = []) {

    }
    private function check_ac(){
        if($this->modInfo[1] == 'show' || $this->modInfo[1] == 'newconnect') return false;
        elseif($this->modInfo[4]) return true;
    }
    function prefix($tbl){
        return $this->conf['prefix'] != '' ? $this->conf['prefix'].'_'.$tbl : $tbl;
    }
    function newconnect(){
        if($this->sl->fn->check_ac('root')) return;
        
        $this->sl->fn->conf('update',$this->conf_path,['user'=>$_POST['user'],'pass'=>$_POST['pass'],'prefix'=>$_POST['prefix'],'dbname'=>$_POST['dbname'],'ip'=>$_POST['ip']]);
    }
    function changeconnect($newconnect = [],$show_error = true){
        if($this->check_ac()) return;
        
        if(count($newconnect) > 0){
            $this->connected = $this->stopConnected = false;
            $this->connect($show_error,$newconnect);
        }
    }
    function backconnect(){
        if($this->check_ac()) return;
        
        $this->connected = $this->stopConnected = false;
        $this->connect();
    }
   	function connect($show_error = true,$newconnect = false){
   	    if($this->connected) return true;
        
        if($this->stopConnected){
            if($show_error) $this->errors(@mysqli_connect_error(),0,'',true);
			
            return false;
        }
        
        if(!function_exists('mysqli_connect')) return false;
        
        if($newconnect && is_array($newconnect)) $this->conf = $newconnect;
   	    else $this->conf = $this->sl->fn->conf('get',$this->conf_path);
        
        if(empty($this->conf['user']) || empty($this->conf['dbname'])) return false;
        
	    $db_ip = explode(":", $this->conf['ip']);
        
        if(!empty($db_ip[0])){
            if(isset($db_ip[1])) $this->db_id = @mysqli_connect($db_ip[0], $this->conf['user'], $this->conf['pass'], $this->conf['dbname'], $db_ip[1]);
            else $this->db_id = @mysqli_connect($db_ip[0], $this->conf['user'], $this->conf['pass'], $this->conf['dbname']);
        }
        
		if(!$this->db_id) {
            $this->stopConnected = true;
            
			if($show_error) $this->errors(@mysqli_connect_error(),0,'',true);
			
            return false;
		}
        else $this->connected = true;

		$this->mysql_version = @mysqli_get_server_info($this->db_id);

		@mysqli_query($this->db_id, "SET NAMES 'utf8'");

		return true;
	}
    function query($query,$show_error = true){
        
        if($this->check_ac()) return;
        if(!$this->connected) $this->connect($show_error);
        
        if($this->stopConnected) return;
        
		if(!($this->query_id = mysqli_query($this->db_id, $query) )) {
            if($show_error) $this->errors(mysqli_error($this->db_id), mysqli_errno($this->db_id), $query);
		}

        $this->query_num ++;
        
		return $this->query_id;
	}
    function select($tbl,$str = false,$show_error = true){
        if($this->check_ac()) return;

        $tbl = $this->prefix($tbl);
        
        if($this->dynamic) $this->createTable($tbl);
        
        $this->last_tbl_name = $tbl;
        
        if(!$str && !is_numeric($str)){
            $query = $this->query("SELECT * FROM ".$tbl);
        }
        elseif(is_numeric($str)){
            $query = $this->get_row($this->query("SELECT * FROM ".$tbl." WHERE id='".$str."'",$show_error));
        }
        elseif(is_array($str)){
            $sql = array_merge(['SELECT'=>'*'],$str);
            
            $where = ($sql['WHERE'] == '') ? ''  : " WHERE ".$sql['WHERE'];
    		$order = ($sql['ORDER'] == '') ? ''  : " ORDER BY ".$sql['ORDER'];
            $group = ($sql['GROUP'] == '') ? ''  : " GROUP BY ".$sql['GROUP'];
    		$limit = is_array($sql['LIMIT']) ? ' LIMIT '.ceil(((intval($sql['LIMIT'][0]) < 1 ? : intval($sql['LIMIT'][0]))-1) * intval($sql['LIMIT'][1])).','.intval($sql['LIMIT'][1]) : ($sql['LIMIT'] == '' ? ''  : " LIMIT ".intval($sql['LIMIT']));
            
            $where = ($sql['LIKE'] == '') ? $where  : (is_array($sql['LIKE']) ? " WHERE ".$sql['LIKE'][0]." LIKE '%".$this->escape($sql['LIKE'][1])."%'": $where);
            
    		$query = $this->query("SELECT ".$sql['SELECT']." FROM ".$tbl.$where.$order.$group.$limit,$show_error);
        }
        else{
            $query = $this->query("SELECT * FROM ".$tbl.($str !== '' ? ' WHERE '.$str : ''),$show_error);
        }
        return $query;
	}
    function insert($tbl,$sql,$show_error = true){
        if($this->check_ac()) return;
        
        $tbl_n = $tbl;
        $tbl   = $this->prefix($tbl);
        
        foreach($sql as $key=>$val){
            if($key == 'date' && empty($val)) $val = date( "Y-m-d H:i:s");
            $keys[] = '`'.$this->sl->fn->replase($key).'`';
            $values[] = "'".$this->escape($val)."'";
            $addRow[$key] = ['VARCHAR',250];
        }
        
        if($this->dynamic) $this->alterTableAdd($tbl_n,$addRow);

	    return $this->query("INSERT INTO ".$tbl." (".implode(',',$keys).") values (".implode(',',$values).")",$show_error);
	}
    function update($tbl,$sql,$where,$quote = true,$show_error = true){
        if($this->check_ac()) return;
        
        $tbl_n = $tbl;
        $tbl = $this->prefix($tbl);
        
        foreach($sql as $key=>$val){
            if($key == 'date' && empty($val)) $val = date( "Y-m-d H:i:s");
            if($quote) $values[] .= '`'.$this->sl->fn->replase($key).'`=\''.$this->escape($val).'\'';
			else $values[] .= '`'.$key.'`='.$this->escape($val);
            $addRow[$key] = ['VARCHAR',250];
        }
        
        if($this->dynamic) $this->alterTableAdd($tbl_n,$addRow);
        
		return $this->query("UPDATE ".$tbl." set ".implode(',',$values)." WHERE ".(is_numeric($where) ? "id='$where'" : $where),$show_error);
	}
    function delete($table,$where = false,$show_error = true){
        if($this->check_ac()) return;
		$this->query("DELETE FROM ".$this->prefix($table)." WHERE ".(is_numeric($where) ? "id='$where'" : ($where ? (is_array($where) ? "id IN (".implode(',',$where).")" : $where) : "id=".intval($this->insert_id()))),$show_error);
	}
    function count($tbl,$where = false){
        if($this->check_ac()) return;
        $where = is_array($where) ? array_merge(['SELECT'=>'COUNT(id) as count'],$where) : ['SELECT'=>'COUNT(id) as count','WHERE'=>$where];
        return intval($this->get_row($this->select($tbl,$where))['count']);
    }
    private function createTable($tblName){
        if($this->check_ac()) return;
        
        return $this->query("CREATE TABLE IF NOT EXISTS `".$tblName."`(id INT NOT NULL AUTO_INCREMENT,cid INT NOT NULL default '0',PRIMARY KEY(id)) ENGINE=InnoDB");
    }
    function alterTableAdd($tblName,$row){
        if($this->check_ac()) return;
        
        $tblName = $this->prefix($tblName);
        
        if($this->dynamic) $this->createTable($tblName);
        
        if(is_array($row)){
            foreach($row as $name=>$arr){
                $this->query("ALTER TABLE `$tblName` ADD `$name` $arr[0]".($arr[1] ? "($arr[1])" : '')." default '$arr[2]'",false);
            }
        }
    }
    function alterTableOnDelete($customers,$orders){
        if($this->check_ac()) return;
        
        if($customers == '' || $orders == '') return;
        
        $customers = $this->prefix($customers);
        $orders = $this->prefix($orders);
        
        if($this->dynamic){
            $this->createTable($customers);
            $this->createTable($orders);
        } 
        
        $this->query("ALTER TABLE $orders ADD FOREIGN KEY (cid) REFERENCES $customers(id) ON DELETE CASCADE",false);
    }
    function get_while($callback,$query_id = false){
        if($this->check_ac()) return;
        if(@get_class($query_id) == 'mysqli_result') {}
        elseif(is_array($query_id)) $query_id = $this->select($query_id[0],$query_id[1]);
        elseif($query_id) $query_id = $this->select($query_id);
        
        while($row = $this->get_row($query_id)){
            if(is_callable($callback)) $callback($row);
        }
    }
    function like($tbl,$row,$like,$lim = []){
        if($this->check_ac()) return;
        return $this->select($tbl,['WHERE'=>$row." LIKE '%".$this->escape($like)."%'",'LIMIT'=>$lim]);
    }
    function show_field($tbl){
        if($this->check_ac()) return;
        $this->query('SHOW COLUMNS FROM '.$this->prefix($tbl),false);
        
        while($row = $this->get_row()){
            $arr[] = $row['Field'];
        }
        
        return $arr ? $arr : [];
    }
    function get_row($query_id = false){
        if($this->check_ac()) return;
		return @mysqli_fetch_assoc($query_id ? $query_id : $this->query_id);
	}
	function get_array($query_id = false){
        if($this->check_ac()) return;
        return @mysqli_fetch_array($query_id ? $query_id : $this->query_id);
	}
    function num_rows($query_id = false){
        if($this->check_ac()) return;
        return @mysqli_num_rows($query_id ? $query_id : $this->query_id);
	}
    function insert_id(){
        if($this->check_ac()) return;
        return @mysqli_insert_id($this->db_id);
	}
    function escape($string){
        if($this->check_ac()) return;
        if(!$this->connected) $this->connect();
		return mysqli_real_escape_string ($this->db_id, $string);
	}
    function free($query_id = false){
        if($this->check_ac()) return;
        @mysqli_free_result($query_id ? $query_id : $this->query_id);
	}
    function close(){
        if($this->check_ac()) return;
		@mysqli_close($this->db_id);
	}
    function errors($error, $error_num, $query = false, $connect = false){
        
		if($query) $query = preg_replace("/([0-9a-f]){32}/", "********************************", $query);

        if($this->gAjax){
            if($connect) $this->sl->fn->info('<strong>MYSQL</strong>: '.$this->sl->fn->lang('Не настроено соединение с базой данных'));
            else $this->sl->fn->info('<strong>MYSQL</strong>: '.$error.' <br /><strong>QUERY['.$error_num.']</strong>: '.$query);
        } 
        else{
            if(!$this->connected) $this->error = $this->sl->stpl->mysql_error('<strong>MYSQL</strong>: '.$this->sl->fn->lang('Не настроено соединение с базой данных'));
            else $this->error = $this->sl->stpl->mysql_error('<strong>MYSQL</strong>: '.$error.' <br /><strong>QUERY['.$error_num.']</strong>: '.$query);
        }
        
        $bug = debug_backtrace();
            
        foreach($bug as $arr) $bug_arr[] = '[function::'.$arr['function'].'][line::'.$arr['line'].'][file::'.$arr['file'].']'."\n";
        
        $rev_bug = array_reverse($bug_arr);
        
        $this->sl->logs->db = $this->sl->logs->db."\n".'Record: '.date('l, j F Y H:i:s')."\n\n".implode($rev_bug);
    }
    function show(){
        if($this->sl->fn->check_ac('root')) return;
        
        $conf = $this->conf;
        
        $this->sl->blocks->add([
            'bg'=>'#f1f1f1 url(/modules/'.$this->modInfo[0].'/images/connect.png) no-repeat 50% 50%',
            'w'=>3,
            'h'=>2,
            'pad'=>10,
            'noresize'=>true,
            'class'=>'shadow_in'
        ]);
            
        if($this->connect(false)){
            $this->sl->blocks->add([
                'bg'=>'#55b00e url(/modules/'.$this->modInfo[0].'/images/symbol_check.png) no-repeat 50% 50%',
                'pad'=>10,
                'noresize'=>true
            ]);
        }
        else{
            $this->sl->blocks->add([
                'bg'=>'#c5350f url(/modules/'.$this->modInfo[0].'/images/symbol_error.png) no-repeat 50% 50%',
                'pad'=>10,
                'noresize'=>true
            ]);
        }
        
        $this->sl->blocks->sep();
        
        $lang = $this->sl->fn->lang([
            'Настроить',
            'Соединение',
            'Сохранить'
        ]);
        
        $this->sl->blocks->add([
            'pad'=>10,
            'html'=>'<h2 class="smooth color">'.$lang[0].'</h2><h2 class="t_size_3 smooth">'.$lang[1].'</h2>',
            'fun'=>"$.sl('_promt',{autoclose:0,module:'db',module:['/ajax/{$this->modInfo[0]}/newconnect'],input:[{value:'".$conf['ip']."',holder:'Localhost',name:'ip'},{value:'".$conf['user']."',holder:'User name',name:'user'},{value:'".$conf['pass']."',holder:'User password',name:'pass',type:'password'},{value:'".$conf['dbname']."',holder:'Db name',name:'dbname'},{value:'".$conf['prefix']."',holder:'Prefix',name:'prefix',regex:'[^a-z]'}],btn:{'$lang[2]':function(){ $.sl('shell',{name:'".$this->modInfo[0]."'},'update') }}})"
        ]);
        
        return '<div class="t_p_40">'.$this->sl->blocks->show().'</div>';
    }
}
?>