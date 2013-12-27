<?
header("Content-type: text/css"); 
?>
.image_box{
    background-color: rgba(0,0,0,0.6);
    top: -100%;
    opacity: 0;
    z-index: 980;
}
#image_box_prew{
    background: #0b0b0b;
}
.image_box .box_conteiner{
    height: inherit;
}
#box_list li{
    height: 128px;
    width: 210px;
    background-color: #181818;
    margin: 0 40px 20px 0;
    float: left;
    overflow: hidden;
    position: relative;
}
#box_list li#dropbox{
    background-color: #242424;
	box-shadow: inset 0 0 29px rgba(0,0,0,.59);
}
#box_list li#dropbox span{
    display: block;
    margin: 45px 0 0 23px;
}
#box_list li img{
    max-width: 210px;
}
#box_list li .view-img{
    height: inherit;
    cursor: pointer;
}
#box_list li .view-img:after{
    content: ''; 
    display: block; 
    position: absolute; 
    top: 0; 
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0, 0.0);
    background-image: url("images/zoom.png");
    background-repeat: no-repeat;
    background-position: 50% 50%;
    background-size: 0px 0px;
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
#box_list li .view-img:hover:after{
    background-color: rgba(0,0,0, 0.5);
    background-size: 22px 22px;
}
#box_list li .cid{
    background: rgba(0,0,0,0.5);
    color: #fff;
    position: absolute;
    left: 0;
    bottom: 0;
    padding: 2px 5px;
}
#box_list li .edit_ico{
    opacity: 0;
    position: absolute;
    top: 0;
    right: 0;
    width: 30px;
    height: 100%;
    background: rgba(0,0,0, 0.8) url(images/edit_.gif) no-repeat 50% 50%;
    cursor: pointer;
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
#box_list li:hover .edit_ico{
    opacity: 1;
}
#box_list li .progressHolder,
.zip_box .progressHolder{
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 5px;
    background-color: rgba(0,0,0,0.7);
    height: 5px;
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
#box_list li .progressHolder .progress,
.zip_box .progressHolder .progress{
    background: #1263bc url(images/upload_.gif) repeat-x 0 0;
    height: 5px;
}
.zip_box .progressHolder{
    left: 0px;
}
#box_list li.done .progressHolder,
.zip_box .loadZip.done .progressHolder{
    opacity: 0;
}
#box_list li.poster{
    box-shadow: 0 0 0 3px #3c8cfa;
}
#box_list li.ico{
    box-shadow: -5px 0 0 0 #3C8CFA;
}
.close_box{
    height: 45px !important;
    background-color: #0a0a0a;
    background:  url(images/back_glow.png) no-repeat 50% 50%,#0a0a0a url(images/back_glow.png) no-repeat 50% 55px;
    padding: 0 !important;
    cursor: pointer;
}
.close_box:hover{
    background:  url(images/back_glow.png) no-repeat 50% 40%,#0a0a0a url(images/back_glow.png) no-repeat 50% 60%;
}