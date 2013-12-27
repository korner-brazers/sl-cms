<?
header("Content-type: text/css"); 
?>
.<?=$_GET['module']?>_content{
    position: relative;
}
.<?=$_GET['module']?>_content .up_btn{
    background: #050505 url(images/up.png) no-repeat 50% 50%;
    width: 200px;
    height: 125px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -62px 0 0 -210px;
    text-align: center;
    color: #3a3a3a;
    line-height: 213px;
    cursor: pointer;
}
.<?=$_GET['module']?>_content .up_info{
    background: #161616;
    width: 275px;
    height: 220px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -110px 0 0 10px;
}
.<?=$_GET['module']?>_content .up_ec{
    background: url(images/ec.png) no-repeat 50% 50%;
    width: 14px;
    height: 29px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -15px 0 0 -4px;
}
.<?=$_GET['module']?>_content ul li{
    display: block !important;
    padding: 3px 0;
    color: #fff;
}
.<?=$_GET['module']?>_content ul li b{
    color: #838383
}