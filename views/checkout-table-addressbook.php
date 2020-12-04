<?php
$hidden = ['id_user', 'address_2', 'created_at', 'updated_at'];
?>

result:
<table class="table" id="topdress-table-addressbook-checkout">
    <thead>
        <th>Full Name</th>
        <th>Phone</th>
        <th>District</th>
        <th>City</th>
        <th>Address Tags</th>
        <th>Actions</th>
    </thead>
    <?php foreach ($addresses as $address) : ?>
        <tr>
            <td><?php esc_html_e(wp_sprintf('%1$s %2$s', __($address['first_name']), __($address['last_name']))); ?></td>
            <td><?php esc_html_e($address['phone']); ?></td>
            <td><?php esc_html_e($address['district']); ?></td>
            <td><?php esc_html_e($address['city']); ?></td>
            <td><?php esc_html_e($address['tag']); ?></td>
            <td><a id="topdress-select-addressbook"  <?php foreach ($address as $key => $value) : ?> <?php if (!in_array($key, $hidden)) : ?> address-<?php esc_html_e($key); ?>="<?php esc_attr_e($value); ?>" <?php endif; ?> <?php endforeach; ?>><?php esc_html_e('[select]'); ?></a></td>
        </tr>
    <?php endforeach; ?>
</table>