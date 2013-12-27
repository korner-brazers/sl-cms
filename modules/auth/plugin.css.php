<?
header("Content-type: text/css"); 
?>
.authWidget{
    font-size: 12px;
    font-family: Arial;
}
.authWidget .input{
    background: #f2f2f2;
    border: 1px solid #cfcec6;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    box-shadow:inset 0px 2px 5px rgba(0, 0, 0, 0.1);
	-moz-box-shadow:inset 0px 2px 5px rgba(0, 0, 0, 0.1);
	-webkit-box-shadow:inset 0px 2px 5px rgba(0, 0, 0, 0.1);
    height: 30px;
    margin-bottom: 15px;
    padding: 0 5px 0 35px;
    background-position: 5px 50%;
    background-repeat: no-repeat;
}
.authWidget .input.login{
    background-image: url(images/login.png);
}
.authWidget .input.password{
    background-image: url(images/lock.png);
    background-position: 10px 50%;
}
.authWidget .input input{
    margin: 0;
    padding: 0;
    background: transparent;
    display: block;
    height: 30px;
    border: 0;
    color: #848484;
    font-weight: bold;
    font-size: 12px;
    font-family: Arial;
    outline: none;
}
.authWidget .btn_conteiner{
    height: 32px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    overflow: hidden;
    background: url(images/btnBg.png) repeat-x 0 100%;
    position: relative;
}
.authWidget .btn_conteiner .bt{
    float: left;
    width: 50%;
    cursor: pointer;
    height: 32px;
    line-height: 32px;
    color: #fff;
    font-size: 12px;
    font-family: Arial;
    text-align: center;
}
.authWidget .btn_conteiner .btnLogin{
    font-weight: bold;
    font-size: 14px;
}
.authWidget .btn_conteiner .btnLogin span{
    padding-right: 15px;
}
.authWidget .btn_conteiner .btnReg{
    background: url(images/btnBgReg.png) repeat-x 0 100%;
}
.authWidget .btn_conteiner .btnReg span{
    padding-left: 15px;
}
.authWidget .btn_conteiner .or{
    position: absolute;
    top: 0;
    left: 50%;
    background: url(images/or.png) no-repeat 50% 50%;
    width: 42px;
    height: 32px;
    margin-left: -21px;
}
.authWidget .ava{
    padding: 3px;
    background: #fff;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #e7e7e7;
    float: left;
}
.authWidget .ava .bgAva{
    background: url(images/avaBg.png) no-repeat 50% 50%;
    height: 49px;
    overflow: hidden;
    width: 49px;
    text-align: center;
    box-shadow:0px 3px 6px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 3px 6px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 3px 6px rgba(0, 0, 0, 0.15);
}
.authWidget .ava .bgAva img{
    max-width: 49px;
    vertical-align: middle;
}
.authWidget .info{
    float: left;
    padding-left: 15px;
}
.authWidget .info .userName{
    font-size: 19px;
    color: #5e5e5e;
    display: block;
    line-height: 20px;
    margin: 0;
    padding: 0;
}
.authWidget .btnOut{
    display: inline-block;
    background: #ff971c url(images/btnOut.png) repeat-x 0 100%;
    padding: 4px 9px;
    font-weight: bold;
    border: 1px solid #ff7516;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    box-shadow:0px 2px 3px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 2px 3px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 2px 3px rgba(0, 0, 0, 0.15);
    color: #fff;
    margin-top: 9px;
    cursor: pointer;
}
.authWidget .btnOut:hover{
    background: #151515;
    border-color: #000;
}