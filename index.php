<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>这是上传单个文件</h1>
        <!--注意:上传文件需要设置enctype属性 multipart/form-data-->
        <form method="post" action="doAction.php" enctype="multipart/form-data">
            <input type="file" name="myFile">
            <br>
            <input type="submit" name="" value="上传">
        </form>
        
        
        <h1>上传多个文件</h1>
        <h3>它的数据是一个二维数组</h3>
        <form method="post" action="doAction.php" enctype="multipart/form-data">
            <input type="file" name="myFile1">
            <br>
            <input type="file" name="myFile2">
            <br>
            <input type="file" name="myFile3">
            <br>
            <input type="file" name="myFile4">
            <br>
            <input type="submit" name="" value="上传">
        </form>
        <h1>上传多个文件</h1>
        <h3>用一个input上传多个文件需要设置multiple为multiple</h3>
        <h3>它的数据是一个三维数组，name属性值得后面需药加一个[]</h3>
        <form method="post" action="doAction.php" enctype="multipart/form-data">
            <input type="file" name="myFile[]" multiple="multiple">
            <br>
            <input type="submit" name="" value="上传">
        </form>
        <form method="post" action="doAction.php" enctype="multipart/form-data">
            <p>验证码：<input type="text" name="code" placeholder="验证码"></p>
            <p><img src="captchaTest.php"></p>
            <input type="submit" name="" value="提交">
        </form>
    </body>
</html>
