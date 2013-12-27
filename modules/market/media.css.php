<?
header("Content-type: text/css"); 
?>
.install_box_install{
    width: 140px;
    position: fixed;
    left: 50%;
    top: 50%;
    margin-left: -70px;
    text-align: center;
    color: #d3e7fa;
    text-shadow: 0px 0px 6px #009cff;
}
.install_box_install .animate{
    background: url(images/i.gif) no-repeat 0 0;
    width: 93px;
    height: 11px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: 5px auto;
}
.install_box_install .success,
.install_box_install .error{
    background: url(images/ico_install.png) no-repeat 0 0;
    width: 56px;
    height: 60px;
    margin: 0 auto;
    margin-top: -40px;
}
.install_box_install .error{
    background: url(images/ico_install_error.png) no-repeat 0 0;
}
.install_box_install.error{
    color: #ff5959;
    text-shadow: 0px 0px 6px #ff2d2d;
}