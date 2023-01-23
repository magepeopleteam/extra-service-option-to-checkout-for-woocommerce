<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


if (!class_exists('Meps_Admin_Setting')) {
    class Meps_Admin_Setting extends MEPS
    {
        protected $parent_menu_title;
        protected $parent_slug;
        protected $helper;
        protected $meps;

        public function __construct()
        {

            $this->parent_menu_title = 'Extra services';
            $this->parent_slug = 'mage-extra-services';
            // $this->helper = new Meps_Helper();

            add_action('admin_menu', array($this, 'add_admin_menu'));
        }

        public function add_admin_menu()
        {
            add_menu_page($this->parent_menu_title, $this->parent_menu_title, 'manage_options', $this->parent_slug, array($this, 'menu_content'), MEPS_PLUGIN_URL . 'assets/img/extra.png', 50);

            // Submenu
            add_submenu_page($this->parent_slug, __('Options', MEPS_TEXTDOMAIN), __('Options', MEPS_TEXTDOMAIN), 'manage_options', $this->parent_slug . '_option', array($this, 'menu_content'));
        }

        public function menu_content()
        {
            $id = 1;
            if (isset($_POST['meps_setting_save'])) {
                // echo '<pre>';
                // print_r($_POST);
                // die;
                // $meps = array();
                if (isset($_POST['meps']['service'])) {
                    foreach ($_POST['meps']['service'] as $key => $service) {
                        // if(!isset($service['active'])) {
                        //     $service['active'] = 'off';
                        // }
                        // $meps[] = $service;
                        $service['id'] = $id;
                        $_POST['meps']['service'][$key] = $service;

                        $id++;
                    }
                }

                update_option('meps_fields', $_POST['meps']);
            }
?>
            <div class="meps-admin-setting-page">
                <h2>WooCommerce Extra Product Services</h2>
                <div class="meps-admin-setting-content">
                    <form id="meps_setting_form" action="" method="POST">
                        <?php $this->field_content(); ?>
                        <?php //$this->billing_field(); 
                        ?>
                        <?php //$this->shipping_field(); 
                        ?>

                        <input type="submit" name="meps_setting_save" value="<?php _e('Save Setting', 'advanced-partial-payment-or-deposit-for-woocommerce'); ?>">
                    </form>
                </div>
            </div>

        <?php
        }

        public function field_content()
        {
            $meps_fields = get_option('meps_fields', array());
            // $meps_fields = array();
            $woo_products = wc_get_products(array('orderby' => 'name'));
            // echo '<pre>'; print_r($products->get_title()); die;

            // echo '<pre>';
            // print_r($meps_fields);
        ?>
            <div class="meps-section">
                <div class="meps-section-heading">
                    <div class="left">
                        <h3>Order Summary</h3>
                    </div>
                    <div class="right"><button class="meps-section-btn btn-no-style"><i class="fa-solid fa-chevron-down"></i></button></div>
                </div>

                <!-- Form builder setting -->
                <div class="meps-section-content">
                    <table class="meps-table meps-form-builder">
                        <thead>
                            <tr style="border:1px solid #efefef;">
                                <th class="meps-draggable"></th>
                                <th class="meps-check"><input type="checkbox" name="" id=""></th>
                                <th><?php _e('Service', 'extra-product-and-service'); ?></th>
                                <th class="meps-add-new-btn" style="width: 12%"><i class="fa-solid fa-plus"></i> <?php _e('Add new service', MEPS_TEXTDOMAIN) ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- From DB -->
                            <?php if ($meps_fields) :
                                $i = 0;
                                foreach ($meps_fields['service'] as $field) : ?>
                                    <tr class="meps-form-builder-row" data-index="<?php echo $i; ?>">
                                        <td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
                                        <td class="meps-td-checkbox"><input type="checkbox" <?php echo isset($field['active']) ? 'checked' : '' ?> name="meps[service][<?php echo $i; ?>][active]" id="" class="meps-service-active-checkbox"></td>
                                        <td style="text-align:left">
                                            <div class="meps-form-builder-item-container">
                                                <div class="meps-field-header">
                                                    <input class="meps-service-title" type="text" name="meps[service][<?php echo $i; ?>][title]" value="<?php echo $field['title'] ?>" placeholder=<?php _e('Service title', 'extra-product-and-service') ?>>
                                                    <input class="meps-service-price" type="text" name="meps[service][<?php echo $i; ?>][price]" value="<?php echo $field['price'] ?>" placeholder="<?php _e('Service price', 'extra-product-and-service') ?>">
                                                </div>
                                                <div class="meps-form-builder-inner-container"> <!-- Field container -->
                                                    <div class="meps-form-builder-2nd-level-container">
                                                        <h4>Field</h4>
                                                        <div class="meps-form-builder-2nd-level-sub-container">
                                                            <?php if ($field['item']) :
                                                                $j = 0;
                                                                foreach ($field['item'] as $item) : ?>

                                                                    <div class="meps-form-builder-2nd-level-inner-container">
                                                                        <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                                        <div class="meps-form-builder-2nd-level-inner-top">
                                                                            <p><label for=""><?php _e('Label', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][item][<?php echo $j; ?>][label]" value="<?php echo $item['label'] ?>" class="meps-service-label"></p>
                                                                            <p><label for=""><?php _e('Placeholder', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][item][<?php echo $j; ?>][placeholder]" value="<?php echo $item['placeholder']; ?>" class="meps-service-placeholder"></p>
                                                                            <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                                <select name="meps[service][<?php echo $i; ?>][item][<?php echo $j; ?>][field_type]" class="meps-service-field-type">
                                                                                    <option value="text" <?php echo $item['field_type'] === 'text' ? 'selected' : ''; ?>><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                                                    <option value="number" <?php echo $item['field_type'] === 'number' ? 'selected' : ''; ?>><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                                                    <option value="select" <?php echo $item['field_type'] === 'select' ? 'selected' : ''; ?>><?php _e('Select', MEPS_TEXTDOMAIN) ?></option>
                                                                                </select>
                                                                            </p>
                                                                            <p><label for=""><?php _e('Required', MEPS_TEXTDOMAIN) ?></label>
                                                                                <select name="meps[service][<?php echo $i; ?>][item][<?php echo $j; ?>][required]" class="meps-service-required">
                                                                                    <option value="no" <?php echo $item['required'] === 'no' ? 'selected' : ''; ?>><?php _e('No', MEPS_TEXTDOMAIN) ?></option>
                                                                                    <option value="yes" <?php echo $item['required'] === 'yes' ? 'selected' : ''; ?>><?php _e('Yes', MEPS_TEXTDOMAIN) ?></option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                        <div class="meps-field-value-container <?php echo ($item['field_type'] === 'select' ? 'meps-show' : '') ?>">
                                                                            <label for="">Values (for select, radio)</label>
                                                                            <input type="text" name="meps[service][<?php echo $i; ?>][item][<?php echo $j; ?>][field_values]" value="<?php echo $item['field_values'] ?>" class="meps-service-field-values" placeholder="Separate multiple values using a comma">
                                                                        </div>
                                                                    </div>

                                                            <?php $j++;
                                                                endforeach;
                                                            endif; ?>
                                                        </div>
                                                        <!-- Db item Blueprint -->
                                                        <div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
                                                            <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                            <div class="meps-form-builder-2nd-level-inner-top">
                                                                <p><label for=""><?php _e('Label', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-label"></p>
                                                                <p><label for=""><?php _e('Placeholder', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
                                                                <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                    <select name="" class="meps-service-field-type">
                                                                        <option value="text"><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                                        <option value="number"><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                                    </select>
                                                                </p>
                                                                <p><label for=""><?php _e('Required', MEPS_TEXTDOMAIN) ?></label>
                                                                    <select name="" class="meps-service-required">
                                                                        <option value="no"><?php _e('No', MEPS_TEXTDOMAIN) ?></option>
                                                                        <option value="yes"><?php _e('Yes', MEPS_TEXTDOMAIN) ?></option>
                                                                    </select>
                                                                </p>
                                                            </div>
                                                            <div class="meps-field-value-container">
                                                                <label for="">Values (for select, radio)</label>
                                                                <input type="text" name="" class="meps-service-field-values" placeholder="Separate multiple values using a comma">
                                                            </div>
                                                        </div>
                                                        <button class="meps-add-field-btn">Add field</button>
                                                    </div>
                                                </div> <!-- Field container end -->


                                                <div class="meps-form-builder-inner-container"> <!-- Condition container -->
                                                    <div class="meps-form-builder-2nd-level-container meps-condition-container">
                                                        <h4>Condition</h4>
                                                        <div class="meps-form-builder-2nd-level-sub-container">
                                                            <?php if ($field['condition']) :
                                                                $j = 0;
                                                                foreach ($field['condition'] as $condition) : ?>

                                                                    <div class="meps-form-builder-2nd-level-inner-container">
                                                                        <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                                        <div class="meps-form-builder-2nd-level-inner-top">
                                                                            <p>
                                                                                <label for=""><?php _e('Products', MEPS_TEXTDOMAIN) ?></label>
                                                                                <select name="meps[service][<?php echo $i; ?>][condition][<?php echo $j; ?>][products][]" class="meps-service-condition-product" multiple>
                                                                                    <?php if ($woo_products) :
                                                                                        foreach ($woo_products as $wp) : ?>
                                                                                            <option value="<?php echo $wp->get_id(); ?>" <?php echo in_array($wp->get_id(), $condition['products']) ? 'selected' : ''; ?>><?php printf("(%s) - %s", $wp->get_sku(), $wp->get_title()); ?></option>
                                                                                    <?php endforeach;
                                                                                    endif; ?>
                                                                                </select>
                                                                            </p>
                                                                            <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                                <select name="meps[service][<?php echo $i; ?>][condition][<?php echo $j; ?>][type]" class="meps-service-condition-type">
                                                                                    <option value="cart_quantity" <?php echo $condition['type'] === 'cart' ? 'selected' : ''; ?>>Cart quantity</option>
                                                                                    <option value="amount_spent" <?php echo $condition['type'] === 'amount_spent' ? 'selected' : ''; ?>>Amount spent</option>
                                                                                    <option value="amount_spent_ex_taxes" <?php echo $condition['type'] === 'amount_spent_ex_taxes' ? 'selected' : ''; ?>>Amount spent excluding taxes</option>
                                                                                    <!-- <option value="stock" <?php //echo $condition['type'] === 'stock' ? 'selected' : ''; ?>>Stock quantity</option>
                                                                                    <option value="stock_status" <?php //echo $condition['type'] === 'stock_status' ? 'selected' : ''; ?>>Stock status (values: instock, outofstock)</option>
                                                                                    <option value="weight" <?php //echo $condition['type'] === 'weight' ? 'selected' : ''; ?>>Weight</option>
                                                                                    <option value="height" <?php //echo $condition['type'] === 'height' ? 'selected' : ''; ?>>Height</option>
                                                                                    <option value="length" <?php //echo $condition['type'] === 'length' ? 'selected' : ''; ?>>Lenght</option>
                                                                                    <option value="width" <?php //echo $condition['type'] === 'width' ? 'selected' : ''; ?>>Width</option>
                                                                                    <option value="volume" <?php //echo $condition['type'] === 'volume' ? 'selected' : ''; ?>>Volume</option> -->
                                                                                </select>
                                                                            </p>
                                                                            <p><label for=""><?php _e('Value', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][condition][<?php echo $j; ?>][value]" value="<?php echo $condition['value'] ?>" class="meps-service-condition-value"></p>
                                                                            <p><label for=""><?php _e('Operator', MEPS_TEXTDOMAIN) ?></label>
                                                                                <select name="meps[service][<?php echo $i; ?>][condition][<?php echo $j; ?>][operator]" class="meps-service-condition-operator">
                                                                                    <option value="greater_equal" <?php echo $condition['operator'] === 'greater_equal' ? 'selected' : ''; ?>>Greater or equal</option>
                                                                                    <option value="lesser_equal" <?php echo $condition['operator'] === 'lesser_equal' ? 'selected' : ''; ?>>Lesser or equal</option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                            <?php $j++;
                                                                endforeach;
                                                            endif; ?>
                                                        </div>
                                                        <!-- Db Blueprint -->
                                                        <div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
                                                            <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                            <div class="meps-form-builder-2nd-level-inner-top">
                                                                <p>
                                                                    <label for=""><?php _e('Products', MEPS_TEXTDOMAIN) ?></label>
                                                                    <select name="" class="meps-service-condition-product" multiple>
                                                                        <?php if ($woo_products) :
                                                                            foreach ($woo_products as $wp) : ?>
                                                                                <option value="<?php echo $wp->get_id(); ?>"><?php printf("(%s) - %s", $wp->get_sku(), $wp->get_title()); ?></option>
                                                                        <?php endforeach;
                                                                        endif; ?>
                                                                    </select>
                                                                </p>
                                                                <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                    <select name="" class="meps-service-condition-type">
                                                                        <option value="cart_quantity">Cart quantity</option>
                                                                        <option value="amount_spent">Amount spent</option>
                                                                        <option value="amount_spent_ex_taxes">Amount spent excluding taxes</option>
                                                                        <!-- <option value="stock">Stock quantity</option>
                                                                        <option value="stock_status">Stock status (values: instock, outofstock)</option>
                                                                        <option value="weight">Weight</option>
                                                                        <option value="height">Height</option>
                                                                        <option value="length">Lenght</option>
                                                                        <option value="width">Width</option>
                                                                        <option value="volume">Volume</option> -->
                                                                    </select>
                                                                </p>
                                                                <p><label for=""><?php _e('Value', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-condition-value"></p>
                                                                <p><label for=""><?php _e('Operator', MEPS_TEXTDOMAIN) ?></label>
                                                                    <select name="" class="meps-service-condition-operator">
                                                                        <option value="greater_equal">Greater or equal</option>
                                                                        <option value="lesser_equal">Lesser or equal</option>
                                                                    </select>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <button class="meps-add-field-btn">Add field</button>
                                                    </div>
                                                </div> <!-- Condition container end -->



                                            </div>
                                        </td>
                                        <td>
                                            <button class="meps-field-group-btn meps-service-action-btn" data-status="close"><i class="fa-solid fa-bars"></i></button>
                                            <button class="mep-service-remove meps-service-action-btn"><i class="fa-solid fa-circle-xmark"></i></button>
                                        </td>
                                    </tr>

                                <?php $i++;
                                endforeach; ?>

                            <?php endif; ?>
                            <!-- From DB END -->

                            <!-- Service blueprint -->
                            <tr class="meps-form-builder-row meps-form-field-blueprint">
                                <td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
                                <td class="meps-td-checkbox"><input type="checkbox" name="meps[service][][active]" id="" class="meps-service-active-checkbox"></td>
                                <td style="text-align:left">
                                    <div class="meps-form-builder-item-container">
                                        <div class="meps-field-header">
                                            <input class="meps-service-title" type="text" name="" placeholder="<?php _e('Service title', 'extra-product-and-service') ?>">
                                            <input class="meps-service-price" type="text" name="" placeholder="<?php _e('Service price', 'extra-product-and-service') ?>">
                                        </div>
                                        <div class="meps-form-builder-inner-container"> <!-- Field container -->
                                            <div class="meps-form-builder-2nd-level-container">
                                                <h4>Field</h4>
                                                <div class="meps-form-builder-2nd-level-sub-container">
                                                    <div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
                                                        <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                        <div class="meps-form-builder-2nd-level-inner-top">
                                                            <p><label for=""><?php _e('Label', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-label">
                                                            </p>
                                                            <p><label for=""><?php _e('Placeholder', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
                                                            <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="" class="meps-service-field-type">
                                                                    <option value="text"><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="number"><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="select"><?php _e('Select', MEPS_TEXTDOMAIN) ?></option>
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Required', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="" class="meps-service-required">
                                                                    <option value="no"><?php _e('No', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="yes"><?php _e('Yes', MEPS_TEXTDOMAIN) ?></option>
                                                                </select>
                                                            </p>
                                                        </div>
                                                        <div class="meps-field-value-container">
                                                            <label for="">Values (for select, radio)</label>
                                                            <input type="text" name="" class="meps-service-field-values" placeholder="Separate multiple values using a comma">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="meps-add-field-btn">Add field</button>
                                            </div>
                                        </div> <!-- Field container end -->

                                        <div class="meps-form-builder-inner-container">
                                            <div class="meps-form-builder-2nd-level-container meps-condition-container"> <!-- Condition container -->
                                                <h4>Condition</h4>
                                                <div class="meps-form-builder-2nd-level-sub-container">
                                                    <div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
                                                        <span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
                                                        <div class="meps-form-builder-2nd-level-inner-top">
                                                            <p>
                                                                <label for=""><?php _e('Products', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="" class="meps-service-condition-product" multiple>
                                                                    <?php if ($woo_products) :
                                                                        foreach ($woo_products as $wp) : ?>
                                                                            <option value="<?php echo $wp->get_id() ?>"><?php printf("(%s) - %s", $wp->get_sku(), $wp->get_title()); ?></option>
                                                                    <?php endforeach;
                                                                    endif; ?>
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="" class="meps-service-condition-type">
                                                                    <option value="cart_quantity">Cart quantity</option>
                                                                    <option value="amount_spent">Amount spent</option>
                                                                    <option value="amount_spent_ex_taxes">Amount spent excluding taxes</option>
                                                                    <!-- <option value="stock">Stock quantity</option>
                                                                    <option value="stock_status">Stock status (values: instock, outofstock)</option>
                                                                    <option value="weight">Weight</option>
                                                                    <option value="height">Height</option>
                                                                    <option value="length">Lenght</option>
                                                                    <option value="width">Width</option>
                                                                    <option value="volume">Volume</option> -->
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Value', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-condition-value"></p>
                                                            <p><label for=""><?php _e('Operator', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="" class="meps-service-condition-operator">
                                                                    <option value="greater_equal">Greater or equal</option>
                                                                    <option value="lesser_equal">Lesser or equal</option>
                                                                </select>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="meps-add-field-btn">Add condition</button>
                                            </div> <!-- Condition container end -->
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="meps-field-group-btn meps-service-action-btn" data-status="close"><i class="fa-solid fa-bars"></i></button>
                                    <button class="mep-service-remove meps-service-action-btn"><i class="fa-solid fa-circle-xmark"></i></button>
                                </td>
                            </tr>
                            <!-- Service blueprint End -->
                        </tbody>
                    </table>
                </div>
                <!-- Form end -->
            </div>
        <?php
        }

        public function billing_field()
        {
        ?>
            <div class="meps-section">
                <div class="meps-section-heading">
                    <div class="left">
                        <h3>Billing</h3>
                    </div>
                    <div class="right"><button class="meps-section-btn btn-no-style"><i class="fa-solid fa-chevron-down"></i></button></div>
                </div>

                <div class="meps-section-content">Billing field</div>
            </div>

        <?php
        }

        public function shipping_field()
        {
        ?>
            <div class="meps-section">
                <div class="meps-section-heading">
                    <div class="left">
                        <h3>Shipping</h3>
                    </div>
                    <div class="right"><button class="meps-section-btn btn-no-style"><i class="fa-solid fa-chevron-down"></i></button></div>
                </div>

                <!-- Form builder setting -->
                <div class="meps-section-content">
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

                            <!-- From DB -->
                            <?php if ($meps_fields) :
                                $i = 0;
                                foreach ($meps_fields['service'] as $field) : ?>
                                    <tr class="meps-form-builder-row">
                                        <td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
                                        <td class="meps-td-checkbox"><input type="checkbox" name="meps[service][<?php echo $i; ?>][active]" <?php echo $field['active'] === 'on' ? 'checked' : '' ?> id="" class="meps-service-active-checkbox"></td>
                                        <td>
                                            <div class="meps-form-builder-item-container">
                                                <div class="meps-field-header">
                                                    <h3><?php echo $field['label'] ?></h3>
                                                </div>
                                                <div class="meps-form-builder-inner-container">
                                                    <div class="meps-form-builder-container">
                                                        <div class="meps-left">
                                                            <p><label for=""><?php _e('Title', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][label]" value="<?php echo $field['label'] ?>" class="meps-service-label"></p>
                                                            <p><label for=""><?php _e('Placeholder', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][placeholder]" value="<?php echo $field['placeholder'] ?>" class="meps-service-placeholder"></p>
                                                            <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="meps[service][<?php echo $i; ?>][field_type]" class="meps-service-field-type">
                                                                    <option value="no_field" <?php echo $field['field_type'] === 'no_field' ? 'selected' : '' ?>><?php _e('No field', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="text" <?php echo $field['field_type'] === 'text' ? 'selected' : '' ?>><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                                    <option value="number" <?php echo $field['field_type'] === 'number' ? 'selected' : '' ?>><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Price', MEPS_TEXTDOMAIN) ?></label> <input type="number" name="meps[service][<?php echo $i; ?>][price]" value="<?php echo $field['price'] ?>" class="meps-service-price"></p>
                                                            <p><label for=""><?php _e('Description', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="meps[service][<?php echo $i; ?>][desc]" value="<?php echo $field['desc'] ?>" class="meps-service-desc"></p>
                                                        </div>
                                                        <div class="meps-right">
                                                            <p><label for=""><?php _e('Required', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="meps[service][<?php echo $i; ?>][is_required]" class="meps-service-required">
                                                                    <option value="no" <?php echo $field['is_required'] === 'no' ? 'selected' : '' ?>>No</option>
                                                                    <option value="yes" <?php echo $field['is_required'] === 'yes' ? 'selected' : '' ?>>Yes</option>
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Show in emails', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="meps[service][<?php echo $i; ?>][show_in_email]" class="meps-service-showinemail">
                                                                    <option value="no" <?php echo $field['show_in_email'] === 'no' ? 'selected' : '' ?>>No</option>
                                                                    <option value="yes" <?php echo $field['show_in_email'] === 'yes' ? 'selected' : '' ?>>Yes</option>
                                                                </select>
                                                            </p>
                                                            <p><label for=""><?php _e('Show in order details page', MEPS_TEXTDOMAIN) ?></label>
                                                                <select name="meps[service][<?php echo $i; ?>][show_in_detail]" class="meps-service-showindetail">
                                                                    <option value="no" <?php echo $field['show_in_detail'] === 'no' ? 'selected' : '' ?>>No</option>
                                                                    <option value="yes" <?php echo $field['show_in_detail'] === 'yes' ? 'selected' : '' ?>>Yes</option>
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
                                <?php $i++;
                                endforeach; ?>

                            <?php endif; ?>
                            <!-- From DB END -->

                            <!-- Service blueprint -->
                            <tr class="meps-form-builder-row meps-form-field-blueprint" style="display:block">
                                <td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
                                <td class="meps-td-checkbox"><input type="checkbox" name="meps[service][][active]" id="" class="meps-service-active-checkbox"></td>
                                <td>
                                    <div class="meps-form-builder-item-container">
                                        <div class="meps-field-header">
                                            <h3>Service Title</h3>
                                        </div>
                                        <div class="meps-form-builder-inner-container">
                                            <div class="meps-form-builder-container">
                                                <div class="meps-left">
                                                    <p><label for=""><?php _e('Title', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-label"></p>
                                                    <p><label for=""><?php _e('Placeholder', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
                                                    <p><label for=""><?php _e('Type', MEPS_TEXTDOMAIN) ?></label>
                                                        <select name="" class="meps-service-field-type">
                                                            <option value="no_field"><?php _e('No field', MEPS_TEXTDOMAIN) ?></option>
                                                            <option value="text"><?php _e('Text', MEPS_TEXTDOMAIN) ?></option>
                                                            <option value="number"><?php _e('Number', MEPS_TEXTDOMAIN) ?></option>
                                                        </select>
                                                    </p>
                                                    <p><label for=""><?php _e('Price', MEPS_TEXTDOMAIN) ?></label> <input type="number" name="" class="meps-service-price"></p>
                                                    <p><label for=""><?php _e('Description', MEPS_TEXTDOMAIN) ?></label> <input type="text" name="" class="meps-service-desc"></p>
                                                </div>
                                                <div class="meps-right">
                                                    <p><label for=""><?php _e('Required', MEPS_TEXTDOMAIN) ?></label>
                                                        <select name="" class="meps-service-required">
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </p>
                                                    <p><label for=""><?php _e('Show in emails', MEPS_TEXTDOMAIN) ?></label>
                                                        <select name="" class="meps-service-showinemail">
                                                            <option value="no">No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                    </p>
                                                    <p><label for=""><?php _e('Show in order details page', MEPS_TEXTDOMAIN) ?></label>
                                                        <select name="" class="meps-service-showindetail">
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
                            <!-- Service blueprint End -->
                        </tbody>
                    </table>
                </div>
                <!-- Form end -->
            </div>

<?php
        }
    } // Class end

    new Meps_Admin_Setting();
}
