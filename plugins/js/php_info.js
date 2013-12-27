$(document).ready(function() {
    $('#bg_img').load(function(){
        setTimeout(function(){
            $('#bg').addClass('bgload');
            $('#error').animate({
                marginTop:'-103px',
                marginLeft:'-205px',
                width: 411,
                height: 206,
                opacity: 1
            },300);
        },1000)
    })
});