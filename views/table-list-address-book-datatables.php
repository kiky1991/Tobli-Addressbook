<table id="table-addressbook" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" name="ids" id="topdress-check-all"></th>
            <th><?php esc_html_e('Full Name', 'topdress'); ?></th>
            <th><?php esc_html_e('Phone', 'topdress'); ?></th>
            <th><?php esc_html_e('District', 'topdress'); ?></th>
            <th><?php esc_html_e('City', 'topdress'); ?></th>
            <th><?php esc_html_e('Address Tags', 'topdress'); ?></th>
            <th><?php esc_html_e('Actions', 'topdress'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($addresses)) : ?>
            <?php foreach ($addresses as $address) : ?>
                <tr>
                    <td><input type="checkbox" name="ids[]" value="<?php esc_attr_e($address['id_address']); ?>"></td>
                    <td><?php esc_html_e(wp_sprintf('%1$s %2$s', __($address['first_name']), __($address['last_name']))); ?></td>
                    <td><?php esc_html_e($address['phone']); ?></td>
                    <td><?php esc_html_e($address['district']); ?></td>
                    <td><?php esc_html_e($address['city']); ?></td>
                    <td><?php esc_html_e($address['tag']); ?></td>
                    <td>[Edit]&nbsp;
                        <a id="delete-address-book" address-id="<?php esc_attr_e($address['id_address']); ?>">[Delete]</a>&nbsp;
                        <?php if ($address_id !== $address['id_address']) : ?>
                            <a id="set-address-book" address-id="<?php esc_attr_e($address['id_address']); ?>">[Set as default]</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="7">
                    <div style="margin:auto;text-align:center;"><?php esc_html_e('Address not result', 'topdress'); ?></div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script type="text/javascript">
    jQuery(function($) {
        $("#topdress-check-all").change(function() {
            if (this.checked == true) {
                $('input[type=checkbox]').prop('checked', true);
            } else {
                $('input[type=checkbox]').prop('checked', false);
            }
        });
    });
</script>