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

    $('input[name=post_title]').on('input', function() {
        console.debug('input ' + new Date());
        if (this.value.length > 1) {
            $(this).attr('list', 'seek_list');
            $(this).searchBooks();
        } else {
            $(this).removeAttr('list')
        }
    // }).on('blur change click dblclick error focus focusin focusout hover keydown keypress keyup load mousedown mouseenter mouseleave mousemove mouseout mouseover mouseup resize scroll select submit', function(){
    }).on('select', function() {
        console.debug('select ' + new Date());
        var id = $("#seek_list option[value='" + this.value + "']").attr('data-id');
        // var selected = $('#seek_list option#' + id);
        var selected = $('#seek_list').find(`[data-id='${id}']`);
        console.log(selected);
        $(selected).searchBook();
    });

    $.fn.searchBooks = function() {
        console.debug('searchBooks()');
        return this.each(function() {
            // Search books by title.
            var url = 'https://www.googleapis.com/books/v1/volumes?q=' + encodeURIComponent(this.value);
            var data = { 'title' : this.value};
            console.debug('searching books ' + url + ' ' + new Date());
            $.getJSON(url, data, function(result) {
                console.debug(result.items);
                var datalist = $('datalist#seek_list');
                datalist.empty();
                result.items.forEach(function(item) {
                    if (item.volumeInfo.title.startsWith(data.title)) {
                        datalist.prepend("<option value='" + item.volumeInfo.title + "' data-id='" + item.id + "'>");
                    }
                 });
            });
        });
    };

    $.fn.searchBook = function() {
        console.debug('searchBook()');
        // Search book by id using data-id.
        var url = 'https://www.googleapis.com/books/v1/volumes/' + this.data('id');
        console.debug('searching book ' + url + ' ' + new Date());
        $.getJSON(url, {}, function(result) {
            console.debug(result);
            var wbg_publisher = result.volumeInfo.publisher;
            $('input[name=wbg_publisher]').val(wbg_publisher);
            var wbg_published_on = result.volumeInfo.publishedDate;
            $('input[name=wbg_published_on]').val(wbg_published_on);
            $('input[name=wbg_isbn]').val(result.volumeInfo.industryIdentifiers[0].identifier);
            $('input[name=wbg_pages]').val(result.volumeInfo.pageCount);
            $('input[name=wbg_country]').val(result.saleInfo.country);
            $('input[name=wbg_language]').val(result.volumeInfo.language);
            $('input[name=wbg_download_link]').val(result.volumeInfo.infoLink);
        });
    };
})(jQuery);