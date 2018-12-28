<?php 
namespace SoftoneAsia\LaravelUpload;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

define("_CONFIG",array(
    'outputType' => [
        'image',
        'photo',
        'video',
        'document'
    ],
    'outputSize' =>  [
        'thumb' => [
            'w' => 150, 'h'=> 150
        ],
        'medium' => [
            'w' => 300, 'h'=> 300
        ],
        'medium_large' => [
            'w' => 768, 'h'=> 768
        ],
        'large' => [
            'w' => 1024, 'h'=> 1024
        ]
    ],
    'outputDir' => '_uploads',
    'maxSize'   => [
        'image' => 2,
        'photo' => 10,
        'video' => 200,
        'document'  => 20
    ],
    'rename'    => true
));

define("_MIMETYPE", array(
    'file'=>[
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv'
    ],

    // images
    'image'=>[
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml'
    ],

    // archives
    'archive'=>[
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed'
    ],

    // audio/video
    'video'=>[
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4'
    ],

    'audio'=>[
        'flac' => 'audio/flac',
        'm3u'  => 'audio/mpegurl',
        'm3u8' => 'audio/mpegurl',
        'm4a'  => 'audio/mp4',
        'm4b'  => 'audio/mp4',
        'mp3'  => 'audio/mpeg',
        'ogg'  => 'audio/ogg',
        'opus' => 'audio/ogg',
        'pls'  => 'audio/x-scpls',
        'wav'  => 'audio/wav'
    ],

    // adobe
    'adobe'=>[
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript'
    ],

    // ms office
    'ms-office' => [
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint'
    ],

    // open office
    'open-office'=>[
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
    ],
    'non-audio'=>[
        'webm' => 'audio/webm',
        'wma' => 'audio/x-ms-wma',
        'xspf' => 'application/xspf+xml'
    ]
));

class SOUploader {
    private static function makeDirByType($types){
        foreach($types as $type){
            $outputDir = _CONFIG['outputDir'].'/'.$type.'s';
            if(!file_exists($outputDir)){
                mkdir($outputDir, 0777, true);
            }
        }
    }
    
    private static function makeDirBySize($sizes){
        foreach($sizes as $key => $val){
            $outputDir = _CONFIG['outputDir'].'/images/'.$key;
            if(!file_exists($outputDir)){
                mkdir($outputDir, 0777, true);
            }
        }
    }
    
    private static function init() {
        //Khởi tạo thư mục theo loại
        // self::makeDirByType(_CONFIG['outputType']);
        // if(!file_exists(_CONFIG['outputDir'])){
        //     mkdir(_CONFIG['outputDir'], 0777, true);
        // }
    }

    private static function allowFileType($file, $type){
        $x = array_filter(_MIMETYPE[$type], function($item) use ($file){
            return $item == $file->getMimeType();
        });
        //dd($file, $type, $x);
        if(count($x)>0) return true;
        else return false;
    }

    private static function allowFileSize($file, $type){
        $filesize = $file->getClientSize() / 1024 / 1024;
        if($filesize <= _CONFIG['maxSize'][$type]) return true;
        else return false;
    }

    private static function uploadImage($file, $option){
        $dir = _CONFIG['outputDir'].'/images';
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }
        //dd($oName, $oMime, $file, $option, _CONFIG);
        
        //Tạo tên file output
        if($option['name'])
            $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $option['name']);
        else{
            if(_CONFIG['rename'])
                $fileName = time().'_'.rand(10,99);
            else
                $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $oName);
        }
        
        //Thay đổi tên file nếu tên file đã tồn tại
        if($option['overwrite'])
        {
            $filePath = "{$fileName}.".$file->getClientOriginalExtension();
            // if(File::exists("{$dir}/{$fileName}")) {
            //     File::delete("{$dir}/{$fileName}");
            // }
        }
        else
        {
            $filePath = "{$fileName}_".date('Y_m_d_').time().".".$file->getClientOriginalExtension();
        }
        
        //Check Allow Condition Upload
        $error = [];
        if(!self::allowFileSize($file, $option['type']))
            $error[] = __('soupload.filesize_validate');
        if(!self::allowFileType($file, $option['type']))
            $error[] = __('soupload.filetype_validate');

        if(count($error)==0){
            //Tạo file theo các kích cỡ
            foreach(_CONFIG['outputSize'] as $key => $size){
                $outputDir = $dir.'/'.$key.'/'.date('Y').'/'.date('n').'/';
                if(!file_exists($outputDir)){
                    mkdir($outputDir, 0777, true);
                }
                Image::make($file)->resize($size['w'], null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($outputDir.$filePath);
            }

            $dirOriginal = $dir.'/original/'.date('Y').'/'.date('n');
            if(!file_exists($dirOriginal)){
                mkdir($dirOriginal, 0777, true);
            }
            Image::make($file)->save($dirOriginal.'/'.$filePath);

            return ['status'=>true, 'filename'=>$filePath, 'fullpath'=>date('Y').'/'.date('n').'/'.$filePath];
        }else{
            return ['status'=>true, 'error'=>$error];
        }
    }

    private static function uploadFile($file, $option){
        $dir = _CONFIG['outputDir'].'/'.$option['type'].'s';
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }
        //dd($oName, $oMime, $file, $option, _CONFIG);
        
        //Tạo tên file output
        if($option['name'])
            $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $option['name']);
        else{
            if(_CONFIG['rename'])
                $fileName = time().'_'.rand(10,99);
            else
                $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $file->getClientOriginalName());
        }
        
        //Thay đổi tên file nếu tên file đã tồn tại
        if($option['overwrite'])
        {
            $filePath = "{$fileName}.".$file->getClientOriginalExtension();
            // if(File::exists("{$dir}/{$fileName}")) {
            //     File::delete("{$dir}/{$fileName}");
            // }
        }
        else
        {
            $filePath = "{$fileName}_".date('Y_m_d_').time().".".$file->getClientOriginalExtension();
        }
        
        //Check Allow Condition Upload
        $error = [];
        if(!self::allowFileSize($file, $option['type']))
            $error[] = __('soupload.filesize_validate');
        if(!self::allowFileType($file, $option['type']))
            $error[] = __('soupload.filetype_validate');

        if(count($error) == 0){
            //dd($dir.'/'.$filePath);
            $outputDir = $dir.'/'.date('Y').'/'.date('n').'/';
            if(!file_exists($outputDir)){
                mkdir($outputDir, 0777, true);
            }
            //dd($file->getPathName());
            move_uploaded_file($file->getPathName(), $outputDir.$filePath);
            return ['status'=>true, 'filename'=>$filePath, 'fullpath'=> $outputDir.$filePath];
        }else{
            return ['status'=>true, 'error'=>$error];
        }
    }

    public static function SOTest(){
        self::init();
    }

    public static function upload($file, $type = 'image', $name = false, $overwrite = true){
        self::init();
        $option = [
            'type' => $type,
            'name' => $name,
            'overwrite' => $overwrite
        ];
        $result = [];
        switch($type){
            case 'image': $result = self::uploadImage($file, $option); break;
            case 'audio': $result = self::uploadFile($file, $option); break;
            case 'video': $result = self::uploadFile($file, $option); break;
            default: $result = self::uploadFile($file, $option);
        }
        
        return $result;
    }
}
