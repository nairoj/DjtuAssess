<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
<!--        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>djtu课程一键评估</title>
</head>
<body>
<h3 class="modal-header" style="text-align:center">djtu课程自动评估</h3>
<div class="container">
    <div class="form-horizontal">
        <div class="form-group">
            <label for="userid" class="col-sm-2 control-label col-xs-12">账 号</label>
            <div class="col-sm-8">
                <input type="number" id="userid" class="form-control" placeholder="请输入教务账号"/>
            </div>
        </div>
        <div class="form-group">
            <label for="passwd" class="col-sm-2 control-label col-xs-12" >密 码</label>
            <div class="col-sm-8">
                <input type="password" id="passwd" class="form-control" placeholder="请输入密码"/>
            </div>
        </div>
        <div class="form-group">
            <label for="checkcode" class="col-sm-2 control-label col-xs-12">验证码</label>
            <div class="col-sm-5 col-xs-8">
                <input type="text" id="checkcode" class="form-control" placeholder="请输入验证码">
            </div>
            <div class="col-sm-4 col-xs-4">
                <img src="<?php echo $checkSum;?>" onclick="refresh(this)"  id="checksum" alt="获取验证码会有点慢:)" title="清耐心等待:)" style="cursor:pointer;">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5">
                <p class="text-info">
                    一键全A，不会记录密码，请用外部浏览器打开;)
                </p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-4">
            <button class="btn btn-block btn-success" onclick="checkVal()">评估</button></div>
        </div>
    </div>
</div>
<p class="hidden">邮件联系作者： 370343151@qq.com</p>
<p class="hidden">源码地址：https://github.com/nairoj/DjtuAssess</p>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mine.js"></script>
</body>
</html>
