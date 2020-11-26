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

        var rows_selected = [];
        var table = $('#topdress_addressbook_table').dataTable({
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
            'columnDefs': [{
                'targets': 0,
                'checkboxes': true,
                'width': '1%',
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                    return '<input type="checkbox">';
                },
            }],
            'rowCallback': function(row, data, dataIndex){
                // Get row ID
                var rowId = data[0];
    
                // If row ID is in the list of selected row IDs
                if($.inArray(rowId, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            }
        });

        $("div.topdress-toolbar").html(topdress.add_address + ' | ' + topdress.delete_address);

        // Handle click on checkbox
        $('#topdress_addressbook_table tbody').on('click', 'input[type="checkbox"]', function (e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = table.row($row).data();

            // Get row ID
            var rowId = data[0];

            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if(this.checked && index === -1){
            rows_selected.push(rowId);

            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1){
            rows_selected.splice(index, 1);
            }

            if(this.checked){
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });
    });

    $(document).on('click', '#bulk-delete-addressbook', function (e) {
        var ids = $.map(table.rows('.selected').data(), function (item) {
            return item[0]
        });
        console.log(ids)
        alert(table.rows('.selected').data().length + ' row(s) selected');
        e.preventDefault();
    });

    function updateDataTableSelectAllCtrl(table){
        var $table             = table.table().node();
        var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);
    
        // If none of the checkboxes are checked
        if($chkbox_checked.length === 0){
        chkbox_select_all.checked = false;
        if('indeterminate' in chkbox_select_all){
            chkbox_select_all.indeterminate = false;
        }
    
        // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length){
        chkbox_select_all.checked = true;
        if('indeterminate' in chkbox_select_all){
            chkbox_select_all.indeterminate = false;
        }
    
        // If some of the checkboxes are checked
        } else {
        chkbox_select_all.checked = true;
        if('indeterminate' in chkbox_select_all){
            chkbox_select_all.indeterminate = true;
        }
        }
    }
});