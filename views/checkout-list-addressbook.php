<?php if (is_array($addresses) && count($addresses)) : ?>
    <div class="list-addressbook-header">
        <p class="form-row form-row-wide">
            <input type="text" id="search_addressbook" name="search_addressbook" style="width: 80%;" />
            <button type="submit" id="button-search-addressbook" class="woocommerce-button button view" style="width: 18%;">Search</button>
        </p>
    </div>
    <div class="list-addressbook-body">
        <?php include_once TOPDRESS_PLUGIN_PATH . 'views/checkout-table-addressbook.php'; ?>
    </div>
<?php else : ?>
    <p class=""><?php esc_html_e('Addressbook empty!', 'topdress'); ?></p>
<?php endif; ?>