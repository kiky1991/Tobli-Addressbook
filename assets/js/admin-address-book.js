jQuery(function ($) {
	$('#topdress-search-customer-select2').select2({
        ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 250, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    search: params.term, // search query
                    action: 'topdress_search_customer'
                };
            },
            processResults: function (data) {
                var options = [];

                if (data) {
                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    $.each(data, function (index, text) { // do not forget that "index" is just auto incremented value
                        options.push({
                            id: text[0],
                            text: text[1]
                        });
                    });
                }

                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3 // the minimum of symbols to input before perform a search
    });
    
    $(document).on('click', '.topdress-view-address', function () {
        const id_address = $(this).attr('address-id');

        if (!id_address) {
            return false;
        }

        tb_show('View Address ', "#TB_inline?width=400&inlineId=topdress-popup-view");
        $("#TB_window").css({
            'width': '400px',
            'height': '400px',
            'margin': '0 -200px'
        });
        $('.topbli-loader').removeClass('hidden');
        $('.topdress-popup-content').html('');

        $.ajax({
            type: 'POST',
            url: topdress.url,
            data: {
                action: 'topdress_view_address',
                id_address: id_address,
                topdress_nonce: topdress.nonce
            },
            success: function (response) {
                $('.topbli-loader').addClass('hidden');
                $('div.topdress-popup-content').html(response);
            }
        });
    });
});