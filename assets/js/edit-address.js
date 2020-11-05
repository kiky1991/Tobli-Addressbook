jQuery(function ($) {
    $(document).on('click', '#delete-address-book', function () {
        if (!confirm('Are you sure?')) {
            return false;
        }

        alert('ok');
    });
});