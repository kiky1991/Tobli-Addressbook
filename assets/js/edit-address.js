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
                window.location.href = response.redirect;
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

    $(document).on('ready', function () {
        $("span.select2").removeAttr('style');
        $("span.select2").css('width:100%! important;');

        $('#topdress_addressbook_table').dataTable({
            "select": true,
            "scrollY": true,
            "dom": '<"topdress-toolbar">frtip',
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "ajax": {
                url: topdress.url,
                type: "POST",
                data: {
                    action: 'topdress_addressbook_datatables',
                    addressbook_datatables_nonce: nonce.datatable
                }
            },
            "bLengthChange": false,
            "bInfo": false,
            "ordering": false,
            "columnDefs": [{
                'targets': 0,
                "className": 'select-checkbox',
                'width': '1%',
                'className': 'dt-body-center',
                'render': function (data, type, full, meta) {
                    return '<input type="checkbox" class="select-checkbox" value="' + data + '">';
                },
            }],
            "select": {
                "style": "os",
                "selector": "td:first-child"
            },
        });

        $(document).on('click', "thead th:first-child .select-checkbox", function (e) {
            const select_all = $(this).prop('checked');
            
            if (select_all == true) {
                $('.select-checkbox').prop('checked', true);
            } else { 
                $('.select-checkbox').prop('checked', false);
            }
        });

        $("div.topdress-toolbar").html(
            '<div class="pull-right">' +
            '<select id="bulk-delete-actions" class="form-control">' +
            '<option value="">Bulk Actions</option>' +
            '<option value="delete">Delete</option>' +
            '</select>' +
            '&nbsp;<button class="dt-button btn small black" type="submit" id="button-bulk-actions">Apply</button></div>');

        $(document).on('click', '#button-bulk-actions', function (e) {
            const action = $('#bulk-delete-actions').val();
            
            if (action == 'delete') { 
                var ids = $("td input:checkbox:checked.select-checkbox").map(function (index) {
                    return $(this).val();
                }).get();

                if (!confirm('Are you sure?')) {
                    return false;
                }
                
                $.ajax({
                    type: 'POST',
                    url: topdress.url,
                    data: {
                        action: 'topdress_bulk_delete_address',
                        ids: ids,
                        topdress_bulk_delete_address: nonce.bulk_delete_address
                    },
                    success: function (response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else { 
                            console.log(response.message);
                        }
                    }
                });
            }

            e.preventDefault();
        });
    });
});