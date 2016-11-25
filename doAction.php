<?php
    //    doAction文件用于处理
    //<pre> 标签可定义预格式化的文本。
    //被包围在 <pre> 标签 元素中的文本通常会保留空格和换行符。而文本也会呈现为等宽字体。
    //示: <pre> 标签的一个常见应用就是用来表示计算机的源代码。
//    echo '<pre>';
////    print_r($_FILES);
//    include_once 'Upload.php';
//    //$config = array(
//    //    'path'=>'a/b/c/d',
//    //    'allowExt'=>array('exe','ppt','xmind')
//    //);
//    $upload = new Upload();
//    
//    $upload->uploadFile();
//    
    //由使用者根据情况决定是否插入数据库
//    print_r($upload->uploadFilesPath);
    print_r($_POST);
    session_start();
    $randCode = $_SESSION['randCode'];
    echo $randCode;
?>

