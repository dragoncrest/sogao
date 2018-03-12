/**
* check if user logged in, buyed or not document, have any coin
* @param string idDoc
* @param string status
*/
function checkUserStatus(idDoc, status, coin)
{
    if (status == BUYED) {
        window.open(urlDocument + idDoc, '_blank');
    } else if (status == NOTCOIN) {
        str   = "Số KEY của bạn không đủ để mở văn bản này. ";
        str  += "Click vào <a href='" + urlHowToBuy + "'>đây</a> để nạp thêm";
        idDoc = 0;
    } else if (status == LOGIN) {
        str   = "Chức năng này chỉ dành cho thành viên";
        idDoc = 0;
    } else if (status == BUY) {
        str  = "Bạn đang có " + coin + " KEY, mất 1 KEY để xem bài viết này";
    }
    if (status != BUYED) {
        var template = $('#dialogStatus-template').html();
        var compiled = _.template(template);
        $('#dialog .pop-content').html(compiled({_str: str, _idDoc: idDoc}));
        showPopupDialog('#dialog');
    }
}

/**
* run ajax to buy document
* @param string id
*/
function buyDocument(id)
{
    sessionStorage.scrollTop = $(window).scrollTop();
    var str      = "Đang xử lý";
    var template = $('#dialogStatus-template').html();
    var compiled = _.template(template);
    $('#dialog .pop-content').html(compiled({_str: str, _isBuying: true}));

    $.ajax({
        type: "GET",
        url: urlAjaxBuyDocument + id,
        dataType: 'JSON',
        cache: false,
        async: false,
        success: function (data) {
            if (data.status) {
                sessionStorage.buy = 1;
                location.reload();
            } else {
                hidePopupDialog('#dialog');
            }
        },
        error: function (data) {
            hidePopupDialog('#dialog');
            console.log('Error:', data);
        }
    });
}
$(document).ready(function() {
    //check status of user with document when directly accessing to document
    if (status) {
        checkUserStatus(sttDoc, status);
    }

    var buy    = parseInt(sessionStorage.buy);
    var scroll = parseInt(sessionStorage.scrollTop);
    //display "-1 coin" after buying a document
    if (!isNaN(buy)) {
        $("#minus-coin").show().animate({top: '5%'});
        setTimeout(function() {
            $("#minus-coin").fadeOut();
        }, 1500);
        sessionStorage.removeItem("buy");
    }
    //scroll to current position after buying a document
    if (!isNaN(scroll)) {
        $(window).scrollTop(scroll);
        sessionStorage.removeItem("scrollTop");
    }
});