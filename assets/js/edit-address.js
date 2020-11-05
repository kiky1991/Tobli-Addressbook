jQuery(function ($) {
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
                topdress_nonce: topdress.nonce
            },
            success: function (response) {
                $('.table-list-address-book').html(response);
            }
        });
    });

    $(document).on('ready', function () {
        $("span.select2").removeAttr('style');
        $("span.select2").css('width:100%! important;');
    });
});