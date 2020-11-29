(function($) {

    // USE STRICT
    "use strict";

    var wbgColorPicker = ['#wbg_btn_color', '#wbg_btn_font_color', '#wbg_btn_border_color'];

    $.each(wbgColorPicker, function(index, value) {
        $(value).wpColorPicker();
    });

    $("#wbg_published_on").datepicker({
        dateFormat: "yy-mm-dd"
    });

    $('.wbg-closebtn').on('click', function() {
        this.parentElement.style.display = 'none';
    });

    $('input[name=post_title]').on('change', function() {
        console.debug('change ' + new Date());
        if (this.value.length > 1) {
            $(this).attr('list', 'seek_list');
            $(this).searchBooks();
        } else {
            $(this).removeAttr('list')
        }
    // }).on('input', function () {
    //     console.debug(this);
    //     var val = this.value;
    //     console.debug(this.value);
    //     if ($('#seek_list option').filter(function() {
    //         console.debug('datalist input');
    //         console.debug(this.value.toUpperCase() === val.toUpperCase());
    //     }).length) {
    //         //send ajax request
    //         console.debug(this.value);
    //     }
    // }).on('blur change click dblclick error focus focusin focusout hover keydown keypress keyup load mousedown mouseenter mouseleave mousemove mouseout mouseover mouseup resize scroll select submit', function(){
    }).on('input select', function() {
        console.debug(arguments[0]);
        var id = $("#seek_list option[value='" + this.value + "']").attr('data-id');
        console.debug($('#seek_list'));
        console.debug($('#seek_list').find(`[data-id='${id}']`));
        var selected = $('#seek_list option#' + id);
        console.log(selected);
        selected.searchBook();
    });
    // .on('focusout', function() {
    //     console.debug('datalist click');
    //     console.debug(this);
    //     var selected = $('datalist#seek_list').val();
    //     console.debug(selected);
    //     $('#wbg_publisher').val(selected.attr('data-publisher'));
    // });
    // .on('keydown', function(e) {
    //     console.debug('keydown ' + new Date());
    //     var code = e.keyCode || e.which;
    //     if (code != 8 && code != 46) { //Backspace or Delete
    //         // Do nothing, it will be processed on keyup.
    //         return;
    //     }
    //     if (this.value.length > 1) {
    //         $(this).attr('list', 'seek_list');
    //         $(this).searchBooks();
    //     } else {
    //         $(this).removeAttr('list')
    //     }
    // });

    // $('datalist[name=seek_list]').on('click', function() {
    //     console.debug('datalist click');
    //     console.debug(this);
    //     var selected = $(this).val();
    //     console.debug(selected);
    //     $('#wbg_publisher').val(selected.attr('data-publisher'));
    // });

    $.fn.searchBooks = function() {
        return this.each(function() {
            // Search books by title.
            var url = 'https://www.googleapis.com/books/v1/volumes?q=' + encodeURIComponent(this.value);
            var data = { 'title' : this.value};
            console.debug('searching books ' + url + ' ' + new Date());
            $.getJSON(url, data, function(result) {
                console.debug(result.items);
                var datalist = $('datalist#seek_list');
                datalist.empty();
                // console.debug(data.title);
                result.items.forEach(function(item) {
                    if (item.volumeInfo.title.startsWith(data.title)) {
                        // console.debug(item);
                        // console.debug(item.volumeInfo.title);
                        datalist.prepend("<option id='" + item.id + "' value='" + item.volumeInfo.title + "' data-id='" + item.id + "'>");
                    }
                 });
            });
        });
    };

    $.fn.searchBook = function() {
        return this.each(function(){
            // Search book by id using data-id.
            var url = 'https://www.googleapis.com/books/v1/volumes/' + $(this).attr("data-id");
            console.debug('searching book ' + url + ' ' + new Date());
            $.getJSON(url, data, function(result) {
                console.debug(result.items);
            });
        });
    };
})(jQuery);