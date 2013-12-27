// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           script         - location of the serverside AJAX file to use; default = jqueryFileTree.php
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//
if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.script == undefined ) o.script = 'ajax/<?=$_GET['module']?>/dir_show';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post(o.script, { dir: t }, function(data) {
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown(o.collapseSpeed,function(){ $.sl('update_scroll'); });
						bindTree(c);
					});
				}
				
				function bindTree(t) {
					$(t).find('LI A').bind(o.folderEvent, function() {
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp(o.collapseSpeed,function(){ $.sl('update_scroll'); });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
								$(this).parent().removeClass('collapsed').addClass('expanded');
							} else {
								// Collapse
								$(this).parent().find('UL').slideUp(o.collapseSpeed,function(){ $.sl('update_scroll'); });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
						} else {
							h ? h.apply(this,[$(this).attr('rel')]);
						}
						return false;
					});
					// Prevent A from triggering the # on non-click events
					if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
				}
				// Loading message
				$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				showTree( $(this), escape(o.root) );
			});
		}
	});
	
})(jQuery);

function <?=$_GET['module']?>_login(){
    $.sl('_promt',{input:[{holder:'Login',value: bridge_l},{holder:'Password',type:'password',value: bridge_p}],autoclose:false,title:'Авторизация',btn:{'Войти':function(w,inp){
        $.sl('load','/ajax/<?=$_GET['module']?>/check_login',{data:{login:inp[0].value,pass:inp[1].value}},function(){
            bridge_l = inp[0].value;
            bridge_p = inp[1].value;
            $.sl('window',{name:w,status:'close'},function(){
                <?=$_GET['module']?>_next(1);
            });
        })
    }}});
}

function <?=$_GET['module']?>_next(p){
    $.sl('shell',{name:'<?=$_GET['module']?>',add_param:p,method:'step',post:{login:bridge_l,pass:bridge_p}},'update');
}

$('ul.bridge_apps_list li').live('click',function(){
    <?=$_GET['module']?>_next(2);
})

$('#bridge_tree a').live('contextmenu',function(){
    var path = $(this).attr('rel');
    $(this).sl('menu',{'Добавить в архив':function(){
        $.sl('load','/ajax/<?=$_GET['module']?>/add_zip',{data:{path:path}},function(data){
            $('#bridge_tree_zip').html(data);
            $.sl('update_scroll');
        })
    }},{zIndex: 600,position:'cursor'})
    
    return false;
})
