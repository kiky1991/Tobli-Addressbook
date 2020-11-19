<?php if (is_array($addresses) && count($addresses)) : ?>
    <div class="list-addressbook-header">
        <p class="form-row form-row-wide">
            <input type="text" id="search_addressbook" name="search_addressbook" placeholder="Search Address" />
        </p>
    </div>
    <div class="list-addressbook-body">
        <ul class="topdress-list-addressbook">
            <?php include_once TOPDRESS_PLUGIN_PATH . 'views/checkout-li-addressbook.php'; ?>
        </ul>
    </div>
<?php else : ?>
    <p class=""><?php esc_html_e('Addressbook empty!', 'topdress'); ?></p>
<?php endif; ?>