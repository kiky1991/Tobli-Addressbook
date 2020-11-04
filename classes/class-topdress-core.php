<?php

if (!class_exists('TOPDRESS_Core')) {

    /**
     * Core Class
     */
    class TOPDRESS_Core
    {

        /**
         * TOPDRESS_Core::delete_addressbook
         * 
         * Delete addressbook
         * @param   array   $ids    array id
         * 
         * @return  bool|int    bool if false
         */
        public function delete_addressbook($ids = array())
        {
            global $wpdb;
            $table = $wpdb->prefix . "topdress_address_book";

            $id = implode(',', $ids);
            return $wpdb->query("DELETE FROM $table WHERE id_address IN($id)");
        }

        /**
         * TOPDRESS_Core::search_addressbook
         * 
         * Search addressbook
         * @param   array   $ids    array id
         * 
         * @return  array|bool    bool if false
         */
        public function search_addressbook($address_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "topdress_address_book";

            return $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $table WHERE id_address = %d",
                    $address_id
                ),
                ARRAY_A
            );
        }
    }
}
