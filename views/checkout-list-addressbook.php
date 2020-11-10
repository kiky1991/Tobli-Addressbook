<?php
$hidden = ['id_user', 'address_2', 'created_at', 'updated_at'];
?>

<?php if (is_array($addresses) && count($addresses)) : ?>
    <ul class="topdress-list-addressbook">
        <?php foreach ($addresses as $address) : ?>
            <li <?php foreach ($address as $key => $value) : ?> <?php if (!in_array($key, $hidden)) : ?> address-<?php esc_html_e($key); ?>="<?php esc_attr_e($value); ?>" <?php endif; ?> <?php endforeach; ?>>
                <?php esc_html_e(wp_sprintf('%1$s %2$s', __($address['first_name']), __($address['last_name']))); ?><br />
                <?php esc_html_e($address['address_1']); ?><br />
                <?php esc_html_e(wp_sprintf('%1$s, %2$s (%3$s)', __($address['district']), __($address['city']), __($address['postcode']))); ?><br />
                <?php esc_html_e($address['phone']); ?> - <b><?php esc_html_e($address['tag']); ?></b><br />
            </li>
        <?php endforeach; ?>
        <li class="topdress-load-more"><?php esc_html_e('Load More', 'topdress'); ?></li>
    </ul>
<?php else : ?>
    <p class=""><?php esc_html_e('Addressbook empty!', 'topdress'); ?></p>
<?php endif; ?>