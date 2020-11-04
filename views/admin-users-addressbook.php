<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Shipping Address Book', 'topdress'); ?></h1>
    <form action="<?php echo esc_url(admin_url('admin.php?page=topdress-address-book')); ?>" method="POST">
        <?php $list_address->prepare_items($id_user); ?>
        <div class="topdress-setting">
            <div class="setting-section">
                <?php $list_address->display(); ?>
            </div>
        </div>
    </form>

    <?php add_thickbox(); ?>
    <div id="topdress-popup-view" style="display:none;width:100%">
        <div class="topbli-loader"></div>
        <div class="topdress-popup-content">
        </div>
    </div>
</div>