<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class lang{
    private $ser = [];
    private $langSel = ['Без перевода','ru-en','ru-uk','ru-pl','ru-tr','ru-de'];
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->confLang = SL_DATA.DIR_SEP.'lang'.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
        
        @mkdir(SL_DATA.DIR_SEP.'lang');
    }
    function init_member(){
        $conf = $this->sl->fn->conf('get',$this->confLang);
        
        $this->langActive = intval($conf['lang']);
        $this->langTranslate = intval($conf['translate']);
        
        if(isset($_COOKIE['lang'])){
            $this->langTranslate = intval($_COOKIE['lang']);
        }
        
        if($this->langActive !== 0 && $this->langTranslate){
            
            $this->conf = SL_DATA.DIR_SEP.'lang'.DIR_SEP.MULTI_DN.'_'.$this->langSel[$this->langActive];
            
            $arr = $this->sl->fn->conf('get',$this->conf);
        
            foreach($arr as $id=>$ar) $this->ser[$ar['in']] = $ar['out'];
        }
        elseif($this->langActive > 0) $this->conf = SL_DATA.DIR_SEP.'lang'.DIR_SEP.MULTI_DN.'_'.$this->langSel[$this->langActive];
    }
    function cookie(){
        $co = intval($_COOKIE['lang']);
        $co = $co >= 1 ? 0 : 1;
        $this->sl->auth->cookie("lang", $co, 360);
    }
    private function createTable(){
        if($this->sl->db->connect(false)){
            $this->sl->db->alterTableAdd('lang',[
                'hash'=>['VARCHAR',32],
                'inlang'=>['TEXT NOT NULL',false],
                'outlang'=>['TEXT NOT NULL',false],
            ]);
        }
    }
    function outBig($lang = '',$showEdit = false){
        if($this->modInfo[5]) return;
        
        if(empty($lang) || is_array($lang)) return $lang;
        
        if($this->sl->db->connect(false)){
            $this->createTable();
        
            $translate = $this->sl->db->get_row($this->sl->db->select('lang','cid='.intval($this->langActive).' AND hash="'.md5($lang).'"'));
            
            if($translate){
                if($this->sl->fn->check_ac('admin',false) || !$showEdit) return ($this->langTranslate ? $translate['outlang'] : $translate['inlang']);
                else return '<span class="'.$this->modInfo[0].'EditSpan">'.($this->langTranslate ? $translate['outlang'] : $translate['inlang']).'<span class="'.$this->modInfo[0].'EditBtn" id="'.$translate['id'].'"></span></span>';
            } 
            else{
                if(intval($this->langActive) > 0){
                    $this->sl->db->insert('lang',[
                        'hash'=>md5($lang),
                        'inlang'=>$lang,
                        'cid'=>intval($this->langActive),
                        'outlang'=>$lang
                    ]);
                }
            }
        }
        
        return $lang;
    }
    function out($lang = ''){
        if($this->modInfo[5]) return;
        
        $ifAr = is_array($lang) ? true : false;
        $ll = $tr = $iss = $rear = [];
        $q  = '';
        
        if($ifAr){
            foreach($lang as $str){
                $str = strip_tags(trim($str));
                
                if(!empty($str)){
                    if(!$this->ser[$str]) $ll[] = $str;
                } 
            } 
        }
        else{
            if(!empty($lang)){
                $lang = strip_tags(trim($lang));
                
                if($this->ser[$lang]) return $this->ser[$lang];
                else{
                    $ll[] = $lang;
                    $lang = [$lang];
                }
            }
            else return $lang;
        }
        
        if(count($ll) > 0 && $this->langActive > 0 && $this->langTranslate){
            
            foreach($ll as $s) $q .= '&text='.urlencode(substr($s,0,500));
            
            $co = $this->sl->curl->get('http://translate.yandex.net/api/v1/tr.json/translate?lang='.$this->langSel[intval($this->langActive)].$q);
            
            $json = json_decode($co['content'],true);
            
            if($json){
                if($json['code'] == 200){
                    foreach($json['text'] as $i=>$out){
                        $newLang[md5(time().rand(999999,999999999))] = [
                            'in'=>$ll[$i],
                            'out'=>$out
                        ];
                        
                        $this->ser[$ll[$i]] = $out;
                    }
                    
                    if($newLang) $this->sl->fn->conf('update',$this->conf,$newLang);
                }
            }
        }
        
        foreach($lang as $str){
            if($this->ser[$str]) $rear[] = $this->ser[$str];
            else $rear[] = $str;
        }
        
        return $ifAr ? $rear : $rear[0];
    }
    function edit($id,$s = false){
        $in = $this->sl->fn->conf('get',$this->conf);
        
        if($s){
            if($this->sl->fn->check_ac('admin')) return;
            
            if(!$in[$id]) $this->sl->fn->info($this->out('Ошибка данных'));
            
            $var = strip_tags(trim($_POST['area']));
            
            $in[$id]['out'] = $var;
            
            $this->sl->fn->conf('update',$this->conf,[$id=>$in[$id]]);
            
            return $var;
        }
        
        return $in[$id]['out'];
    }
    function editBig($id,$s = false){
        $in = $this->sl->db->select('lang',intval($id));
        
        if($s){
            if($this->sl->fn->check_ac('admin')) return;
            
            if(!$in) $this->sl->fn->info($this->out('Ошибка данных'));
            
            $var = $this->sl->fn->substr($_POST['area'],0,100);
            
            $this->sl->db->update('lang',['outlang'=>trim($_POST['area'])],intval($id));
            
            return $var;
        }
        
        return $in['outlang'];
    }
    function delete($id){
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->fn->conf('delete',$this->conf,$id);
    }
    function deleteBig($id){
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete('lang',intval($id));
    }
    function settings($set = false){
        if($this->sl->fn->check_ac('admin')) return;
        
        $conf = $this->sl->fn->conf('get',$this->confLang);
        
        if($set){
            $sel = intval($_POST['lang']);
            
            if(!$this->langSel[$sel]) $sel = 0;
            
            $conf = [
                'lang'=>$sel,
                'translate'=>intval($_POST['translate'])
            ];
            
            $this->sl->fn->conf('set',$this->confLang,$conf);
            
            return;
        }
        
        $lang = $this->out([
            'Язык',
            'Перевод',
            'Да',
            'Нет',
            'Без перевода'
        ]);
        
        $this->langSel[0] = $lang[4];
        
        $this->sl->scin->table_td_op(0,100);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->select('lang',$this->langSel,$conf['lang']),
            $lang[0]
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->radio('translate',[$lang[3],$lang[2]],$conf['translate'],['reverse'=>true]),
            $lang[1]
        ]);
        
        $this->sl->scin->table();
        
        return $this->sl->scin->table_display();
    }
    function showOutAdmin($page = 1,$like = 0){
        if($this->sl->fn->check_ac('admin')) return;
        
        $page = intval($page);
        
        $tran = $this->out([
            'Слово',
            'Перевод',
            'Поиск',
            'Настройки',
            'Удалить',
            'Ошибка данных',
            'Найти',
            'Сохранить'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $tran[2]=>"$.sl('_promt',{title:'{$tran[2]}',btn:{'{$tran[6]}':function(p,v){
                $('#{$this->modInfo[0]}AdminSmall').sl('load','/ajax/{$this->modInfo[0]}/showOutAdmin/$page/'+v[0].value,function(){ $.sl('update_scroll') })
            }},input:['test']}); return; l",
            $tran[3]=>"$.sl('_window_setting',{load:['/ajax/{$this->modInfo[0]}/settings'],module:['/ajax/{$this->modInfo[0]}/settings/1','',function(){ $.sl('shell',{name:'{$this->modInfo[0]}',add_param:'$page/$like'},'update'); }],bg:false,h: 102}); return; l"
        ]));
        
        $this->sl->scin->table_td_op(20,300,0,120);
        $this->sl->scin->table_head('',$tran[0],$tran[1],'');
        
        $this->sl->scin->table_td_add_op([['class'=>'dark t_center'],['class'=>'light']]);
        
        $conf = $this->sl->fn->conf('get',$this->conf);
        
        $lim = [$page,25,"$('#{$this->modInfo[0]}AdminSmall').sl('load','/ajax/{$this->modInfo[0]}/showOutAdmin/{n}/$like',function(){ $.sl('update_scroll') })"];
        
        if($like){
            foreach($conf as $i=>$ar){
                if(stristr($ar['out'],trim($like)) || strstr($ar['in'],trim($like))){
                    $newConf[$i] = [
                        'in'=>$ar['in'],
                        'out'=>$ar['out']
                    ];
                }
            }
            
            if($newConf) $conf = $newConf;
            else $conf = [];
        }
        
        $nar = $this->sl->fn->arrayID($conf);
        
        $this->sl->scin->table_dynamic([
            ['&#8801;'],
            'in'=>['<b>','</b>'],
            'out'=>['<b>','</b>']
        ],[
            $tran[4]=>['/ajax/'.$this->modInfo[0].'/delete'],
            '&#926;'=>[3=>['onclick'=>"{$this->modInfo[0]}Edit.apply(this,['{id}'])"]]
        ],$nar,$lim);
        
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return '<div id="'.$this->modInfo[0].'AdminSmall">'.$this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__,['langBtn'=>$tran[7]]).'</div>';
    }
    function showOutBigAdmin($page = 1,$like = 0){
        if($this->sl->fn->check_ac('admin')) return;
        if(!$this->sl->db->connect(false)) return;
        
        $this->sl->scin->table_clear();
        
        $tran = $this->out([
            'Слово',
            'Перевод',
            'Поиск',
            'Настройки',
            'Удалить',
            'Ошибка данных',
            'Найти',
            'Сохранить'
        ]);
        
        $page = intval($page);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $tran[2]=>"$.sl('_promt',{title:'{$tran[2]}',btn:{'{$tran[6]}':function(p,v){
                $('#{$this->modInfo[0]}AdminBig').sl('load','/ajax/{$this->modInfo[0]}/showOutBigAdmin/$page/'+v[0].value,function(){ $.sl('update_scroll') })
            }},input:['test']}); return; l"
        ]));
        
        $this->sl->scin->table_td_op(20,300,0,120);
        $this->sl->scin->table_head('',$tran[0],$tran[1],'');
        
        $this->sl->scin->table_td_add_op([['class'=>'dark t_center'],['class'=>'light']]);
        
        $conf = $this->sl->fn->conf('get',$this->conf);
        
        $lim = [$page,25,"$('#{$this->modInfo[0]}AdminBig').sl('load','/ajax/{$this->modInfo[0]}/showOutBigAdmin/{n}/$like',function(){ $.sl('update_scroll') })"];
        $query = ['LIMIT'=>$lim,'ORDER'=>'id DESC','WHERE'=>'cid='.intval($this->langActive)];
        
        if($like){
            $query['LIKE'] = ['inlang',$like];
            $lim[] = ['LIKE'=>$query['LIKE']];
        } 
        
        $select = $this->sl->db->select('lang',$query);
        
        $this->sl->scin->table_dynamic([
            ['&#8801;'],
            'inlang'=>function($v){
                return '<b>'.$this->sl->fn->substr($v,0,100).'</b>';
            },
            'outlang'=>function($v){
                return '<b>'.$this->sl->fn->substr($v,0,100).'</b>';
            }
        ],[
            $tran[4]=>['/ajax/'.$this->modInfo[0].'/deleteBig'],
            '&#926;'=>[3=>['onclick'=>"{$this->modInfo[0]}Edit.apply(this,['{id}',true])"]]
        ],$select,$lim,'lang');
        
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return '<div id="'.$this->modInfo[0].'AdminBig">'.$this->sl->scin->table_display().'</div>';
    }
    function show(){
        $this->createTable();
        
        $tran = $this->out([
            'Простые слова',
            'Большой текст'
        ]);
        return $this->sl->scin->slide([$tran[0]=>$this->showOutAdmin(),$tran[1]=>$this->showOutBigAdmin()]);
    }
}
?>