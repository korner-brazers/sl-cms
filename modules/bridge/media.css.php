<?
header("Content-type: text/css"); 
?>
UL.jqueryFileTree {
	font-family: Verdana, sans-serif;
	font-size: 11px;
	line-height: 18px;
	padding: 0px;
	margin: 0px;
    padding-left: 20px;
}
UL.jqueryFileTree LI {
	list-style: none;
	padding: 0px;
	padding-left: 20px;
    display: block;
    position: relative;
    cursor: pointer;
    background-color: #0d0d0d;
}
UL.jqueryFileTree li:nth-child(even){
    background-color: #101010;
}
UL.jqueryFileTree li a{
    text-decoration: none;
    display: block;
    color: #d4d4d4;
    text-shadow: 0 1px 0 #242424;
    padding: 2px 2px;
    -webkit-transition: all 300ms ease-in;
    -moz-transition: all 300ms ease-in;
    -o-transition: all 300ms ease-in;
    -ms-transition: all 300ms ease-in;
}
UL.jqueryFileTree A:hover {
	background-color: #212121;
    box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
}

/* Core Styles */
.jqueryFileTree LI.directory { background: url(images/directory.png) left 3px no-repeat; }
.jqueryFileTree LI.directory a{
    font-weight: bold;
}
.jqueryFileTree LI.expanded { background: url(images/folder_open.png) left 3px no-repeat; }
.jqueryFileTree LI.file { background: url(images/file.png) left 3px no-repeat; }
.jqueryFileTree LI.file a{
    color: #6f6f6f;
    font-weight: normal;
}
.jqueryFileTree LI.wait { background: url(images/spinner.gif) left 3px no-repeat; }
/* File Extensions*/
/*
.jqueryFileTree LI.ext_3gp { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_afp { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_afpa { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_asp { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_aspx { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_avi { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_bat { background: url(images/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_bmp { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_c { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_cfm { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_cgi { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_com { background: url(images/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_cpp { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_css { background: url(images/css.png) left top no-repeat; }
.jqueryFileTree LI.ext_doc { background: url(images/doc.png) left top no-repeat; }
.jqueryFileTree LI.ext_exe { background: url(images/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_gif { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_fla { background: url(images/flash.png) left top no-repeat; }
.jqueryFileTree LI.ext_h { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_htm { background: url(images/html.png) left top no-repeat; }
.jqueryFileTree LI.ext_html { background: url(images/html.png) left top no-repeat; }
.jqueryFileTree LI.ext_jar { background: url(images/java.png) left top no-repeat; }
.jqueryFileTree LI.ext_jpg { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_jpeg { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_js { background: url(images/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_lasso { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_log { background: url(images/txt.png) left top no-repeat; }
.jqueryFileTree LI.ext_m4p { background: url(images/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_mov { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mp3 { background: url(images/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_mp4 { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mpg { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mpeg { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_ogg { background: url(images/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_pcx { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_pdf { background: url(images/pdf.png) left top no-repeat; }
.jqueryFileTree LI.ext_php { background: url(images/php.png) left top no-repeat; }
.jqueryFileTree LI.ext_png { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_ppt { background: url(images/ppt.png) left top no-repeat; }
.jqueryFileTree LI.ext_psd { background: url(images/psd.png) left top no-repeat; }
.jqueryFileTree LI.ext_pl { background: url(images/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_py { background: url(images/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_rb { background: url(images/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rbx { background: url(images/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rhtml { background: url(images/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rpm { background: url(images/linux.png) left top no-repeat; }
.jqueryFileTree LI.ext_ruby { background: url(images/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_sql { background: url(images/db.png) left top no-repeat; }
.jqueryFileTree LI.ext_swf { background: url(images/flash.png) left top no-repeat; }
.jqueryFileTree LI.ext_tif { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_tiff { background: url(images/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_txt { background: url(images/txt.png) left top no-repeat; }
.jqueryFileTree LI.ext_vb { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_wav { background: url(images/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_wmv { background: url(images/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_xls { background: url(images/xls.png) left top no-repeat; }
.jqueryFileTree LI.ext_xml { background: url(images/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_zip { background: url(images/zip.png) left top no-repeat; }
*/
.step_content{
    width: 200px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -60px;
}
.bridge_apps,
.bridge_files{
    width: 255px !important;
    background: #0d0d0d;
}
.bridge_files{
    width: 455px !important;
}
.bridge_files.color_d{
    background: #0c0c0c;
}
.bridge_apps_list{
    list-style: none;
    padding: 0;
    margin: 0;
    width: 255px
}
.bridge_apps_list li{
    display: block;
    position: relative;
    height: 60px;
    cursor: pointer;
    border-top: 1px solid #0d0d0d;
    -webkit-transition: all 300ms ease-in;
    -moz-transition: all 300ms ease-in;
    -o-transition: all 300ms ease-in;
    -ms-transition: all 300ms ease-in;
}
.bridge_apps_list li:not(.link):hover,
.bridge_apps_list li.active:not(.link){
    background-color: #212121;
    border-top: 1px solid #323232;
    box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
}
.bridge_apps_list li.more{
    height: 45px !important;
    background-color: #0a0a0a;
    background:  url(../images/more.png) no-repeat 50% 50%,url(../images/more.png) no-repeat 50% -10px;
    padding: 0 !important;
}
.bridge_apps_list li.more:hover{
    background:  url(../images/more.png) no-repeat 50% 60%,url(../images/more.png) no-repeat 50% 40%;
    border-top: 1px solid #0d0d0d;
}
.bridge_apps_list li.modern{
    background:  url(../images/modernMod.png) no-repeat 95% 50%;
}
.bridge_apps_list li.more_load{
    height: 45px !important;
}
.bridge_apps_list li.back{
    height: 45px !important;
    background-color: #0a0a0a;
    background:  url(../images/back.png) no-repeat 50% 50%,url(../images/back.png) no-repeat 50% 55px;
    padding: 0 !important;
}
.bridge_apps_list li.back:hover{
    background:  url(../images/back.png) no-repeat 50% 40%,url(../images/back.png) no-repeat 50% 60%;
    border-top: 1px solid #0d0d0d;
}
.bridge_apps_list li > div{
    padding: 12px 15px;
}
.bridge_apps_list li .title{
    color: #d4d4d4;
    text-shadow: 0 1px 0 #242424;
    font-weight: bold;
    display: block;
    clear: both;
    margin-bottom: 5px;
}
.bridge_apps_list li .descr{
    color: #6f6f6f;
}
.bridge_apps_list li.link{
    background: url(images/i.png) no-repeat 15px 50%;
    padding-left: 20px;
}
.bridge_apps_list li.link:hover{
    background-color: #141414;
}
.bridge_apps_list li:nth-child(even){
    background-color: #101010;
}
.bridge_apps_list li a{
    color: inherit;
    text-decoration: none;
}
.bridge_apps_list li .ico{
    overflow: hidden;
    width: 40px;
    height: 40px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.bridge_apps_list li .ico img{
    max-width: 46px;
}
.bridge_apps_list li .con{
    padding-left: 10px;
}
#<?=$_GET['module']?>_next_btn{
    background: url(images/btn.png) no-repeat 0 0;
    cursor: pointer;
    width: 133px;
    height: 47px;
    display: none;
}
#bridge_tree,
#bridge_tree_zip{
    padding-top: 40px;
}