<?php
$user = get_userdata($result['id_user']);
?>


<table style="width:100%">
    <tr>
        <th colspan="2">
            <h3>Profile</h3>
        </th>
    </tr>
    <tr>
        <th>Full Name:</th>
        <td><?php esc_html_e(wp_sprintf("%s %s", __($user->first_name), __($user->last_name))); ?></td>
    </tr>
    <tr>
        <th>Display Name:</th>
        <td><?php printf(
                '<a href="%s">%s</a>',
                esc_url($this->helper->get_admin_edit_user_link($result['id_user'])),
                esc_html__($user->display_name, 'topdress')
            ); ?></td>
    </tr>
    <tr>
        <th colspan="2">
            <h3>Shipping Address</h3>
        </th>
    </tr>
    <tr>
        <th>Full Name:</th>
        <td><?php esc_html_e(wp_sprintf("%s %s", __($result['first_name']), __($result['last_name']))); ?></td>
    </tr>
    <tr>
        <th>Country:</th>
        <td><?php esc_html_e(__($result['country'])); ?></td>
    </tr>
    <tr>
        <th>State:</th>
        <td><?php esc_html_e(__($result['state'])); ?></td>
    </tr>
    <tr>
        <th>City:</th>
        <td><?php esc_html_e(__($result['city'])); ?></td>
    </tr>
    <tr>
        <th>District:</th>
        <td><?php esc_html_e(__($result['district'])); ?></td>
    </tr>
    <tr>
        <th>Address:</th>
        <td><?php esc_html_e(__($result['address_1'])); ?></td>
    </tr>
    <tr>
        <th>Phone:</th>
        <td><?php esc_html_e(__($result['phone'])); ?></td>
    </tr>
    <tr>
        <th>Postcode:</th>
        <td><?php esc_html_e(__($result['postcode'])); ?></td>
    </tr>
    <tr>
        <th>Address Tag:</th>
        <td><?php esc_html_e(__($result['tag'])); ?></td>
    </tr>
</table>