<?
header("Content-type: text/css"); 
?>
.<?=$_GET['module']?>_bg{
    background: #fff url(bg.jpg) no-repeat 50% 50%;
}
.<?=$_GET['module']?>_apps{
    width: 255px !important;
    background: #eaeaea;
}
.<?=$_GET['module']?>_apps_list{
    list-style: none;
    padding: 0;
    margin: 0;
    width: 255px
}
.<?=$_GET['module']?>_apps_list li{
    display: block;
    position: relative;
    height: 60px;
    cursor: pointer;
    border-top: 1px solid #eaeaea;
    -webkit-transition: all 300ms ease-in;
    -moz-transition: all 300ms ease-in;
    -o-transition: all 300ms ease-in;
    -ms-transition: all 300ms ease-in;
}
.<?=$_GET['module']?>_apps_list li:not(.link):hover,
<?=$_GET['module']?>_apps_list li.active:not(.link){
    background-color: #212121;
    border-top: 1px solid #323232;
    box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-moz-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow:0px 3px 3px rgba(0, 0, 0, 0.15);
}
.<?=$_GET['module']?>_apps_list li > div{
    padding: 12px 15px;
}
.<?=$_GET['module']?>_apps_list li .title{
    color: #595959;
    font-weight: bold;
    display: block;
    clear: both;
    margin-bottom: 5px;
    -webkit-transition: all 300ms ease-in;
    -moz-transition: all 300ms ease-in;
    -o-transition: all 300ms ease-in;
    -ms-transition: all 300ms ease-in;
}
.<?=$_GET['module']?>_apps_list li:hover .title{
    color: #d4d4d4;
}
.<?=$_GET['module']?>_apps_list li .descr{
    color: #6f6f6f;
}
.<?=$_GET['module']?>_apps_list li:nth-child(even){
    background-color: #e0e0e0;
}
.<?=$_GET['module']?>_apps_list li a{
    color: inherit;
    text-decoration: none;
}
.<?=$_GET['module']?>_apps_list li .ico{
    overflow: hidden;
    width: 40px;
    height: 40px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.<?=$_GET['module']?>_apps_list li .ico img{
    max-width: 40px;
}
.<?=$_GET['module']?>_apps_list li .con{
    padding-left: 10px;
}
.<?=$_GET['module']?>_apps_list li.data{
    background-image: url(i.png);
    background-repeat: no-repeat;
    background-position: 18px 50%;
    padding-left: 18px;
}

.markdown > h2:first-child, .markdown > h1:first-child, .markdown > h1:first-child + h2, .markdown > h3:first-child, .markdown > h4:first-child, .markdown > h5:first-child, .markdown > h6:first-child {
    margin-top: 0;
    padding-top: 0;
}

.markdown{
    color: #333333;
    font: 13px/1.4 Helvetica,arial,freesans,clean,sans-serif;
}
.markdown h1, .markdown h2, .markdown h3, .markdown h4, .markdown h5, .markdown h6 {
    cursor: text;
    font-weight: bold;
    margin: 20px 0 10px;
    padding: 0;
    position: relative;
}
.markdown h1 {
    color: #000000;
    font-size: 28px;
}
.markdown h2{
    border-bottom: 1px solid #CCCCCC;
    color: #000000;
    font-size: 24px;
}
.markdown h3 {
    font-size: 18px;
}
.markdown blockquote {
    border-left: 4px solid #DDDDDD;
    color: #777777;
    padding: 0 15px;
    margin: 15px 0;
    quotes: none;
}
.markdown p, .markdown blockquote, .markdown ul, .markdown ol, .markdown dl, .markdown table, .markdown div {
    margin: 15px 0;
}
.markdown ul, .markdown ol {
    padding-left: 30px;
}
.markdown a {
    color: #4183C4;
    text-decoration: none;
}
.markdown code, .markdown tt {
    background-color: #F8F8F8;
    border: 1px solid #EAEAEA;
    border-radius: 3px 3px 3px 3px;
    margin: 0 2px;
    padding: 0 5px;
}
.markdown  div,.markdown  code,.markdown  tt {
    font-family: Consolas,"Liberation Mono",Courier,monospace;
    font-size: 12px;
}
.markdown .highlight div, .markdown div {
    background-color: #F8F8F8;
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    font-size: 13px;
    line-height: 19px;
    overflow: auto;
    padding: 6px 10px;
}
.markdown div code, .markdown div tt {
    background-color: transparent;
    border: medium none;
    margin: 0;
    padding: 0;
}
.markdown table {
    border-collapse: collapse;
    border-spacing: 0;
    font: inherit;
}
.markdown table tr {
    background-color: #FFFFFF;
    border-top: 1px solid #CCCCCC;
}
.markdown table th, .markdown table td {
    border: 1px solid #CCCCCC;
    padding: 6px 13px;
}
.markdown table th {
    font-weight: bold;
}
.markdown table tr:nth-child(2n) {
    background-color: #F8F8F8;
}