function str_pad( input, pad_length, pad_string, pad_type ) {

	var half = '', pad_to_go;

	var str_pad_repeater = function(s, len){
			var collect = '', i;

			while(collect.length < len) collect += s;
			collect = collect.substr(0,len);

			return collect;
		};

	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') { pad_type = 'STR_PAD_RIGHT'; }
	if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type == 'STR_PAD_LEFT') { input = str_pad_repeater(pad_string, pad_to_go) + input; }
		else if (pad_type == 'STR_PAD_RIGHT') { input = input + str_pad_repeater(pad_string, pad_to_go); }
		else if (pad_type == 'STR_PAD_BOTH') {
			half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
			input = half + input + half;
			input = input.substr(0, pad_length);
		}
	}

	return input;
}

function timeDifference(begin, end, lim) {
    if (end < begin) {
	    return false;
    }
    
    lim = !lim ? 6 : lim;
    var countlim = 0;
    
    var diff = {
    	seconds: [end.getSeconds() - begin.getSeconds(), 60],
    	minutes: [end.getMinutes() - begin.getMinutes(), 60],
    	hours: [end.getHours() - begin.getHours(), 24],
    	days: [end.getDate()  - begin.getDate(), new Date(begin.getYear(), begin.getMonth() + 1, 0).getDate()],
    	months: [end.getMonth() - begin.getMonth(), 12],
    	years: [end.getYear()  - begin.getYear(), 0]
    };
    var result = new Array();
    var flag = false;
    for (i in diff) {
    	if (flag) {
    		diff[i][0]--;
    		flag = false;
    	}    	
    	if (diff[i][0] < 0) {
    		flag = true;
    		diff[i][0] += diff[i][1];
    	}
    	
	    result.push(str_pad(''+diff[i][0], 2, '0', 'STR_PAD_LEFT') + '.' );
        
        countlim += 1;
        
        if(countlim >= lim) break;
    }
    result =  result.reverse().join(' ');
    
    return result.substr(0,result.length-1);
};

jQuery.fn.countdown = function (date, options) {
	options = jQuery.extend({
		lim:6
	}, options);
    
	var elem = $(this);
    
	var timeUpdate = function () {
	    var s = timeDifference(new Date(), date, options.lim);
	    if (s.length) {
	    	elem.html(s);
            Cufon.replace(elem);
	    } else {
	        clearInterval(timer);
	    }		
	};

	timeUpdate();
	var timer = setInterval(function(){timeUpdate()}, 1000);		
};



$(document).ready(function(){
    $('.loading').fadeOut();
    
    
    var sNow = timeDifference(new Date(), new Date(date));
    
    if(sNow.length) $("#dateReady").countdown(new Date(date));
    else{
        $('.time').css({opacity:0});
        $("#dateReady").html('00.00.00.00.00.00');
    } 
    
        Cufon.replace("h1,h2,h3,h4,h5,h6");
        
        $('.dark').show().delay(700).fadeOut(5000);
        $('#wrap').delay(2000).fadeIn(4000);
        
        titles();
        
        bg_animate();
    
});

function bg_animate(){
    var p_a = rand(45,55);
    var p_b = rand(35,65);
    var h_a = rand(0,4);
    var h_b = rand(0,10);
    var t = rand(2,4)*1000;
    console.log(p_a);
    setTimeout(function(){
        $('.bg').animate({backgroundPosition: '('+p_a+'% '+h_a+'%)'},t);
        $('.bg2').animate({backgroundPosition: '('+p_b+'% '+h_b+'%)'},t,function(){
            bg_animate();
        });
    },rand(5,14)*1000);
}
function titles(){
    var elems = $('.titles').find('h1');
    var cElem = elems.length;
    var cPoint = 0;
    
    elems.hide();
    
    setInterval(function(){
        
        elems.eq(cPoint).fadeOut();
        
        cPoint += 1;
        
        cPoint = cPoint >= cElem ? 0 : cPoint;
        
        elems.eq(cPoint).fadeIn();
        
    },3000);
}

function rand(min,max){
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
