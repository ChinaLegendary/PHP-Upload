<?php
    //    主要用于上传图片
    class Upload {
        // 1. 只能上传jpg,jpeg,png,gif,bmp等格式的图片
        private $allowExt = array('jpg','jpeg','png','gif','bmp');
        // 2. 限制上传图片的单张大小 小于 2048 = 2MB
        // 注意:单位是B->KB->MB
        private $maxSize = 1024*1024*2;
        // 3. 判断一下资源是否真的是图片     a.txt ==> a.png
        // 默认情况下不检测图片的真假
        private $checkTrueImg = false;
        // 4. 设置图片的保存路径
        private $path = 'myUploadImg';
        // 5. 保存每个文件的基本信息 name tmp_name type error size
        private  $files = [];
        
        // 6.前5步都是默认值,如果用户单独设置,就使用用户的数据
        // 如果给参数,使用用户的;如果没参数使用默认的
        public function __construct($config=[]) {
            if(isset($config['allowExt']) && is_array($config['allowExt'])) {
                // 用户自定义上传格式  exe, xmind, ppt
                // 合并数组
                $this->allowExt = array_merge($this->allowExt, $config['allowExt']);
            }
            
            if(isset($config['maxSize']) && is_numeric($config['maxSize'])) {
                $this->maxSize = $config['maxSize'];
            }
            
            if(isset($config['checkTrueImg']) && is_bool($config['checkTrueImg'])) {
                $this->checkTrueImg = $config['checkTrueImg'];
                
            }

            if (isset($config['path'])) {
                $this->path = $config['path'];
            }
            
            //7.组装文件数据：统一所有文件的格式，让他们保持一致
            $this->initFiles();
        }
        private function initFiles(){
//            声明一个数组 用于保存组装后的文件数据
            $filesInfo = [];
            foreach ($_FILES as $key => $value){
                if(is_array($value['name'])){
                    echo '多文件'."<hr>";
                    foreach ($value['name'] as $key1 => $value1) {
                        $filesInfo[$key1]['name'] = $value['name'][$key1];
                        $filesInfo[$key1]['type'] = $value['type'][$key1];
                        $filesInfo[$key1]['tmp_name'] = $value['tmp_name'][$key1];
                        $filesInfo[$key1]['error'] = $value['error'][$key1];
                        $filesInfo[$key1]['size'] = $value['size'][$key1];
                    }
                }  else {
                    echo '单文件'."<hr>";
                    $filesInfo[] = $value;
                }
            }
            $this->files = $filesInfo;            
//            var_dump($this->files);
        }
        
//        8.开始上传文件
//        准备插入到数据库的文件路径
        public $uploadFilesPath = [];
        public function uploadFile(){
                echo '开始上传文件'."<hr>";
    //            1.检测上传文件是否有错误, error 的值 如果是0成功  1,2,3
    //            2.检测上传文件的格式是否符合要求
    //            3,检测上传文件的大小是否符合要求
    //            4.是否开启"检测是否为图片"的选项
    //            5.检测是否利用HttpPOST上传:如果不是通过input标签上传,不应该上传到服务器
    //            6.如果以上5个情况都满足,保存数据,否则打印错误提示
                foreach ($this->files as $key => $value) {
                    switch ($value['error']) {
                        case 0:
                            echo $value['name'].'没有错误发生，文件上传成功'."<hr>";
                            break;
                        case 1:
                            echo $value['name'].'上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值'.'<hr>';
                            break;
                        case 2:
                            echo $value['name'].'上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值'.'<hr>';
                            break;
                        case 3:
                            echo $value['name'].'文件只有部分被上传'.'<hr>';
                            break;
                        case 4:
                            echo $value['name'].'没有文件被上传'.'<hr>';
                            break;
                        case 6:
                            echo $value['name'].'找不到临时文件夹'.'<hr>';
                            break;
                        case 7:
                            echo $value['name'].'文件写入失败'.'<hr>';
                            break;
                        default:
                            break;
                    }
                    //            获取文件后缀
                    $ext = pathinfo($value['name'],PATHINFO_EXTENSION);
                    if(!in_array($ext, $this->allowExt)){
                        echo $value['name']."文件格式不符合"."<hr>";
                        continue;
                    }

    //                判断大小
                    if($value['size']>$this->maxSize){
                        echo $value['name']."超过了最大限制"."<hr>";
                        continue;
                    }

                    if($this->checkTrueImg){
                        if(getimagesize($value['tmp_name']) == 0){
                            echo $value['name'].'不是真正的图片'.'<hr>';
                            continue;
                        }
                    }

    //                判断上传文件的来源
                    if(!is_uploaded_file($value['tmp_name'])){
                        echo $value['name']."文件来源不合法"."<hr>";
                        continue;
                    }


                    if(!file_exists($this->path)){
    //                    第三个参数$recursive 
                        mkdir($this->path,0777,true);
                    }
                    $destination = $this->path."/".$value['name'];
                    if (!move_uploaded_file($value['tmp_name'], $destination)){
                        echo $value['name']."文件上传失败"."<hr>";
                        continue;
                    }
                    echo $value['name']."上传文件成功"."<hr>";



    //                9.文件上传成功以后保存文件路径到数据库中
                    $this->uploadFilesPath[] = $destination;
                }
            }
    }

//    百度云 360 115 飞猫 城通 yunfile
//    常识
//    第一次下载东西的时候不需要等待
//    第二次下载东西的时候需要等待10分钟