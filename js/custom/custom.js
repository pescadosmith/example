$(document).ready(function() {

    //Fix for dropdown options in Firefox
    $('select, input').attr('autocomplete', 'off')

    $("#sortable1, #sortable2").sortable({connectWith: ".connectedSortable"}).disableSelection();

    $(".rmPAS_btn").click(function() {
        var x = $(this).val();
        $(this).append("<input type='hidden' name='playlistalbumsongid[]' value=" + x + ">")

        postData = $('form').serialize()

        $.ajax({
            type: "POST",
            url: $(location).attr('href'),
            beforeSend: function() {
                waitImage('start');
            },
            data: postData,
            success: function(data) {
                $("body").html(data);
                waitImage('stop');
            },
            complete: function() {
                waitImage('stop');
            },
            error: function(data) {
                alert("Network Error:" + data);
                waitImage('stop');
            }
        });
        return false;
    });

    $("#serialize").click(function() {
        i = 1;
        $("#sortable2 > li").each(function() {
            var v = i++;
            var x = $(this).attr("id");
            $(this).append("<input type='hidden' name='new_sequence[]' value=\"AlbumSongID=>" + x + "~Sequence=>" + v + "\"/>")
        });

        postData = $('form').serialize()

        $.ajax({
            type: "POST",
            url: $(location).attr('href'),
            beforeSend: function() {
                waitImage('start');
            },
            data: postData,
            success: function(data) {
                $("body").html(data);
                waitImage('stop');
            },
            complete: function() {
                waitImage('stop');
            },
            error: function(data) {
                alert("Network Error:" + data);
                waitImage('stop');
            }
        });
        return false;
    });

    $("#save").click(function() {
        var postData = $('form').serialize();
        $.ajax({
            type: "POST",
            url: $(location).attr('href'),
            beforeSend: function() {
                waitImage('start');
            },
            data: postData,
            success: function(data) {
                $("body").html(data);
                waitImage('stop');
            },
            complete: function() {
                waitImage('stop');
            },
            error: function(data) {
                alert("Network Error:" + data);
                waitImage('stop');
            }
        });
        return false;
    });

    $("#logout").click(function() {
        $("form").val('');
        $(location).attr('href', site_url);
    });
});
//END OF READY            


function waitImage(type) {
//place transparent overlay onto screen 
    if (type == 'start') {
        $('#wait_image')
                .css({
                    'position': 'fixed',
                    'top': '50%',
                    'left': '50%',
                    'height': '50',
                    'width': '50',
                    'z-index': 50000
                })
                .html('<img src="http://' + $(location).attr('hostname') + '/img/loaderimage.gif" height="50" width="50" />');
        //alert($('#wait_image').html())
    } else {
        $('#wait_image').html('');
    }

}
