<?php
function getFilePath($path, $size = 'thumb'){
    $extArray = [
        'video' => 'mp4',
        'audio' => 'mp3, ogg, wma',
        'file'  => '',
        'document'=> '',
        'image' => 'jpg, jpeg, png, gif'
    ];
    $file = explode('.', $path);
    $ext = end($file);

    $x = array_filter($extArray, function($item) use ($ext){
        preg_match('/'.$ext.'/', $item, $matches);
        if(count($matches)>0) return true;
        else return false;
    });
    $type = array_keys($x)[0];
    
    $base = '/';
    //dd($type, $x, $ext, $extArray);
    if($type == 'image'){
        return $base.'_uploads/images/'.$size.'/'.$path;
    }else{
        return $base.$path;
    }
}

function getImagePath($path, $size = 'thumb'){
    return '/_uploads/images/'.$size.'/'.$path;
}

function getImage($path, $option=[]){
    $size = (isset($option['size'])) ? $option['size'] : 'thumb';
    $title = (isset($option['title'])) ? $option['title'] : $path;
    $className = (isset($option['class'])) ? $option['class'] : '';

    $str = '<img src="'.getImagePath($path, $size).'" alt="'.$title.'" title="'.$title.'" class="'.$className.'"/>';
    return $str;
}
