<?
/**
 * @crop_img
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class crop_img{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->dir = SL_UPLOAD.DIR_SEP.'images';
        $this->altDir = '/uploads/images/';
    }
    function __call($method,$arr){
        
    }
    private function crop($fileName,$thumb_width,$thumb_height,$ex){
        
        if($fileName == 'no_image' && !file_exists($this->dir.DIR_SEP.$fileName.'.'.$ex)) return;
        
        $resultName = $this->dir.DIR_SEP.$fileName.'_'.$thumb_width.'x'.$thumb_height.'.'.$ex;
        $altResultName = $fileName.'_'.$thumb_width.'x'.$thumb_height.'.'.$ex;
        $thisName = $this->dir.DIR_SEP.$fileName.'.'.$ex;
        
        if(file_exists($resultName)) return $altResultName;
        
        if(!file_exists($thisName)) return $this->crop('no_image',$thumb_width,$thumb_height);
        
        if($ex == 'jpg') $image = imagecreatefromjpeg($thisName);
        elseif($ex == 'png') $image = imagecreatefrompng($thisName);    
        
        $width  = imagesx($image);
        $height = imagesy($image);
                
        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;
    
        if($original_aspect >= $thumb_aspect) {
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        }
        else{
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }
                
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        
        if($ex == 'png') {
			imagealphablending( $thumb, false);
			imagesavealpha( $thumb, true);
		}
        
        imagecopyresampled($thumb,
                       $image,
                       0 - ($new_width - $thumb_width) / 2,
                       0 - ($new_height - $thumb_height) / 2,
                       0, 0,
                       $new_width, $new_height,
                       $width, $height);
        
        
        if($ex == 'jpg') imagejpeg($thumb,$resultName,100);
        elseif($ex == 'png') imagepng($thumb,$resultName);
        
        imagedestroy($thumb);
        
        return $altResultName;
    }
    private function cropWidth($fileName,$thumb_width,$ex){
        
        if($fileName == 'no_image' && !file_exists($this->dir.DIR_SEP.$fileName.'.'.$ex)) return;
        
        $resultName = $this->dir.DIR_SEP.$fileName.'_'.$thumb_width.'.'.$ex;
        $altResultName = $fileName.'_'.$thumb_width.'.'.$ex;
        $thisName = $this->dir.DIR_SEP.$fileName.'.'.$ex;
        
        if(file_exists($resultName)) return $altResultName;
        
        if(!file_exists($thisName)) return $this->cropWidth('no_image',$thumb_width);
        
        if($ex == 'jpg') $image = imagecreatefromjpeg($thisName);
        elseif($ex == 'png') $image = imagecreatefrompng($thisName);
                
        $width = imagesx($image);
        $height = imagesy($image);
                
        $ratio = $thumb_width / $width;
        $height_size = $height * $ratio;
                
        $new_image = imagecreatetruecolor($thumb_width, $height_size);
        
        if($ex == 'png') {
			imagealphablending( $new_image, false);
			imagesavealpha( $new_image, true);
		}
                
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $thumb_width, $height_size, $width, $height);
        
        if($ex == 'jpg') imagejpeg($new_image,$resultName,100);
        elseif($ex == 'png') imagepng($new_image,$resultName);
        
        imagedestroy($new_image);
        
        return $altResultName;
    }
    private function checkAndJoin($fileName){
        $ex = explode('.',$fileName);
        $end = end($ex);
        unset($ex[count($ex)-1]);
        $name = implode('.',$ex);
        return [$name,$end];
    }
    function size($fileName,$thumb_width,$thumb_height){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $thumb_width  = intval($thumb_width)  < 16 ? 16 : $thumb_width;
        $thumb_height = intval($thumb_height) < 16 ? 16 : $thumb_height;
        $check = $this->checkAndJoin($fileName);
        if($check[1] == 'jpg' || $check[1] == 'png') return $this->crop($check[0],$thumb_width,$thumb_height,$check[1]);
        else return $fileName;
    }
    function width($fileName,$thumb_width){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $thumb_width  = intval($thumb_width)  < 16 ? 16 : $thumb_width;
        $check = $this->checkAndJoin($fileName);
        if($check[1] == 'jpg' || $check[1] == 'png') return $this->cropWidth($check[0],$thumb_width,$check[1]);
        else return $fileName;
    }
}
?>