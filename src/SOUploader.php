<?php 
namespace SoftoneAsia\LaravelUpload;

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
    'allowType' => [
        'jpg', 'png', 'gif'
    ],
    'maxSize'   => '2',
    'rename'    => true
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
        //Khởi tạo thư mục
        self::makeDirByType(_CONFIG['outputType']);
        if(file_exists(_CONFIG['outputDir'])){
            self::makeDirBySize(_CONFIG['outputSize']);
        }else{
            mkdir(_CONFIG['outputDir'], 0777, true);
            self::makeDirBySize(_CONFIG['outputSize']);
        }
    }

    public static function SOTest(){
        self::init();
    }

    public static function upload($file, $option = []){
        self::init();
        $outputType = '';
        $outputName = '';
        $option = [];
        dd($file, $option);
    }
}
