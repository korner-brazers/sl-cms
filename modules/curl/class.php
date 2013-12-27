<?
/**
 * @curl
 * @author korner
 * @copyright SL-SYSTEM 2012
 */

class curl{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;        
    }
    var $SET = array(
        'TIMEOUT'=>20,
        'RETURNTRANSFER'=>1,
        'FOLLOWLOCATION'=>1,
        'HEADER'=>0,
        'REFERER'=>false,
        'AUTOREFERER'=>false,
        'ENCODING'=>'gzip',
        'COOKIE'=>false,
        'USERAGENT'=>"Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8)",
        'PROXY'=>false,
        'CONVERT'=>false,
        'REMOVE_SCRIPT'=>true,
        'BASE'=>false,
        'ERROR'=>true
    );
    
    function get($url,$mass = array()){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $POST = (isset($mass['POST'])) ? $mass['POST'] : false;
        $COOKIE = (isset($mass['COOKIE'])) ? $mass['COOKIE'] : false;
        
        $ch = @curl_init($this->urlencode($url));
        //curl_setopt($ch, CURLOPT_URL, $url);
        
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->SET['RETURNTRANSFER']);
        @curl_setopt($ch, CURLOPT_HEADER, $this->SET['HEADER']);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->SET['FOLLOWLOCATION']);
        
        @curl_setopt($ch, CURLOPT_REFERER, ($this->SET['REFERER'] ? $this->SET['REFERER'] : $this->urlencode($url)));
        
        if($this->SET['AUTOREFERER']) curl_setopt($ch, CURLOPT_AUTOREFERER, $this->SET['AUTOREFERER']);
        
        @curl_setopt($ch, CURLOPT_ENCODING, $this->SET['ENCODING']);
        @curl_setopt($ch, CURLOPT_USERAGENT, $this->SET['USERAGENT']);
        @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->SET['TIMEOUT'] );
        @curl_setopt($ch, CURLOPT_TIMEOUT, $this->SET['TIMEOUT']);
        
        if($this->SET['BASE']){
            @curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
            @curl_setopt($ch,CURLOPT_USERPWD,":"); 
        }
        
        if($POST){
            @curl_setopt($ch, CURLOPT_POST, 1);
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
        }
        
        if($this->SET['COOKIE']){
            @curl_setopt($ch, CURLOPT_COOKIEJAR, $this->SET['COOKIE']);
            @curl_setopt($ch, CURLOPT_COOKIEFILE,$this->SET['COOKIE']);
        }
        
        if($this->SET['PROXY']){
            @curl_setopt($ch, CURLOPT_PROXY, $this->SET['PROXY']['ip']);
            @curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->SET['PROXY']['login'].':'.$this->SET['PROXY']['password']);
        }
        
        $data = @curl_exec($ch);
        $header = @curl_getinfo($ch);
        
        $ur = ['content'=>$data,'header'=>$header,'error'=>''];
        
        if (@curl_errno($ch) && $this->SET['ERROR']){
            $ur['error'] = @curl_error($ch);
        }
        
        @curl_close($ch);
        
        if($this->SET['CONVERT']){
            $in_charset = $this->SET['CONVERT'][0];
            
            if (preg_match('/charset=([^ ]*)[\"\']/i', $ur['content'], $response)) $in_charset = $response[1];
    	    if(stristr($in_charset,$this->SET['CONVERT'][1])) $ur['content'] = mb_convert_encoding($ur['content'], 'windows-1251', $in_charset);
        }
        
        if($this->SET['REMOVE_SCRIPT']) $ur['content'] = preg_replace("'<script[^>]*>.*?<\/script>'si",'',$ur['content']);
        
        return $ur;
    }
    
    private function urlencode($url){
        $purl = parse_url($url);
        
        if(!$purl['host']) return 'http://'.$url;
        else return $url;
    }
    
    function m_get($urls = [],$mass = array()){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $urls = is_array($urls) ? $urls : [];
        
        $result = [];
        $curl   = [];  
        $mh     = curl_multi_init();
        
        foreach($urls as $key => $val){
            if (empty($val)) unset($urls[$key]);
        }
 
        foreach ($urls as $id => $url) 
        { 
            $curl[$id] = curl_init(); 
            curl_setopt($curl[$id], CURLOPT_URL, $this->urlencode($url)); 
            curl_setopt($curl[$id], CURLOPT_HEADER, $this->SET['HEADER']);
            curl_setopt($curl[$id], CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($curl[$id], CURLOPT_TIMEOUT, $this->SET['TIMEOUT']); 
            curl_setopt($curl[$id], CURLOPT_FOLLOWLOCATION, $this->SET['FOLLOWLOCATION']);
            curl_setopt($curl[$id], CURLOPT_USERAGENT, $this->SET['USERAGENT']);
            curl_multi_add_handle($mh, $curl[$id]); 
        } 

        $running = null; 
        do{
            curl_multi_exec($mh, $running); 
            sleep(1);
        } 
        while($running > 0); 

        foreach($curl as $id => $c) 
        { 
            $result[$id]['content'] = curl_multi_getcontent($c); 
            $result[$id]['header'] =  curl_getinfo($c); 
            curl_multi_remove_handle($mh, $c); 
            
        } 
        
        curl_multi_close($mh);
        
        return $result;
    }
}
?>