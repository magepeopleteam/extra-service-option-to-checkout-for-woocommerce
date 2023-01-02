<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


if (!class_exists('Meps_Admin_Setting')) {
    class Meps_Admin_Setting extends MEPS
    {
        protected $menu_title;
        protected $helper;
        protected $meps;

        public function __construct()
        {

            $this->menu_title = __('Extra services', MEPS_TEXTDOMAIN);
            // $this->helper = new Meps_Helper();

            add_action('admin_menu', array($this, 'add_admin_menu'));
        }

        public function add_admin_menu()
        {
            add_menu_page($this->menu_title, $this->menu_title, 'manage_options', 'mage-extra-services', array($this, 'menu_content'), 'dashicons-plus-alt', 50);

        }

        public function menu_content()
        {
?>

            <div class="meps-admin-setting-page">
                <div class="meps-admin-page-header">
                    <h3><?php echo parent::meps_get_plugin_data('Name'); ?><small><?php echo parent::meps_get_plugin_data('Version'); ?></small></h3>
                </div>
                <div class="meps-admin-page-content">
                    <?php $this->setting_content(); ?>
                </div>
            </div>

        <?php
        }

        public function setting_content()
        {
        ?>
            <div class="meps-tab-container">
                <div class="meps-tab-menu">
                    <ul class="meps-ul">
                        <li><a href="#" class="meps-tab-a active-a" data-id="general"><i class="fas fa-home"></i> <?php _e('General', 'advanced-partial-payment-or-deposit-for-woocommerce') ?></a></li>
                        <li><a href="#" class="meps-tab-a" data-id="form-builder"><i class="fa-solid fa-cubes"></i> <?php _e('Form builder', 'advanced-partial-payment-or-deposit-for-woocommerce') ?></a></li>
                    </ul>
                </div>

                <!-- Form start -->
                <form id="meps_setting_form" action="" method="POST">

                    <div class="meps-tab" data-id="general">
                        <div class="meps-tab-content">
                            <!-- General setting -->
                            General
                        </div>
                    </div>
                    <div class="meps-tab meps-tab-active" data-id="form-builder">
                        <div class="meps-tab-content">
                            <!-- Form builder setting -->
                            <table class="meps-table meps-form-builder">
                                <thead>
                                    <tr style="border:1px solid #efefef;">
                                        <th class="meps-draggable"></th>
                                        <th class="meps-check"><input type="checkbox" name="" id=""></th>
                                        <th></th>
                                        <th class="meps-add-new-btn" style="width: 12%"><i class="fa-solid fa-plus"></i> <?php _e('Add new service', MEPS_TEXTDOMAIN) ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- Service bluprint -->
                                    <tr class="meps-form-builder-row meps-form-field-blueprint">
                                        <td class="meps-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
                                        <td><input type="checkbox" name="" id=""></td>
                                        <td>
                                            <div class="meps-form-builder-item-container">
                                                <div class="meps-field-header">
                                                    <h3>Service Title</h3>
                                                </div>
                                                <div class="meps-form-builder-inner-container">
                                                    <div class="meps-form-builder-container">
                                                        <div class="meps-left">
                                                            <p><?php _e('Title', MEPS_TEXTDOMAIN) ?> <input type="text" name="" id=""></p>
                                                            <p><?php _e('Type', MEPS_TEXTDOMAIN) ?>
                                                                <select name="" id="">
                                                                    <option value="text"><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="number"><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                                </select>
                                                            </p>
                                                            <p><?php _e('Price', MEPS_TEXTDOMAIN) ?> <input type="number" name="" id=""></p>
                                                            <p><?php _e('Description', MEPS_TEXTDOMAIN) ?> <input type="text" name="" id=""></p>

                                                        </div>
                                                        <div class="meps-right">
                                                            <p>Required
                                                                <select name="" id="">
                                                                    <option value="no">No</option>
                                                                    <option value="yes">Yes</option>
                                                                </select>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="meps-field-group-btn" data-status="close"><i class="fa-solid fa-bars"></i></button>
                                            <button class="mep-service-remove"><i class="fa-solid fa-circle-xmark"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Service bluprint End -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <input type="submit" name="meps_setting_save" value="<?php //_e('Save Setting', 'advanced-partial-payment-or-deposit-for-woocommerce'); 
                                                                                ?>"> -->
                </form>
                <!-- Form end -->
            </div>
<?php
        }
    }

    new Meps_Admin_Setting();
}
