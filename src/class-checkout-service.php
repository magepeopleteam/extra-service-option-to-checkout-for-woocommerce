<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

if (!class_exists('Meps_Checkout_Service')) {
    class Meps_Checkout_Service extends MEPS
    {

        public $helper;

        public function __construct()
        {
            $this->helper = new Meps_Helper;
            add_action('woocommerce_review_order_before_payment', array($this, 'before_order_summary'));
            add_action('woocommerce_proceed_to_checkout', array($this, 'before_order_summary'));
            add_action('woocommerce_review_order_before_order_total', [$this, 'meps_extra_service_before_total']);
            add_action('woocommerce_cart_totals_before_order_total', [$this, 'meps_extra_service_before_total']);
            add_filter('woocommerce_calculated_total', array($this, 'adjust_cart_total'), 100, 1);
            add_filter('woocommerce_before_checkout_process', array($this, 'save_extra_service_data'));
            add_filter('woocommerce_checkout_update_order_meta', array($this, 'save_to_order_meta'));

            add_filter('woocommerce_get_order_item_totals', array($this, 'add_service_row_in_order_table'), 10, 2);

            add_action('wp_ajax_meps_change_service_checkbox', array($this, 'meps_change_service_checkbox'));
        }

        public function save_extra_service_data()
        {
            wc()->session->set('meps_service_session', $_POST['meps_session']);
        }

        public function save_to_order_meta($order_id)
        {
            // $order = wc_get_order($order_id);
            if (wc()->session->get('meps_service_session')) {
                update_post_meta(
                    $order_id,
                    '_meps_services',
                    serialize(wc()->session->get('meps_service_session'))
                );

                wc()->session->set('meps_service_session', '');
                wc()->session->set('meps_services', '');
            }
        }

        public function before_order_summary()
        {
            $meps_fields = get_option('meps_fields', array());
            // echo '<pre>'; print_r($meps_fields); die;
            $meps_form_builder = new Meps_Form_Builder;
            $meps_service_session_arr = wc()->session->get('meps_services') ?: array();
            $has_service_in_session = $meps_service_session_arr ? true : false;
            // wc()->session->set('meps_services', '');
            // if(wc()->session->get('meps_services')) {
            //     echo '<pre>'; print_r(wc()->session->get('meps_services'));
            // }

?>
            <div class="meps-extra-service-container" style="width: 100%">
                <h3>Extra services</h3>
                <div class="meps-extra-service-content">

                    <?php if ($meps_fields) :
                        $i = 0;
                        foreach ($meps_fields['service'] as $field) :

                            if (!isset($field['active'])) continue;

                            if (!$this->helper->meps_cart_meet_the_service_condition($field['condition'])) continue; ?>

                            <div class="meps-service-item">
                                <div class="meps-service-item-heading">
                                    <div class="left">
                                        <input type="hidden" name="meps_session[service][<?php echo $i ?>][title]" value="<?php echo $field['title'] ?>">
                                        <input type="hidden" name="meps_session[service][<?php echo $i ?>][price]" value="<?php echo $field['price'] ?>">
                                        <input type="hidden" name="meps_session[service][<?php echo $i ?>][id]" value="<?php echo $field['id'] ?>">
                                        <p>
                                            <input type="checkbox" name="" <?php echo $meps_service_session_arr ?( in_array($field, $meps_service_session_arr) ? 'checked' : '') : '' ?> data-meps-service-id="<?php echo $field['id'] ?>"> <label for=""><?php echo $field['title'] ?></label>
                                            <span class="meps-service-price" data-meps-service-price="<?php echo $field['price'] ?>">Add +<?php echo wc_price($field['price']) ?></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="meps-service-item-content <?php echo $has_service_in_session ? (in_array($field, $meps_service_session_arr) ? 'meps-show' : '') : '' ?>">
                                    <?php if ($field['item']) :
                                        $j = 0;
                                        foreach ($field['item'] as $item) :
                                            $indexes = array(
                                                'service_id' => $field['id'],
                                                'service_index' => $i,
                                                'item_index' => $j,
                                            );
                                    ?>
                                            <div class="meps-service-item-field">
                                                <label for=""><?php echo $item['label'] ?></label>
                                                <?php $meps_form_builder->field($item, $indexes); ?>
                                            </div>
                                    <?php $j++;
                                        endforeach;
                                    endif; ?>

                                </div>
                            </div>
                        <?php $i++;
                        endforeach; ?>

                    <?php endif; ?>

                </div>
            </div>

        <?php
        }

        public function meps_extra_service_before_total()
        {
            $meps_service_session_arr = wc()->session->get('meps_services') ?: array();
            if (!$meps_service_session_arr) return;
            $service_price = 0;
            if ($meps_service_session_arr) {
                foreach ($meps_service_session_arr as $service) {
                    $service_price += $service['price'];
                }
            }
        ?>
            <tr class="order-topay">
                <th>Extra service</th>
                <td><?php echo wc_price($service_price) ?></td>
            </tr>
            <?php
        }

        public function adjust_cart_total($total)
        {
            $meps_service_session_arr = wc()->session->get('meps_services') ?: array();
            $service_price = 0;
            if ($meps_service_session_arr) {
                foreach ($meps_service_session_arr as $service) {
                    $service_price += $service['price'];
                }
            }
            return $total + $service_price;
        }

        public function meps_change_service_checkbox()
        {
            $isChecked = $_POST['isChecked'];
            $meps_id = isset($_POST['meps_id']) ? $_POST['meps_id'] : false;
            $meps_fields = get_option('meps_fields', array());

            $current_service = array();
            if ($meps_fields) {
                foreach ($meps_fields['service'] as $field) {
                    if ($meps_id == $field['id']) {
                        $current_service = $field;
                        break;
                    }
                }
            }

            // $meps_service_session_arr = 

            $prev_meps_service = wc()->session->get('meps_services') ?: array();
            $key = array_search($meps_id, array_column($prev_meps_service, 'id'));
            if ($isChecked == 'true') {
                if (!$key) {
                    array_push($prev_meps_service, $current_service);
                }
            } else {
                $key = array_search($meps_id, array_column($prev_meps_service, 'id'));
                unset($prev_meps_service[$key]);
            }

            $prev_meps_service = array_values($prev_meps_service);

            wc()->session->set('meps_services', $prev_meps_service);

            exit();
        }

        public function add_service_row_in_order_table($total_rows, $myorder_obj)
        {
            $order_total = $total_rows['order_total'];
            unset($total_rows['order_total']);

            $order_id = $myorder_obj->get_id();
            $order_extra_service = get_post_meta($order_id, '_meps_services', true) ? maybe_unserialize(get_post_meta($order_id, '_meps_services', true)) : array();

            $html = '';
            $extra_service_price = 0;
            if ($order_extra_service['service']) :
                foreach ($order_extra_service['service'] as $service) :

                    $extra_service_price += $service['price'];

                    $html .= '<ul style="margin:0;padding:0; list-style-type:none">';
                    $html .= '<li><h3>'.$service['title'].'</h3></li>';

                    if ($service['item']) :
                        foreach ($service['item'] as $item) :
                                $html .= '<li style="padding-left: 25px"><span class="th__title">Field:</span>'.$item.'</li>';
                        endforeach;
                    endif;

                    $html .= '</ul>';

                endforeach;
            endif;

            $total_rows['extra_service_price'] = array(
                'label' => __('Extra service price:', 'woocommerce'),
                'value'   => wc_price($extra_service_price)
            );

            $total_rows['order_total'] = $order_total;

            $total_rows['extra_service_value'] = array(
                'label' => __('Extra service value:', 'woocommerce'),
                'value'   => $html
            );

            return $total_rows;
        }
    }

    new Meps_Checkout_Service();
}
