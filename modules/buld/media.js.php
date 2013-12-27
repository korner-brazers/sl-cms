<?
header("Content-type: application/x-javascript");

$lang = json_decode($_GET['lang']);
?>

$('body').append('<link rel="stylesheet" href="/modules/<?=$_GET['module']?>/codemirror-2.34/lib/codemirror.css" /><script type="text/javascript" src="/modules/<?=$_GET['module']?>/codemirror-2.34/lib/codemirror.js"></sc'+'ript><script src="/modules/<?=$_GET['module']?>/codemirror-2.34/mode/javascript/javascript.js"></sc'+'ript><link rel="stylesheet" href="/modules/<?=$_GET['module']?>/codemirror-2.34/theme/ambiance.css" />');

(function($){
    var max_canvas   = [0,0],
        select_out   = false,
        tools_select = 0,
        bZizeV       = 100,
        dragBg       = [false,0,0,0,0],
        dragLayer    = [0,0],
        canvas       = '#buld .canvas',
        layer_move   = '#buld .conteiner_position_layer.active',
        visual_bg    = '#buld .visual_bg_conteiner',
        gragObj      = [false,null,0,0,false,0,0],
        libralys     = [],
        buld_cur     = [0,0],
        prjOpenJson  = {},
        prjOpenId    = false,
        prjOpenChange= {},
        iniOn        = false,
        buld_info    = $('#buld #infoRm'),
        Projected    = true,
        timeMove     = false;
    
    var methods = {
        init : function() {
            
            prjOpenId    = false,
            prjOpenJson  = {},
            prjOpenChange= {};
            
            /**
             * Left tools
             */
            
            $('#buld .left_tools li').on('click',function(){
                $(this).parent().find('li').removeClass('active');
                tools_select = $(this).addClass('active').index();
            });
            
            /**
             * Buld Panels Tab
             */
            
            $('[buldTab]').on('click',function(){
                var _this = $(this),
                    cl    = 'active',
                    inx   = _this.attr('buldTab'),
                    panels= $('#buld #panels div.co').removeClass(cl),
                    ih_cl = _this.hasClass(cl) ? 1 : 0;
                
                $('[buldTab]').removeClass(cl);
                ih_cl ? (_this.removeClass(cl),panels.eq(inx).removeClass(cl)) : (_this.addClass(cl),panels.eq(inx).addClass(cl));
                panels.eq(inx).tinyscrollbar_update('relative');
            });
            
            /**
             * Cursor move body
             */
             
            $('#buld').on('mousemove touchmove',function(e){
                var e = e.pageX ? e : e.originalEvent.changedTouches[0];
                
                buld_cur = [e.pageX,e.pageY];
            });
            
            /**
             * Init Scroll Size
             */
            
            var jsThis = $(visual_bg).get(0);
            
            if (jsThis.addEventListener){
                jsThis.addEventListener('DOMMouseScroll', methods.scroll, false);
                jsThis.addEventListener('mousewheel', methods.scroll, false);
            }
            else if (jsThis.attachEvent) {
                jsThis.attachEvent('onmousewheel', methods.scroll);
            }
            
            /**
             * Init Lib
             */
               
            methods.iniLib();
            
            /**
             * Stop init
             */
             
            if(iniOn) return;
            else iniOn = true;
            
            
            /**
             * On/Off obj
             */
            
            $('#buld .obj .onoff').live('click',function(){
                var _this = $(this),
                    id    = _this.closest('.obj').attr('id');
                    
                if(_this.hasClass('on')){
                    prjOpenJson[prjOpenId]['save'][id].op.status = 0;
                    _this.removeClass('on');
                }
                else{
                    prjOpenJson[prjOpenId]['save'][id].op.status = 1;
                    _this.addClass('on');
                }
            });
            
            /**
             * Drag objs and background
             */
            
            $(visual_bg).on('mousedown touchstart',function(e){
                timeMove = false;
                
                if(e.pageX) timeMove = true;
                else{
                    e = e.originalEvent.changedTouches[0];
                    
                    setTimeout(function(){
                        timeMove = true;
                    },150);
                } 
                
                dragBg[0]    = true;
                
                dragBg[1]    = e.pageX; 
                dragBg[2]    = e.pageY;
                
                var layer = $(layer_move).position();
                $(visual_bg).addClass('move');
                
                dragLayer[0] = layer.left;
                dragLayer[1] = layer.top;
                
            }).on('mousemove touchmove',function(e){
                var e = e.pageX ? e : e.originalEvent.changedTouches[0];
                
                if(!timeMove) return;
                
                dragBg[3] = dragBg[1] - e.pageX;
                dragBg[4] = dragBg[2] - e.pageY;
                
                if(dragBg[0] && !gragObj[0]){  
                    $(layer_move).css({
                        left: dragLayer[0] - dragBg[3],
                        top: dragLayer[1] - dragBg[4]
                    });
                    
                    $(visual_bg).css({backgroundPosition: (dragLayer[0] - dragBg[3]) + 'px ' + (dragLayer[1] - dragBg[4]) + 'px'})
                }
                else if(gragObj[0]){
                    
                    gragObj[5] = gragObj[2] - dragBg[3];
                    gragObj[6] = gragObj[3] - dragBg[4];
                    
                    
                    gragObj[1].css({
                        left: gragObj[5],
                        top: gragObj[6]
                    });
                    
                    if(!gragObj[7]){
                        gragObj[1].css({opacity: '0.5'});
                        $(canvas).hide();
                        gragObj[7] = true;
                    }
                }
            }).on('mouseup touchend',function(){
                
                dragBg[0] = false;
                dragBg[3] = 0;
                
                $(visual_bg).removeClass('move');
                
                gragObj[0] = false;
                
                if(gragObj[4] && gragObj[7]){
                    prjOpenJson[prjOpenId]['save'][gragObj[4]].op.left = gragObj[5] < 0 ? 0 : gragObj[5];
                    prjOpenJson[prjOpenId]['save'][gragObj[4]].op.top  = gragObj[6] < 0 ? 0 : gragObj[6];
                    
                    $(canvas).show();
                    
                    gragObj[1].css({opacity: 1,left:prjOpenJson[prjOpenId]['save'][gragObj[4]].op.left,top:prjOpenJson[prjOpenId]['save'][gragObj[4]].op.top});
                    
                    methods.move_obj(gragObj[4]);
                    
                    methods.max_set(prjOpenJson[prjOpenId]['save'][gragObj[4]].op.left,prjOpenJson[prjOpenId]['save'][gragObj[4]].op.top);
                    
                    methods.canvas();
                    
                    gragObj[4] = false;
                }
            })
            
            /**
             * Libraly List 
             */
            
            $('#buld .libraly_list li span:not(.no)').live('click',function(){
                var ul = $('ul:first',$(this).parent()),
                    vis= ul.is(':visible') ? 1 : 0;
                    
                vis && ul.length? ul.slideUp(function(){ $.sl('update_scroll') }) : ul.slideDown(function(){ $.sl('update_scroll') });
            });
            
            /**
             * Set TimeInterval Check Projected
             */
             
             setInterval(function(){
                if(!Projected) return;
                
                Projected = false;
                
                $.sl('load','/ajax/<?=$_GET['module']?>/comProjectedJS',{
                    data:{
                        prjSearch:JSON.stringify(prjOpenJson)
                    },
                    dataType:'json',
                    mode:'hide',
                    ignore:true,
                    error: function(){
                        Projected = true;
                    }
                },function(j){
                    Projected = true;
                    methods.bindChangePrj(j);
                })
             },5000);
        },
        info: function(i){
            buld_info.text(i);
            methods.prjChange();
        },
        objProperties: function(id){
            var obj = prjOpenJson[prjOpenId]['save'][id],li = '';
            
            if(!obj) return;

            $('#buld .objPropertiesConteiner').html('<div class="header"><h3><?=$lang[9]?>: <span>'+obj.op.name+'</span></h3><div class="btns">'+$.scin('btn','&#8801;',{attr:{'onclick':"$.buld('editObj','"+obj.op.id+"')"}})+'</div></div><div class="sep"></div><ul class="list_style objProperties"></ul>');
            
            var co = $('#buld .objPropertiesConteiner ul.objProperties');
            
            $.each(obj.values,function(i,a){
                $('<li><span class="l">'+i.substr(0,12)+'</span><div class="r">'+(a.type == 1 ? $.scin('select',{val:a.val.split('\n'),value:parseInt(a.value),callback:["buldSelectValObj","'"+id+"'","'"+i+"'"]}) : (a.type == 2 ? $.scin('checkbox',{value:parseInt(a.value || a.val),attr:{onclick:"buldSelectValObj($(this).find('input').val(),'"+id+"','"+i+"')"}}) : '<div class="areaProper"><textarea class="noxcode" spellcheck="false" onkeyup="$.buld(\'objPrsKeyUp\',\''+id+'\',\''+i+'\',this.value)"></textarea><div class="bigEdit" onclick="$(this).buld(\'bigEditProperties\',\''+id+'\',\''+i+'\')"></div></div>'))+'</div></li>').appendTo(co).find('textarea').val(a.value);
            })
            
            $.sl('update_scroll')
        },
        objPrsKeyUp: function(id,i,val){
            prjOpenJson[prjOpenId]['save'][id]['values'][i]['value'] = val;
            methods.info('<?=$lang[10]?>');
        },
        objSelectValObj: function(c,id,i){
            prjOpenJson[prjOpenId]['save'][id]['values'][i]['value'] = c;
            methods.info('<?=$lang[10]?>');
        },
        bigEditProperties: function(id,i){
            var area = $(this).parent().find('textarea');
            $.sl('window',{
                name: 'b_b_e',
                status: 'fullsize',
                data: '<div class="win_h_size"><textarea id="buldLibCodeMirror"></textarea></div>',
                bg: false
            },function(){
                methods.editLibAreaCode(prjOpenJson[prjOpenId]['save'][id]['values'][i]['value'],function(v){
                    prjOpenJson[prjOpenId]['save'][id]['values'][i]['value'] = v;
                    area.val(v);
                    methods.info('<?=$lang[10]?>');
                })
            })
        },
        iniLib: function(){
            var li = lic = lio = '',obl = '#buld .moveObjLib',_this;
            
            function lb_o(obj,lib_id,cid){
                if(obj.length == 0) return '';
                
                lio = '';
                
                $.each(obj,function(id,arr){
                    lio += '<li objid="'+id+'" lib_id="'+lib_id+'" cid="'+cid+'" class="liObj"><span class="no">'+arr.name+'</span></li>';
                })
                
                return '<ul>'+lio+'</ul>';
            }
            
            function lb_c(cat,lib_id){
                if(cat.length == 0) return '';
                
                lic = '';
                
                $.each(cat,function(cid,arr){
                    lic += '<li><span>'+arr.name+'</span>'+lb_o(arr.obj,lib_id,cid)+'</li>';
                })
                
                return '<ul>'+lic+'</ul>';
            }
            
            function lb_n(){
                $.each(libralys,function(lib_id,arr){
                    li += '<li><span>'+arr.name+'</span>'+lb_c(arr.category,lib_id)+'</li>';
                })
            }
            
            $.sl('load','/ajax/<?=$_GET['module']?>/jsLib',{dataType: 'json'},function(j){
                libralys = j,lb_n();
                
                $('#buld .libraly_list').html(li).find('li.liObj').draggable({
                    revert: true,
                    opacity: '0',
                    revertDuration: 0,
                    start: function(){
                        $('#buld').append('<div class="moveObjLib">'+$('span',this).text()+'</div>');
                    },
                    stop: function(){
                        $(obl).remove(),_this = $(this);
                        
                        methods.create_objOfLib(_this.attr('lib_id'),_this.attr('cid'),_this.attr('objid'))
                    },
                    drag: function(event, ui){
                        $(obl).css({left: buld_cur[0],top: buld_cur[1] - 65});
                    },
                    helper: function( event ) {
        				return $("<div></div>");
        			}
                }).on('touchmove',function(e){
                    var dro =  $(obl),e = e.originalEvent.changedTouches[0];
                
                    if(dro.length) dro.css({left: e.pageX,top: e.pageY - 65});
                    else $('#buld').append('<div class="moveObjLib" style="left:'+e.pageX+'px; top: '+(e.pageY - 65)+'px">'+$('span',this).text()+'</div>');
                    
                }).on('touchend',function(e){
                    $(obl).remove(),_this = $(this),e = e.originalEvent.changedTouches[0];
                    
                    methods.create_objOfLib(_this.attr('lib_id'),_this.attr('cid'),_this.attr('objid'))
                });
                
                $.sl('update_scroll');
            })
        },
        scroll: function(oEvent,iDelta){
            if(!iDelta){
                var oEvent = oEvent || window.event;
                var iDelta = oEvent.wheelDelta ? oEvent.wheelDelta/120 : -oEvent.detail/3;
            }
            
            bZizeV = iDelta > 0 ? bZizeV += 10 : bZizeV -= 10;
            bZizeV = bZizeV > 100 ? 100 : bZizeV;
            bZizeV = bZizeV < 30 ? 30 : bZizeV;
            
            $(layer_move).removeClass('_100 _90 _80 _70 _60 _50 _40 _30').addClass('_'+bZizeV);
        },
        btnZoom: function(){
            
            $(this).sl('menu',{
                '<?=$lang[11]?>':function(){
                    methods.scroll(false,1);
                },
                '<?=$lang[12]?>':function(){
                    methods.scroll(false,-1);
                }
            })
        },
        move_obj: function(id){
            var all_obj   = $(layer_move+' .obj:not(#'+id+')'),
                _this_obj = $('#buld #'+id),
                _this_p   = prjOpenJson[prjOpenId]['save'][id].op.top,objs = [],max = [0,''];
              
            $.each(all_obj,function(){
                objs[0] = $(this).attr('id');
                objs[1] = prjOpenJson[prjOpenId]['save'][objs[0]].op.top;
                
                if(objs[1] < _this_p){
                    objs[1] > max[0] && (max = [objs[1],objs[0]]);
                }
            });
            
            if(max[1]) _this_obj.detach().insertAfter('#buld #'+max[1]);
            else if(all_obj.length > 0){
                objs[0] = all_obj.eq(0).attr('id');
                objs[1] = prjOpenJson[prjOpenId]['save'][objs[0]].op.top;
                
                if(objs[1] > _this_p) _this_obj.detach().insertBefore('#buld #'+objs[0]);
            }
            
            methods.info('<?=$lang[13]?>');
        },
        remove_index_arr: function(arr,index,str){
            var n_ar = str ? {} : [];
            $.each(arr,function(i,obj){
                if(i !== index)  n_ar[i] = obj;
            })
            return n_ar;
        },
        max_set: function(x,y){
            max_canvas = [
                x > max_canvas[0] ? x : max_canvas[0],
                y > max_canvas[1] ? y : max_canvas[1]
            ];
        },
        canvas: function(id,a,b,c,d,g,h){
            if(!prjOpenId) return;
            
            var canvas = document.getElementById('buld_canvas_'+prjOpenId);
            
            canvas.width  = max_canvas[0]+500;
            canvas.height = max_canvas[1]+500;
         
            var context = canvas.getContext('2d');
        
            context.clearRect(0, 0, canvas.width, canvas.height);
            
            context.lineWidth = 1;
            
            $(layer_move+' .obj ul.values li b').removeClass('active');

            function attach_obj(attach,obj,ih,id){
                var isd,b_out,b_in,p,pv,top,vid,f_top,v2,elem,is_obj = false;
                
                $.each(attach,function(i,ob){
                    
                    is_obj = typeof ob === 'object' ? true : false;
                    isd    = is_obj ? ob[0] : ob;
                    vid    = is_obj ? ob[1] : ob;
                    
                    if(prjOpenJson[prjOpenId]['save'][isd]){
                        elem= $(layer_move+' #'+id+' ul.values li[n="'+ih+'"]'); // проверяем есть ли это свойство
                        
                        if(!elem.length && ih) return;
                        
                        context.beginPath();
            
                        context.strokeStyle = "#"+((1<<24)*Math.random()|0).toString(16);
                        //context.strokeStyle = "#f47e00";
            
                        top = f_top = ih ? elem.data('top')+obj.op.top+12 : obj.op.top + 36;
                        
                        if(ih) $('b.out',elem).addClass('active');
                        
                        elem= $(layer_move+' #'+isd+' ul.values li[n="'+vid+'"]'); // проверяем есть ли это свойство у другого обьекта
                        
                        if(!elem.length && is_obj) return; //если это свойство и если его нету то пропускаем
                        
                        context.moveTo(obj.op.left + 100,top);
                        
                        pv  = is_obj ? prjOpenJson[prjOpenId]['save'][isd].op : 0;
                        top = is_obj ? elem.data('top')+pv.top+12 : prjOpenJson[prjOpenId]['save'][isd].op.top + 36;
                        
                        if(is_obj) $('b.in',elem).addClass('active');
                        
                        v2  = prjOpenJson[prjOpenId]['save'][isd].op.left - 100;
                        
                        context.bezierCurveTo(obj.op.left + 200,f_top,v2,top,prjOpenJson[prjOpenId]['save'][isd].op.left,top);
                        
                        context.stroke();
                    }
                });
            }
            
            function attach_values(attach,obj,id){
                $.each(attach,function(i,ob){
                    attach_obj(ob.attach,obj,i,id);
                })
            }
            
            $.each(prjOpenJson[prjOpenId]['save'],function(i,obj){
                attach_obj(obj.op.attach,obj);
                attach_values(obj.values,obj,i);
            });
            
            
        },
        /**
         * PRJ function
         */
        savePrj: function(call){
            if(!prjOpenId){
                $.sl('info','<?=$lang[14]?>'); return;
            }
            
            $.sl('load','/ajax/<?=$_GET['module']?>/savePrj/'+prjOpenId,{data:{sort:$('#buld form#form_'+prjOpenId).serializeArray(),prj:JSON.stringify(prjOpenJson[prjOpenId])}},function(){
                methods.prjChange(false,true);
                call && call();
            });
        },
        comPrj: function(){
            methods.savePrj(function(){
                $.sl('load','/ajax/<?=$_GET['module']?>/comPrj/'+prjOpenId);
            })
        },
        comPrjProjected: function(){
            methods.savePrj(function(){
                $.sl('load','/ajax/<?=$_GET['module']?>/comPrj/'+prjOpenId+'/1');
            })
        },
        bindChangePrj: function(j){
            function chVal(ar,prid){
                var ch = false;
                
                $('#buld .conteiner_position_layer#'+prid+' .obj').removeClass('change');
                
                $.each(ar,function(hashId,v){
                    prjOpenJson[prid]['save'][hashId]['values'][v[0]]['value'] = v[1];
                    $('#buld .obj#'+hashId).addClass('change'),ch = 1;
                });
                
                if(ch) methods.prjChange(prid);
            }
            $.each(j,function(prjId,arr){
                chVal(arr,prjId);
            });
        },
        prjChange: function(id,s){
            var id = id ? id : prjOpenId,
                ob = $('#buld ul.prjListLi li#'+id),
                ch = 'change';
            
            prjOpenChange[id] = s ? 0 : 1;
            s ? ob.removeClass(ch) : ob.addClass(ch);
            s ? $(visual_bg).removeClass(ch) : $(visual_bg).addClass(ch);
            s && $('#buld .conteiner_position_layer#'+id+' .obj').removeClass(ch);
            methods.objListNav();
        },
        objListNav: function(search){
            var li = $('#buld ul.objListNav').empty();
            
            function lsi(i,o){
                $('<li><span>'+o.op.name+'</span></li>').appendTo(li).click(function(){
                    var wb = $(visual_bg).width(),
                        hb = $(visual_bg).height(),
                        obj= $('#buld .obj#'+i),
                        pobj = obj.position(),
                        pla  = $(layer_move).position(),
                        wb2 = wb / 2,
                        hb2 = hb / 2,
                        l = wb2 - pobj.left,
                        t = hb2 - pobj.top,
                        ch= 'moved';
                        
                        obj.addClass(ch);
                        
                        $(layer_move).animate({left:l,top:t},300,function(){
                            setTimeout(function(){
                                obj.removeClass(ch);
                            },400)
                        });
                });
            }
            
            $.each(prjOpenJson[prjOpenId].save,function(i,o){
                if(search){
                    if(o.op.name.toLowerCase().indexOf(search.toLowerCase()) >= 0) lsi(i,o);
                }
                else lsi(i,o);
            })
            
            $('#buld #objListNavCo').tinyscrollbar_update('relative');
        },
        openPrj: function(id){
            $.sl('load','/ajax/<?=$_GET['module']?>/openPrj/'+id,{dataType: 'json'},function(j){
                j.save = !j.save ? {} : j.save;
                prjOpenJson[id] = j;
                prjOpenId = id;
                prjOpenChange[id] = 0;
                methods.showListOpenPrj(id);
                
                var bprj = $(visual_bg);
                
                if($('.conteiner_position_layer#'+id,bprj).length) return;
                
                $('.conteiner_position_layer',bprj).removeClass('active');
                
                bprj.append([
                    '<div class="conteiner_position_layer active t_p_a t_top t_left" id="',id,'">',
                        '<form method="post" id="form_',id,'">',
                            '<div class="conteiner_obj t_p_r">',
                                '<canvas height="300" width="300" class="canvas" id="buld_canvas_',id,'"></canvas>',
                            '</div>',
                        '</form>',
                    '</div>'
                ].join(''));
                
                $.each(prjOpenJson[prjOpenId]['save'],function(i,obj){
                    methods.create_obj(i);
                    methods.max_set(obj.op.left,obj.op.top);
                })
                
                methods.canvas();
                methods.prjChange(false,true);
                methods.objListNav();
            });
        },
        showListOpenPrj: function(){
            var li = $('#buld ul.prjListLi').html(''),cl = 'active',ch = 'change';
            
            $.each(prjOpenJson,function(id,a){
                $('<li class="'+(id == prjOpenId ? cl : '')+(prjOpenChange[id] ? ' '+ch : '')+'" id="'+id+'"><span>'+a.name+'</span></li>').appendTo(li).click(function(){
                    $(this).parent().find('li').removeClass(cl);
                    $(this).addClass(cl);
                    
                    $('#buld .conteiner_position_layer').removeClass(cl).parent().find('.conteiner_position_layer#'+id).addClass(cl);
                    
                    prjOpenId = id;
                    
                    prjOpenChange[id] ? $(visual_bg).addClass(ch) : $(visual_bg).removeClass(ch);
                });
            });
            
            $.sl('update_scroll');
        },
        /**
         * Obj Function
         */
        create_objOfLib: function(lib_id,cid_id,obj_id){
            if(!prjOpenId) return;
            
            var objGet = libralys[lib_id]['category'][cid_id]['obj'][obj_id],valu = {},la = $(layer_move).offset();
            
            if(!objGet) return ;
            
            $.each(objGet.op,function(n,a){
                valu[n] = {
                    value: (a.type == 1) ? 0 : (a.type == 2) ? a.val : '',
                    attach: [],
                    val: a.val,
                    type: a.type
                }
            })
            
            methods.create_obj(new Date().getTime()+'_obj',{
                op:{
                    left: buld_cur[0] - la.left < 0 ? 0 : buld_cur[0] - la.left,
                    top: buld_cur[1] - la.top < 0 ? 0 : buld_cur[1] - la.top,
                    status: 1,
                    name:objGet.name,
                    id:obj_id,
                    attach: []
                },
                values: valu
            });
            
            methods.info('<?=$lang[15]?>');
        },
        create_obj: function(id,obj){
            if(!prjOpenId) return;
            
            if(obj)  prjOpenJson[prjOpenId]['save'][id] = obj;
            var obj = (obj ? obj : prjOpenJson[prjOpenId]['save'][id]),ul = '',li_i = '';
            
            if(!obj) return;
            
            $.each(obj.values,function(i,ob){
                ul += '<li n="'+i+'"><b class="in"></b><span>'+i.substr(0,10)+'</span><b class="out"></b></li>';
            });
            
            var html = [
                '<div class="obj" style="left:',obj.op.left,'px; top: ',obj.op.top,'px;" id="',id,'">',
                    '<div class="t_p_r">',
                        '<div class="name_obj">',obj.op.name.substr(0,12),'</div>',
                        '<div class="t_p_5">',
                            '<div class="attach t_clearfix">',
                                '<b class="in"></b><span><?=$lang[16]?></span><b class="out"></b>',
                            '</div>',
                            '<ul class="values">',ul,'</ul>',
                        '</div>',
                        '<div class="onoff',(obj.op.status ? ' on' : ''),'"></div>',
                    '</div>',
                    '<input type="hidden" name="objSort[]" value="',id,'" />',
                '</div>'
            ].join('');
            
            $(layer_move+' .conteiner_obj').append(html);
            
            $.each($('#buld #'+id+' .values li'),function(){
                $(this).data('top',$(this).position()['top']);
            })
            
            /**
             * Obj info end start move
             */
             
            $('#buld .obj#'+id).on('mousedown touchstart', function(){
                if(tools_select == 1){
                    prjOpenJson[prjOpenId]['save'] = methods.remove_index_arr(prjOpenJson[prjOpenId]['save'],$(this).attr('id'),true);
                    $(this).remove();
                    methods.canvas();
                    return;
                }
                
                var _this = $(this),
                    id    = _this.attr('id');
                    
                    gragObj = [
                        true,
                        _this,
                        prjOpenJson[prjOpenId]['save'][id].op.left,
                        prjOpenJson[prjOpenId]['save'][id].op.top,
                        id,
                        prjOpenJson[prjOpenId]['save'][id].op.left,
                        prjOpenJson[prjOpenId]['save'][id].op.top,
                        false
                    ];
                    
                    methods.objProperties(id);
            });
            
            /**
             * Attach obj lines
             */
            
            $('#buld .obj#'+id+' ul.values li b,#buld .obj#'+id+' div.attach b').on('click touchstart',function(e){
                e.preventDefault();
                
                var _in = $(this).hasClass('in') ? 1 : 0,
                    id  = $(this).closest('.obj').attr('id'),
                    v_n = $(this).parent().attr('n'),
                    p_r = $(this).parent().hasClass('attach') ? 1 : 0,
                    attach,d_m = [],d_m_c = [],n_d_m = [];
                
                if(_in){
                    if(select_out[0] == id) return;
                    
                    if(prjOpenJson[prjOpenId]['save'][select_out[0]]){
                        
                        if(select_out[1]){
                            attach = prjOpenJson[prjOpenId]['save'][select_out[0]]['values'][select_out[1]]['attach'];
                            prjOpenJson[prjOpenId]['save'][select_out[0]]['values'][select_out[1]]['attach'][attach.length] = v_n ? [id,v_n] : id;
                        }
                        else{
                            attach = prjOpenJson[prjOpenId]['save'][select_out[0]]['op']['attach'];
                            prjOpenJson[prjOpenId]['save'][select_out[0]]['op']['attach'][attach.length] = v_n ? [id,v_n] : id;
                        }
                        
                        methods.canvas();
                        methods.info('<?=$lang[17]?>');
                    }
                }
                else{
                    if(tools_select == 2){
                        select_out = [id,v_n];
                        methods.info('<?=$lang[18]?>');
                        return;
                    }
                    
                    $(this).sl('menu',{
                        '<?=$lang[19]?>':function(){
                            select_out = [id,v_n];
                        },
                        '<?=$lang[20]?>': function(){
                            d_m = v_n ? prjOpenJson[prjOpenId]['save'][id]['values'][v_n]['attach'] : prjOpenJson[prjOpenId]['save'][id]['op']['attach'];
                            
                            $.each(d_m,function(i,v){
                                if(prjOpenJson[prjOpenId]['save'][typeof v === 'object' ? v[0] : v]) n_d_m[n_d_m.length] = v;
                            })
                            
                            v_n ? prjOpenJson[prjOpenId]['save'][id]['values'][v_n]['attach'] = n_d_m : prjOpenJson[prjOpenId]['save'][id]['op']['attach'] = n_d_m;
                            
                            $.each(n_d_m,function(i,v){
                                var ty = typeof v === 'object' ? v[0] : v;
                                
                                if(prjOpenJson[prjOpenId]['save'][ty]) d_m_c[d_m_c.length] = prjOpenJson[prjOpenId]['save'][ty].op.name;
                            })
                            
                            $(this).sl('scroll_menu',{menu:d_m_c},function(i){
                                if(v_n) prjOpenJson[prjOpenId]['save'][id]['values'][v_n]['attach'] = methods.remove_index_arr(prjOpenJson[prjOpenId]['save'][id]['values'][v_n]['attach'],i);
                                else prjOpenJson[prjOpenId]['save'][id]['op']['attach'] = methods.remove_index_arr(prjOpenJson[prjOpenId]['save'][id]['op']['attach'],i);
                                
                                methods.canvas();
                                methods.info('<?=$lang[17]?>');
                            });
                        }
                    })
                    
                }
            });
            
        },
        editLib: function(id,s,lib_id){
            $.sl('load','/ajax/<?=$_GET['module']?>/editLib/'+(id || 0)+'/'+(s || 0)+'/'+(lib_id || 0),function(data){
                
                if(id) $.sl('window',{status: 'data',name: 'b_l_w', data: data});
                else{
                    $.sl('window',{
                        name: 'b_l_w',
                        data: data,
                        w: 600,
                        h: 400,
                        title: '<?=$lang[4]?>',
                        drag: true,
                        bg: false,
                        size: true,
                        resize: true
                    })
                }
            });
        },
        editPrj: function(id){
            $.sl('load','/ajax/<?=$_GET['module']?>/editPrj/'+(id || 0),function(data){
                
                if(id) $.sl('window',{status: 'data',name: 'b_prj_w', data: data});
                else{
                    $.sl('window',{
                        name: 'b_prj_w',
                        data: data,
                        w: 600,
                        h: 400,
                        title: '<?=$lang[5]?>',
                        drag: true,
                        bg: false,
                        size: true,
                        resize: true
                    })
                }
            });
        },
        editPrjSettings: function(id){
            $.sl('load','/ajax/<?=$_GET['module']?>/editPrjSettings/'+(id || 0),function(data){
                $.sl('window',{
                    name: 'b_prjs_w',
                    data: data,
                    w: 500,
                    h: 300,
                    title: '<?=$lang[21]?>',
                    drag: true,
                    bg: false,
                    autoclose: false,
                    btn:{
                        '<?=$lang[0]?>': function(){
                            $.sl('load','/ajax/<?=$_GET['module']?>/editPrjSettings/'+(id || 0)+'/set',{data:$('form#buld_form_prj_op').serializeArray()})
                        }
                    }
                })
            });
        },
        editObj: function(id){
            $.sl('load','/ajax/<?=$_GET['module']?>/editObj/'+(id || 0),function(data){
                $.sl('window',{
                    name: 'o_b_w',
                    data: data,
                    w: 800,
                    h: 450,
                    title: '<?=$lang[22]?>',
                    drag: true,
                    bg: false,
                    autoclose: false,
                    btn: {
                        '<?=$lang[0]?>':function(){
                            $.sl('load','/ajax/<?=$_GET['module']?>/editObj/'+(id || 0)+'/save',{data:$('#<?=$_GET['module']?>_form_editObj').serializeArray()});
                        }
                    }
                },function(){ methods.editLibAreaCode() })
            });
        },
        editLibAreaCode: function(tx,call){
            
            var _this = $('#buldLibCodeMirror').val(tx || buldLibCodeMirror[0]),spa = _this.parent(),w = spa.width(), h = spa.height(),vals = '';
            
            var editor = CodeMirror.fromTextArea(document.getElementById("buldLibCodeMirror"), {
                lineNumbers: true,
                theme: "ambiance",
                onChange: function(){
                    vals = editor.getValue();
                    _this.val(vals);
                    call && call(vals);
                }
            });
            editor.setSize(w, h);
        },
        buldInfo: function(){
            var data = [
                '<div class="t_p_10 buld_c">',
                '<?=$lang[23]?>: <b class="t_color_w t_shadow">Korner</b><br>',
                '<?=$lang[24]?>: <b class="t_color_w t_shadow">v2.0</b><br><br>',
                '<b class="t_color_w t_shadow">BULD</b> - создан <a href="http://qwarp.sl-cms.com">QWARP</a> студией совместно с <a href="http://sl-cms.com">SL SYSTEM</a>, работающий над тем, чтобы сделать верстку сайтов удобной и быстрой. Мы верим, что BULD должен быть общедоступным ресурсом, открытым и доступным для всех и каждого.',
                '</div>'
            ].join('');
    
            $.sl('window',{
                data: data,
                bg: false,
                w: 400,
                h: 150,
                title: '<?=$lang[25]?>'
            })
        }
    };
    
    $.fn.buld = function( method ) {
    
        if ( methods[method] ) {
          return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.init.apply( this, arguments );
        } else {
          return methods.init.apply( this );
        }   
    
    };
    
    $.buld = function( method ) {
    
        if ( methods[method] ) {
          return methods[ method ].apply( false,Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.init.apply( false, arguments );
        } else {
          return methods.init();
        }   
    
    };
    
})(jQuery);

/**
 * Function BULD
 **/
var buld_addOpObjThisArea = false;

function buld_addOpObj(call){
    call('<tr><td><img src="/modules/<?=$_GET['module']?>/media/img/option_ico.png" /></td><td>'+$.scin('input',{name: 'opObj[name][]',regex:'[^a-z0-9_]'})+'<textarea name="opObj[val][]" style="display: none"></textarea></td><td>'+$.scin('select',{name: 'opObj[type][]',val:['<?=$lang[27]?>','<?=$lang[28]?>','Checkbox'],value: 0,callback: 'buld_addOpObjTypeSelect',attr:{'onclick':"buld_addOpObjThisArea = $(this);"}})+'</td><td>'+$.scin('btn','&#8776;',{attr:{'onclick':"$(this).sl('_tbl_del_tr')"}})+'</td></tr>');
}

function buld_addOpObjTypeSelect(i){
    var area = buld_addOpObjThisArea.closest('tr').find('textarea');
    
    if(i == 1){
        $.sl('_area',{value: area.val(),btn:{
            '<?=$lang[0]?>':function(w,a){
                area.val(a[0].value)
            }
        }})
    }
    else if(i == 2){
        $.sl('_promt',{input:[{holder:'<?=$lang[26]?>',value: parseInt(area.val())}],btn:{
            '<?=$lang[0]?>':function(w,a){
                area.val(parseInt(a[0].value))
            }
        }})
    }
}
function buldSelectValObj(c,id,i){
    $.buld('objSelectValObj',c,id,i)
}