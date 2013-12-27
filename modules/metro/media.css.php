<?
header("Content-type: text/css"); 
?>
.metro{
    border-collapse: collapse;
    margin: 0 auto;
    -moz-transition: -moz-transform 0.6s ease-out; 
    -webkit-transition: -webkit-transform 0.6s ease-out; 
    -o-transition: -o-transform 0.6s ease-out;
}
.metro_page{
    display: inline-block;
    position: relative;
    -moz-transition: all 0.6s ease-out; 
    -webkit-transition: all 0.6s ease-out; 
    -o-transition: all 0.6s ease-out;
}
.metro_size{
    transform: scale(0.7,0.7);
    -ms-transform: scale(0.7,0.7); 
    -webkit-transform: scale(0.7,0.7); 
    -o-transform: scale(0.7,0.7); 
    -moz-transform: scale(0.7,0.7);
}
.metro_conteiner{
    white-space: nowrap;
    margin: 0 auto;
    position: relative;
}
.metro td{
    padding: 0;
    vertical-align: top;
}
.metro td .shine{
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
.metro td .shine{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url(images/shine.png) no-repeat -280px 0;
    opacity: 0;
    cursor: pointer;
}
.metro td .shine.hover{
    box-shadow: inset 0 0 0 3px #fff;
    background-position: 50% 0;
    opacity: 1;
}
.metro td div.lay{
    -moz-transition: box-shadow 0.2s ease-out; 
    -webkit-transition: box-shadow 0.2s ease-out; 
    -o-transition: box-shadow 0.2s ease-out;
}
.metro td div.lay:hover:not(.null){
    box-shadow:0px 0px 13px rgba(0, 0, 0, 0.3);
	-moz-box-shadow:0px 0px 13px rgba(0, 0, 0, 0.3);
	-webkit-box-shadow:0px 0px 13px rgba(0, 0, 0, 0.3);
}
.metro td div.pl{
    height: inherit;
}
.metro td div.hidebl.hide{
    overflow: hidden;
}
.metro td div.lay{
    position: absolute;
    top: 0;
    left: -70px;
    width: 100%;
    height: 100%;
    background: #61ae2a;
    color: #fff;
    opacity: 0;
    white-space: normal;
    overflow: hidden;
}
.metro td div.lay.null{
    background: transparent;
}
.metro td div.tit{
    position: absolute;
    left: 0;
    bottom: 0;
    padding: 20px;
    color: #fff;
    font-size: 24px;
    font-family: Arial;
    width: 100%;
    background: -moz-linear-gradient(top,  rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.75))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%); /* IE10+ */
    background: linear-gradient(to bottom,  rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#bf000000',GradientType=0 ); /* IE6-9 */
}
.metro_nav{
    position: absolute;
    left: 50%;
    bottom: -30px;
    list-style: none;
    padding: 0;
    margin: 0;
    white-space: nowrap;
}
.metro_nav li{
    display: inline-block;
    position: relative;
    width: 16px;
    height: 16px;
    margin-left: 8px;
    cursor: pointer;
}
.metro_nav li b{
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
    display: block;
    position: absolute;
    top: 16px;
    left: 16px;
    width: 0;
    height: 0;
    background: #fff;
    opacity: 0;
    margin: -8px 0 0 -8px;
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
.metro_nav li:hover b,
.metro_nav li.active b{
    width: 16px;
    height: 16px;
    opacity: 0.2;
    top: 8px;
    left: 8px;
}
.metro_nav li span{
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
    display: block;
    position: absolute;
    top: 3px;
    left: 3px;
    width: 10px;
    height: 10px;
    background: #fff;
    opacity: 0.8;
}
.metro_nav li.active span{
    opacity: 1;
}