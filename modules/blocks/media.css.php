<?
header("Content-type: text/css"); 
?>
.ulBlocks{
    border-collapse: collapse;
    margin-left: -10px;
    margin-right: -10px;
}
.ulBlocks td{
    min-height: 125px;
    padding: 0;
    padding-bottom: 10px;
    vertical-align: top;
}
.ulBlocks td .layer{
    background: #000 url(images/full_scrin.png) no-repeat 50% 50%;
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.ulBlocks td div.pl{
    margin-left: 10px;
    height: inherit;
}
.ulBlocks td div.mar{
    background: #1a1a1a;
    height: inherit;
}
.ulBlocks td.shadow_in div.mar{
    box-shadow:inset 0px 0px 20px rgba(0, 0, 0, 0.25);
	-moz-box-shadow:inset 0px 0px 20px rgba(0, 0, 0, 0.25);
	-webkit-box-shadow:inset 0px 0px 20px rgba(0, 0, 0, 0.25);
}
.ulBlocks td.shadow_out div.mar{
    box-shadow:0px 0px 20px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 0px 20px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 0px 20px rgba(0, 0, 0, 0.15);
}
.ulBlocks td div.rea{
    cursor: pointer;
    color: #656565;
    text-shadow: 0px 1px 0px #101010
}
.ulBlocks td div.rea:hover{
    background: #181818;
    color: #fff
}
.ulBlocks td div.rea:hover .tis{
    text-shadow: 0 1px 0 #000
}
.ulBlocks td.w_1{
    width: 25%;
}
.ulBlocks td.w_2{
    width: 50%;
}
.ulBlocks td.w_3{
    width: 75%;
}
.ulBlocks td.w_4{
    width: 100%;
}
.ulBlocks td.h_1{
    height: 125px;
}
.ulBlocks td.h_2{
    height: 260px;
}
.ulBlocks td.h_3{
    height: 395px;
}
.ulBlocks td.h_4{
    height: 530px;
}
.ulBlocks td div.hidebl{
    overflow: hidden;
}
.ulBlocks td.title{
    padding-left: 10px;
}
.ulBlocks td.title div{
    font-size: 16px;
    font-family: Arial;
    text-transform: uppercase;
    text-shadow: 0px 1px 0px #fff;
    padding: 10px;
    padding-left: 0;
    color: #3b3b3b;
    border-bottom: 1px solid #c4c4c4;
}
.ulBlocks td .sep{
    height: 2px;
    background: url(images/m_sep_03.png) repeat-x 0 0;
    margin: 10px 0;
}
.ulBlocks td .color{
    color: #fff;
}
.ulBlocks td .lem{
    background: #343434;
    padding: 5px;
    font-size: 23px;
    color: #fff;
    font-family: Arial;
    box-shadow:inset 0px 0px 8px rgba(0, 0, 0, 0.7);
	-moz-box-shadow:inset 0px 0px 8px rgba(0, 0, 0, 0.7);
	-webkit-box-shadow:inset 0px 0px 8px rgba(0, 0, 0, 0.7);
    border-radius:3px;
}
#blockConteiner{
    min-height: 300px;
    max-width: 900px;
    margin: 0 auto;
}