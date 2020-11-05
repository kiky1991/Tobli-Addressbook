<div class="woocommerce-address-fields" style="margin-top: 50px;">
    <div class="woocommerce-address-fields__field-wrapper">
        <div class="u-columns woocommerce-Addresses address-book">
            <div class="woocommerce-Address address-book" style="width: 100%;">
                <header class="woocommerce-Address-Book-title title">
                    <h3><?php esc_html_e('Shipping Address Book', 'topdress'); ?></h3>
                </header>
                <div class="table-list-address-book">
                    <?php include_once TOPDRESS_PLUGIN_PATH . 'views/table-list-address-book.php'; ?>
                </div>
                <a href="<?php esc_attr_e(wc_get_page_permalink('myaccount') . 'edit-address/add-addressbook'); ?>" class="add">Add</a>
            </div>
        </div>
    </div>
</div>