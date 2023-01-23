<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


if (!class_exists('Meps_Helper')) {
    class Meps_Helper
    {
        public function meps_cart_meet_the_service_condition($meps_conditions = array())
        {
            $condition_meet = false;
            $cart = WC()->cart->cart_contents;
            $meps_conditions = !$meps_conditions ? get_option('meps_fields', array()) : $meps_conditions;

            if($cart) {
                
                foreach($cart as $item) {
                    $product_id = $item['product_id'];
                    $qty = $item['quantity'];
                    $product = wc_get_product($product_id);
                    $categories = $product->get_data() ? $product->get_data()['category_ids'] : array();
                    
                    // Check for condition matching
                    $meet = false;
                    $condition_index = 0;
                    if($meps_conditions) {
                        foreach($meps_conditions as $condition) {

                            if($meet || $condition_index === 0) {
                                // check by product
                                if (!$this->check_by_product($product_id, $condition['products'])) { // if true then skip
                                    $meet = false;
                                    break 2;
                                }

                                // check by cart quantity
                                switch($condition['type']) {
                                    case 'cart_quantity':
                                        if (!$this->check_by_cart_quantity($qty, $condition)) { // if true then skip
                                            $meet = false;
                                            break 2;
                                        }
                                        break;
                                    case 'amount_spent':
                                        if(!$this->check_by_cart_total($condition)) {
                                            $meet = false;
                                            break 2;
                                        }
                                        break;
                                    case 'amount_spent_ex_taxes':
                                        if (!$this->check_by_cart_total_ex_tax($condition)) {
                                            $meet = false;
                                            break 2;
                                        }
                                        break;
                                }


                                $meet = true;
                            }
                            
                            $condition_index++;
                        }
                    }
                    
                    if($meet) {
                        $condition_meet = true;
                        continue;
                    }

                }
            }

            return $condition_meet;
        }

        protected function check_by_product($product_id, $condition_products)
        {
            if(!$product_id && !$condition_products) return false;

            return in_array($product_id, $condition_products);
        }

        protected function check_by_cart_quantity($cart_qty, $condition)
        {
            if (!$cart_qty && !$condition) return false;

            if(($condition['operator'] === 'greater_equal') && ($cart_qty >= $condition['value'])) {
                return true;
            } elseif(($condition['operator'] === 'lesser_equal') && ($cart_qty <= $condition['value'])) {
                return true;
            } else {
                return false;
            }
        }

        protected function check_by_cart_total($condition)
        {
            if (!$condition) return false;

            $cart_total_amount = WC()->cart->total;

            if (($condition['operator'] === 'greater_equal') && ($cart_total_amount >= $condition['value'])) {
                return true;
            } elseif (($condition['operator'] === 'lesser_equal') && ($cart_total_amount <= $condition['value'])) {
                return true;
            } else {
                return false;
            }
        }

        protected function check_by_cart_total_ex_tax($condition)
        {
            if (!$condition) return false;

            $tax_amount = WC()->cart->get_total_tax();
            $cart_total_amount = WC()->cart->total;
            $cart_total_amount_ex_tax = $cart_total_amount - $tax_amount;

            if (($condition['operator'] === 'greater_equal') && ($cart_total_amount_ex_tax >= $condition['value'])) {
                return true;
            } elseif (($condition['operator'] === 'lesser_equal') && ($cart_total_amount_ex_tax <= $condition['value'])) {
                return true;
            } else {
                return false;
            }
        }

    }
}