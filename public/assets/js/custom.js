$(document).ready(function(){

// animation vertical menu ---------------------------------------------------------
    $("#vertical-menu p").click(function(){
        //slide up all the link lists
        $("#vertical-menu ul").slideUp();
        //slide down the link list below the h3 clicked - only if its closed
        if(!$(this).nextAll("ul").eq(0).is(":visible"))
        {
            // $(this).next("ul").slideDown();
            $(this).nextAll("ul").eq(0).slideDown("slow");
        }
    });
//  END -----------------------------

// animation title index and request ajax ---------------------------------------------------------
    $(".main-title-index").click(function(){
        var id = $(this).attr('id');
        id    = id.slice(-1);
        
        for(i=1; i<=4; i++){
            if(id == i) continue;
            var t = "#title-index-" + i;
            $(t).animate({
                height: 'toggle'
            });
        }
        
        if ($("#content").html().length > 0) {
            $( "#content" ).html( "" );
            return;
        } 
          
        $.ajaxx("home/ajax/"+id, "#content");
    });
//  END -----------------------------
    
// ajax ---------------------------------------------------------
    $.ajaxx = function(link, attr) {
        var request = $.ajax({
            //async: false,
              url: 'http://' + window.location.hostname + link,
              method: "GET",
              //data: { id : id },
              //dataType: "html"
        });

        request.done(function( msg ) {
          $( attr ).html( msg );
        });

        request.fail(function( jqXHR, textStatus ) {
          console.log( "Request failed: " + textStatus );
        });        
    };
//  END -----------------------------

// set time to hide announcement when uploading data in admin panel ---------------------------------------------------------    
    setTimeout(function(){
        $('#upload').fadeOut(1000);
    }, 1500);
//  END -----------------------------

// siderbar menu toggle ---------------------------------------------------------
    // $(".sidebar-main > li > a").click(function(e) {
        // e.preventDefault();
        // var $this = $(this);        
        // $this.parent().children("ul").stop(true, true).slideToggle("normal");
    // });
//  END -----------------------------

// check search form before submit ---------------------------------------------------------
    $.CheckSearch = function() {
        $v = $("#sear-inp").val();
        if(($v == "tìm kiếm.....") || ($v == '') || ($v == ' '))
            return false;
        return true;
    };
    $("#sear-inp").focusin(function(){
        $value = $( this ).val();
        if($value == 'tìm kiếm.....') $( this ).val('');
        $( this ).css("font-style", "normal");
    });
    $("#sear-inp").focusout(function(){
        $value = $( this ).val();
        if($value == '') $( this ).val('tìm kiếm.....');
        if($value == ' ') $( this ).val('tìm kiếm.....');
        $( this ).css("font-style", "italic");
    });    
//  END -----------------------------

});

// detect what mouse is pressed---------------------------------------------------------
$.hihi = function(event) {
    switch (event.which) {
        case 1:
            alert('Left Mouse button pressed.');
            break;
        case 2:
            alert('Middle Mouse button pressed.');
            break;
        case 3:
            alert('Right Mouse button pressed.');
            break;
        default:
            alert('You have a strange Mouse!');
    }
};
//  END -----------------------------

// ---------------------------------------------------------
$.DisplayContent = function(i) {
    if(i==1){
        $("#tab-header-1").addClass("tab-active");
        $("#tab-header-2").removeClass("tab-active");
            
        $("#tab-content-1").css("display", "block");
        $("#tab-content-2").css("display", "none");
    }
    if(i==2){
        $("#tab-header-1").removeClass("tab-active");
        $("#tab-header-2").addClass("tab-active");
        
        $("#tab-content-1").css("display", "none");
        $("#tab-content-2").css("display", "block");
    }
};
//  END -----------------------------
    
// remove fancybox when click outside content ---------------------------------------------------------
//---- request ajax when click 'Thông tư 05/2014/TT-BTC' ---------
    $(document).mousedown(function(event) {
        
        var myClass = $(event.target).attr('class');
           if(myClass){
               //request ajax when left click
            if((myClass=='linktab') && (event.which==1)){
                $.DisplayContent(2);
                
                id   = $(event.target).attr('id');            
                $.ajaxx("/document/ajaxDieuKhoan/" + id, '#tab-content-2');
                return;
            }
            
            //remove fancybox
               myClass = myClass.split(" ", 1);
               if(myClass[0] == 'fancybox-slide'){
                   $( ".fancybox-container" ).fadeOut(300, function() { $(this).remove(); });
                   $( 'html' ).removeAttr('class');
               }
        }
    });
//  END -----------------------------

// show popup dialoag ---------------------------------------------------------
function showPopupDialog(id)
{
    $(id).show();
    $(id).animate({
        left: '55%',
        opacity: '1'
    }, 'slow', 'swing');
    $('.pop-close-but').on("click", function() {
        hidePopupDialog('#dialog');
    });
}
//  END ----------------------------- showPopupDialog

// hide popup dialoag ---------------------------------------------------------
function hidePopupDialog(id)
{
    $(id).fadeOut(function(){
        $('#dialog').css({left:'60%', opacity: '0.5'});
        $(id + ' .pop-content').html('');
    });
}
//  END ----------------------------- hidePopupDialog
// x -  ---------------------------------------------------------
//  END ----------------------------- x