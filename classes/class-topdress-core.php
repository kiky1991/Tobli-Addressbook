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
        public function search_addressbook($address_id, $andwhere = '')
        {
            global $wpdb;
            $table = $wpdb->prefix . "topdress_address_book";

            return $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $table WHERE id_address = %d {$andwhere}",
                    $address_id
                ),
                ARRAY_A
            );
        }

        /**
         * TOPDRESS_Core::list_addressbook
         * 
         * Get addressbook
         * 
         * @return  array|bool    bool if false
         */
        public function list_addressbook($query = array(), $limit = 10, $offset = 0, $separator = 'AND')
        {
            global $wpdb;
            $table = $wpdb->prefix . 'topdress_address_book';

            $where = '';
            if (count($query) > 0) {
                $dump = array();
                $numeric = ['id_user', 'id_address'];
                foreach ($query as $key => $value) {
                    if (!in_array($key, $numeric)) {
                        $new_value = "'{$value['value']}'";
                    } else {
                        $new_value = intval($value['value']);
                    }

                    $dump[] = "{$key} {$value['separator']} {$new_value}";
                }

                $where = "WHERE " . implode(" {$separator} ", $dump);
            }

            return $wpdb->get_results(
                "SELECT * FROM $table
                    $where
                    ORDER BY created_at DESC
                    LIMIT $limit OFFSET $offset",
                ARRAY_A
            );
        }

        /**
         * TOPDRESS_Core::is_addressbook
         * 
         * Check is addressbook
         * 
         * @return  bool    bool if false
         */
        public function is_addressbook($id)
        {
            if (intval($id) < 1) {
                return false;
            }

            $q = array(
                'id_address'   => array(
                    'separator' => '=',
                    'value'     => intval($id)
                )
            );

            $result = $this->list_addressbook($q, 1);
            if ($result && $result[0]['id_user'] == get_current_user_id()) {
                return $result;
            }

            return false;
        }

        /**
         * TOPDRESS_Core::update_addressbook
         * 
         * Get addressbook
         * 
         * @return  array|bool    bool if false
         */
        public function update_addressbook($data, $update = false)
        {
            global $wpdb;

            $table = $wpdb->prefix . 'topdress_address_book';
            if (!$update) {
                try {
                    return $wpdb->insert(
                        $table,
                        array(
                            'id_user'   => $data['id_user'],
                            'first_name'   => $data['first_name'],
                            'last_name'    => $data['last_name'],
                            'country'      => $data['country'],
                            'state_id'    => $data['state_id'],
                            'state'       => $data['state'],
                            'city_id'      => $data['city_id'],
                            'city'      => $data['city'],
                            'district_id'      => $data['district_id'],
                            'district'      => $data['district'],
                            'address_1'      => $data['address_1'],
                            'address_2'      => $data['address_2'],
                            'phone'      => $data['phone'],
                            'postcode'      => $data['postcode'],
                            'tag'      => $data['tag'],
                        )
                    );
                } catch (Exception $e) {
                    return false;
                }
            } else {
                try {
                    return $wpdb->update(
                        $table,
                        array(
                            'id_user'   => $data['id_user'],
                            'first_name'   => $data['first_name'],
                            'last_name'    => $data['last_name'],
                            'country'      => $data['country'],
                            'state_id'    => $data['state_id'],
                            'state'       => $data['state'],
                            'city_id'      => $data['city_id'],
                            'city'      => $data['city'],
                            'district_id'      => $data['district_id'],
                            'district'      => $data['district'],
                            'address_1'      => $data['address_1'],
                            'address_2'      => $data['address_2'],
                            'phone'      => $data['phone'],
                            'postcode'      => $data['postcode'],
                            'tag'      => $data['tag'],
                        ),
                        array(
                            'id_address'   => $data['id_address']
                        )
                    );
                } catch (Exception $e) {
                    return false;
                }
            }
        }
    }
}
