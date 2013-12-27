<?
header("Content-type: text/css"); 
?>
.<?=$_GET['module']?>EditSpan:hover{
    position: relative;
    display: inline-block;
}
.<?=$_GET['module']?>EditSpan:hover span.<?=$_GET['module']?>EditBtn{
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    background:rgba(0,0,0,.75) url(edi_row.png) no-repeat 50% 50%;
    width: 22px;
    height: 22px;
    cursor: pointer;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}