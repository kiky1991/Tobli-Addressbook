<div class="tablenav top">
    <div class="alignleft actions bulkactions">
        <?php $this->bulk_actions($which); ?>
    </div>

    <div class="alignleft">
        <select name="topdress_user_id" id="topdress-search-customer-select2">
            <option value=""><?php esc_html_e('-- Search customer --', 'topdress'); ?></option>
        </select>
        <input name="search_customer_address" type="submit" id="dosearch" class="button" value="Search">
    </div>
    <div class="alignright">
        <?php $this->extra_tablenav($which); ?>
        <?php $this->pagination($which); ?>
    </div>
</div>