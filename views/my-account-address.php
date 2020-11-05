<div class="woocommerce-address-fields" style="margin-top: 50px;">
    <div class="woocommerce-address-fields__field-wrapper">
        <div class="u-columns woocommerce-Addresses address-book">
            <div class="woocommerce-Address address-book" style="width: 100%;">
                <header class="woocommerce-Address-Book-title title">
                    <h3><?php esc_html_e('Shipping Address Book', 'topdress'); ?></h3>
                </header>
                <div class="topdress-hscroll">
                    <table width="100%" border="1" cellspacing="0" cellpadding="6">
                        <tbody>
                            <tr>
                                <th><input type="checkbox" name="ids" id=""></th>
                                <th><?php esc_html_e('Full Name', 'topdress'); ?></th>
                                <th><?php esc_html_e('Phone', 'topdress'); ?></th>
                                <th><?php esc_html_e('District', 'topdress'); ?></th>
                                <th><?php esc_html_e('City', 'topdress'); ?></th>
                                <th><?php esc_html_e('Address Tags', 'topdress'); ?></th>
                                <th><?php esc_html_e('Actions', 'topdress'); ?></th>
                            </tr>
                            <?php if (count($addresses)) : ?>
                                <?php foreach ($addresses as $address) : ?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" id=""></td>
                                        <td><?php esc_html_e(wp_sprintf('%1$s %2$s', __($address['first_name']), __($address['last_name']))); ?></td>
                                        <td><?php esc_html_e($address['phone']); ?></td>
                                        <td><?php esc_html_e($address['district']); ?></td>
                                        <td><?php esc_html_e($address['city']); ?></td>
                                        <td><?php esc_html_e($address['tag']); ?></td>
                                        <td>[Edit]&nbsp;[Delete]&nbsp;[Set as default]</td>
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

                </div>
                <a href="http://localhost:9000/my-account/edit-address/billing/" class="add">Add</a>
            </div>
        </div>
    </div>
</div>