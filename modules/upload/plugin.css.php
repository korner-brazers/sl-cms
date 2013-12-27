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
.<?=$_GET['module']?>_conteiner{
    border: 1px dashed #2b2b2b;
    height: 114px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    background: url(images/move.png) no-repeat 50% 50%;
    cursor: pointer;
    position: relative;
}
.<?=$_GET['module']?>_conteiner.load{
    background: transparent;
}
.<?=$_GET['module']?>_conteiner .up_progress{
    background: #0b0b0b;
    width: 80px;
    height: 10px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -5px 0 0 -40px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    overflow: hidden;
    display: none;
}
.<?=$_GET['module']?>_conteiner .up_progress div{
    height: 10px;
    background: url(images/upload_.gif) repeat-x 0 0;
}
.<?=$_GET['module']?>_conteiner .up_pr{
    width: 80px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: 10px 0 0 -40px;
    text-align: center;
    font-size: 20px;
    display: none;
}
.<?=$_GET['module']?>_conteiner.load .up_progress,
.<?=$_GET['module']?>_conteiner.load .up_pr{
    display: block;
}