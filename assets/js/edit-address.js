jQuery(function ($) {
    $('#search-address-book').on('keyup', function (e) {
        let term = $(this).val();

        if (term.length == 0 || term.length >= 3) {
            $.ajax({
                type: 'POST',
                url: topdress.url,
                data: {
                    action: 'topdress_search_address_term',
                    term: term,
                    topdress_search_term_nonce: nonce.search_term
                },
                success: function (response) {
                    if (response) {
                        $('.table-list-address-book').html(response);
                    }
                }
            });
        }

        e.preventDefault();
    });
    
    $(document).on('click', '#delete-address-book', function () {
        if (!confirm('Are you sure?')) {
            return false;
        }

        const address_id = $(this).attr('address-id');

        $.ajax({
            type: 'POST',
            url: topdress.url,
            data: {
                action: 'topdress_delete_address',
                address_id: address_id,
                topdress_delete_nonce: nonce.delete
            },
            success: function (response) {
                $('.table-list-address-book').html(response);
            }
        });
    });

    $(document).on('click', '#set-address-book', function () {
        if (!confirm('Are you sure?')) {
            return false;
        }

        const address_id = $(this).attr('address-id');

        $.ajax({
            type: 'POST',
            url: topdress.url,
            data: {
                action: 'topdress_set_default_address',
                address_id: address_id,
                topdress_set_default_nonce: nonce.set_default
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else { 
                    console.log(response.message);
                }
            }
        });
    }); 
    
    $('#topdress-submit-bulk-actions').on('click', function (e) {
        const ids = $("input[name='ids[]']")
            .map(function () {
                if (this.checked == true) {
                    return $(this).val();
                }
            }).get();
        alert(ids);
        // $.ajax({
        //     type: 'POST',
        //     url: topdress.url,
        //     data: {
        //         action: 'topdress_set_default_address',
        //         address_id: address_id,
        //         topdress_set_default_nonce: nonce.set_default
        //     },
        //     success: function (response) {
        //         if (response.success) {
        //             window.location.href = response.redirect;
        //         } else { 
        //             console.log(response.message);
        //         }
        //     }
        // });

        e.preventDefault();
    });

    $(document).on('ready', function () {
        $("span.select2").removeAttr('style');
        $("span.select2").css('width:100%! important;');
    });
});