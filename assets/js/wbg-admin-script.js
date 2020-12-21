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
        if (this.value.length > 1) {
            $(this).attr('list', 'seek_list');
            $(this).searchBooks();
        }
    }).on('keyup', function() {
        var id = $("#seek_list option[value=\"" + this.value + "\"]").attr('data-id');
        console.log(id);
        if (typeof id !== 'undefined') {
            console.log('select ' + new Date());
            var selected = $('#seek_list').find(`[data-id="${id}"]`);
            console.log(selected);
            $(selected).searchBook();
        }
    });

    $.fn.searchBooks = function() {
        console.log('searchBooks()');
        return this.each(function() {
            // Search books by title.
            console.log('Searching title: ' + encodeURIComponent(this.value));
            var url = 'https://www.googleapis.com/books/v1/volumes?q=' + encodeURIComponent(this.value);
            var data = { 'title' : this.value};
            console.log('searching books ' + url + ' ' + new Date());
            $.getJSON(url, data, function(result) {
                console.log(result.items);
                var datalist = $('datalist#seek_list');
                datalist.empty();
                result.items.forEach(function(item) {
                    datalist.prepend("<option value=\"" + item.volumeInfo.title + "\" data-id=\"" + item.id + "\">");
                });
                $("datalist#seek_list option").each(function(){
                    var sameOpt = $(this).parent().find("[value=\"" + this.value + "\"]:gt(0)");
                    sameOpt.val(function(i, val){
                        return val + '-' + (sameOpt.index(this) + 2);
                    });
                });
            });
        });
    };

    $.fn.searchBook = function() {
        console.log('searchBook()');
        // Search book by id using data-id.
        var url = 'https://www.googleapis.com/books/v1/volumes/' + this.data('id');
        console.log('searching book ' + url + ' ' + new Date());
        $.getJSON(url, {}, function(result) {
            console.log(result);
            var wbg_title = result.volumeInfo.title;
            $('input[name=post_title]').val(wbg_title);
            var wbg_description = '';
            if (typeof result.volumeInfo.description !== 'undefined') {
                wbg_description = result.volumeInfo.description;
            } else if (typeof result.volumeInfo.subtitle !== 'undefined') {
                wbg_description = result.volumeInfo.subtitle;
            }
            $('textarea[name=content]').html(wbg_description);
            var wbg_author = '';
            if (typeof result.volumeInfo.authors !== 'undefined') {
                wbg_author = result.volumeInfo.authors.join(', ');
            }
            $('input[name=wbg_author]').val(wbg_author);
            var wbg_publisher = result.volumeInfo.publisher;
            $('input[name=wbg_publisher]').val(wbg_publisher);
            var wbg_published_on = result.volumeInfo.publishedDate;
            $('input[name=wbg_published_on]').val(wbg_published_on);
            var wbg_isbn = '';
            if (typeof result.volumeInfo.industryIdentifiers !== 'undefined' && typeof result.volumeInfo.industryIdentifiers[0].identifier !== 'undefined') {
                wbg_isbn = result.volumeInfo.industryIdentifiers[0].identifier;
            }
            $('input[name=wbg_isbn]').val(wbg_isbn);
            $('input[name=wbg_pages]').val(result.volumeInfo.pageCount);
            $('input[name=wbg_country]').val(result.saleInfo.country);
            $('input[name=wbg_language]').val(result.volumeInfo.language);
            $('input[name=wbg_download_link]').val(result.volumeInfo.infoLink);
            var fifu_input_url = 'https://books.google.ca/googlebooks/images/no_cover_thumb.gif';
            if (typeof result.volumeInfo.imageLinks !== 'undefined' && typeof result.volumeInfo.imageLinks.thumbnail !== 'undefined') {
                fifu_input_url = result.volumeInfo.imageLinks.thumbnail;
            }
            $('input[name=fifu_input_url]').val(fifu_input_url);
        });
    };

})(jQuery);
