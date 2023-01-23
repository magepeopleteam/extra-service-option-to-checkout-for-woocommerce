<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


if (!class_exists('Meps_Admin_Order_Detail')) {
    class Meps_Admin_Order_Detail
    {
        public function __construct()
        {
            add_action('add_meta_boxes', array($this, 'meps_order_meta_box'));
        }

        function meps_order_meta_box()
        {
            add_meta_box('meps_order_detail', 'Extra Service', array($this, 'show_in_admin_order_detail'), 'shop_order', 'advanced', 'high');
        }

        public function show_in_admin_order_detail($post)
        {
            $order_extra_service = get_post_meta($post->ID, '_meps_services', true) ? maybe_unserialize(get_post_meta($post->ID, '_meps_services', true)) : array();

            // echo '<pre>'; print_r($order_extra_service);

?>
            <style type="text/css">
                .th__title {
                    text-transform: capitalize;
                    display: inline-block;
                    min-width: 140px;
                    font-weight: 700
                }

                ul.meps_container {
                    border: 1px solid #ddd;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-radius: 3px;
                }

                ul.meps_container li {
                    border-bottom: 1px dashed #ddd;
                    padding: 5px 0 10px;
                    color: #080808;
                }

                ul.meps_container li h3 {
                    padding: 0;
                    margin: 0;
                    color: #555;
                }
            </style>

            <div class="meps-container">
                <?php if ($order_extra_service['service']) :
                    foreach ($order_extra_service['service'] as $service) : ?>

                        <ul>
                            <li>
                                <h3><?php echo $service['title'] ?></h3>
                            </li>

                            <?php if ($service['item']) :
                                foreach ($service['item'] as $item) : ?>
                                    <li><span class="th__title">Field:</span><?php echo $item; ?></li>
                            <?php endforeach;
                            endif; ?>
                        </ul>

                <?php endforeach;
                endif; ?>
            </div>

<?php
        }
    }

    new Meps_Admin_Order_Detail();
}
