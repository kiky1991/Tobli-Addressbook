<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Shipping Address Book', 'topdress'); ?></h1>
    <form action="<?php echo esc_url(admin_url('admin.php?page=topdress-address-book')); ?>" method="POST">
        <?php $list_address->prepare_items($search); ?>
        <?php $list_address->search_box('Search Address', 'search_address'); ?>
        <div class="topdress-setting">
            <div class="setting-section">
                <?php $list_address->display(); ?>
            </div>
        </div>
    </form>
</div>