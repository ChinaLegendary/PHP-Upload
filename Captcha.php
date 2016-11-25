<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Captcha
 *
 * @author Administrator
 */
//专门处理验证码
class Captcha {
//    1.字体文件
    private $fontFile='font/simkai.ttf';
//    2.字体大小
    private $fontSize = 30;
//    3.验证码宽度
    private $width = 100;
//    4.验证码高度
    private $height = 40;
//    5.验证码长度
    private $length = 4;
//    6.点的个数
    private $pixel = 100;
//    7.线段的个数
    private $line = 3;
//    8.*的个数
    private $snow = 4;
//    9.验证码图片
    private $image = null;
    
    public function __construct($config=[]) {
        echo '开始初始化验证码'."<hr>";
        if(isset($config['fontFile'])&&
                is_readable($config['fontFile'])&& 
                is_file($config['fontFile'])){
            $this->fontFile = $config['fontFile'];
        }
            if (isset($config['fontSize']) && is_numeric($config['fontSize']) && $config['fontSize']>0) {
                $this->fontSize = $config['fontSize'];
            }
            
            if (isset($config['width']) && 
                    is_numeric($config['width']) &&
                    $config['width'] > 0) {
                $this->width = $config['width'];
            }
            
            if (isset($config['height']) && 
                    is_numeric($config['height']) &&
                    $config['height'] > 0) {
                $this->height = $config['height'];
            }
            if (isset($config['length']) && 
                    is_numeric($config['length']) &&
                    $config['length'] > 0) {
                $this->length = $config['length'];
            }
            
            if (isset($config['pixel']) && 
                    is_numeric($config['pixel']) &&
                    $config['pixel'] > 0) {
                $this->pixel = $config['pixel'];
            }
            
            if (isset($config['line']) && 
                    is_numeric($config['line']) &&
                    $config['line'] > 0) {
                $this->line = $config['line'];
            }
            
            if (isset($config['snow']) && 
                    is_numeric($config['snow']) &&
                    $config['snow'] > 0) {
                $this->snow = $config['snow'];
            }
        
        $this->image = imagecreatetruecolor($this->width, $this->height);
    }
    
//    10获取验证码
    public function getCaptcha(){
        $white = imagecolorallocate($this->image, 255, 255, 255);
//        填充画布背景为白色
        imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $white);
//        开始绘制验证码
        $code = '';
        for($i=0;$i<$this->length;$i++){
            $angle = mt_rand(-30,30);
            $x = $this->width/$this->length * $i;
            $y = $this->height/2+10;
            $color = $this->createRandColor();
            $text = $this->createRandStr();
            $code .=$text;
            imagettftext($this->image, $this->fontSize, $angle, $x, $y, $color, $this->fontFile, $text);
        }
        for($i=0;$i<$this->snow;$i++) {
            imagestring($this->image, mt_rand(0, 10), mt_rand(0, $this->width), mt_rand(0, $this->height), "*", $this->createRandColor());
        }
        for($i=0;$i<$this->pixel;$i++) {
            imagesetpixel($this->image,  mt_rand(0, $this->width), mt_rand(0, $this->height),$this->createRandColor());
        }
        for($i=0;$i<$this->line;$i++) {
            imageline($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height),mt_rand(0, $this->width), mt_rand(0, $this->height), $this->createRandColor());
        }
        // 显示到屏幕上
        
        session_start();
        $_SESSION['randCode'] = $code;
       
        ob_clean();
        header("Content-type:image/png");
        imagepng($this->image);
        imagedestroy($this->image); 
        
    }
//    11生成随机色
    private function createRandColor(){
        return imagecolorallocate($this->image, mt_rand(0,255), mt_rand(0, 255), mt_rand(0, 255));
    }
    
//    12生成随机字符串
    private function createRandStr(){
        $codes = ['a','b','c','d','e','f','g','h','j','k','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z',2,3,4,5,6,7,8,9
            ];
        return $codes[mt_rand(0, count($codes)-1)];        
    }
    
}
