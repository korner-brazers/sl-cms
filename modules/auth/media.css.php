<?
header("Content-type: text/css"); 
?>

.sepi{
    width: 22px;
    height: 66px;
    margin: 15px 0;
    margin-left: 50px;
    background: url(images/sep.png) no-repeat;
}
.install{
    margin-left: -62px;
}
.password input{
    background: transparent;
    color: #fff;
    border: 0;
    height: 30px;
    width: 130px;
    line-height: 30px;
    text-align: center;
    outline: none;
}
.btn_auth{
    margin-top: 18px;
    margin-left: 34px;
    font-family: Tahoma;
    font-size: 12px;
    padding: 6px 13px;
    border-radius:3px 3px 3px 3px;
	-moz-border-radius:3px 3px 3px 3px;
	-webkit-border-radius:3px 3px 3px 3px;
	box-shadow:0px 2px 2px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 2px 2px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 2px 2px rgba(0, 0, 0, 0.15);
	background-color: rgb(255, 255, 255);
	background-image:linear-gradient(-90deg, rgb(255, 255, 255), rgb(221, 221, 221));
	background-image:-webkit-gradient(linear, 50% 0%, 50% 100%, from(rgb(255, 255, 255)), to(rgb(221, 221, 221)));
	background-image:-moz-linear-gradient(-90deg, rgb(255, 255, 255), rgb(221, 221, 221));
    cursor: pointer;
}
.btn_auth span{
    color: #000 !important;
}
.user_b{
    width: 80%;
    margin: 0 auto;
    overflow: hidden;
    height: inherit;
}
.all_users{
    white-space:nowrap;
    padding: 0;
    margin: 0;
    list-style: none;
    transition: all 300ms ease-in;
    -webkit-transition: all 300ms ease-in;
    -moz-transition: all 300ms ease-in;
    -o-transition: all 300ms ease-in;
    -ms-transition: all 300ms ease-in;
}
.all_users li{
    display: inline-block;
    margin-left: -35px;
    margin-right: 130px;
    opacity: 0.5;
    width: 160px;
}
.all_users li.active{
    opacity: 1;
}
.all_users .ava{
    margin-right: 10px;
    width: 64px;
    height: 64px;
}
.all_users .user_name{
    color: #fff;
    text-shadow: 0px 0px 5px rgb(255, 255, 255);
}
.all_users .user_ac{
    color: #000;
    text-shadow: 0px 0px 5px rgb(255, 255, 255); 
    margin-top: 3px;
}
.point_right{
    top: 35%;
    width: 50px;
    height: 80px;
    background: url(images/r.png) no-repeat 50% 50%;
    cursor: pointer;
    margin-top: -8px;
    display: none;
}
.point_left{
    top: 35%;
    width: 50px;
    height: 80px;
    background: url(images/l.png) no-repeat 50% 50%;
    cursor: pointer;
    margin-top: -8px;
    display: none;
}