<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.


if ( ! class_exists( 'Meps_Admin_Setting' ) ) {
	/** Extra Admin setting class */
	class Meps_Admin_Setting extends MEPS {

		/**
		 * Menu title
		 *
		 * @var string
		 */
		protected $parent_menu_title;
		/**
		 * Parent Slug
		 *
		 * @var string
		 */
		protected $parent_slug;
		/**
		 * Meps
		 *
		 * @var string
		 */
		protected $meps;

		/** Construction function */
		public function __construct() {
			$this->parent_menu_title = 'Extra services';
			$this->parent_slug       = 'mage-extra-services';

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		}

		/** Add Extra Service Menu function */
		public function add_admin_menu() {
			add_menu_page( $this->parent_menu_title, $this->parent_menu_title, 'manage_options', $this->parent_slug, array( $this, 'menu_content' ), MEPS_PLUGIN_URL . 'assets/img/extra.png', 50 );

			// Submenu.
			add_submenu_page( $this->parent_slug, __( 'Options', 'extra-product-and-service' ), __( 'Options', 'extra-product-and-service' ), 'manage_options', $this->parent_slug . '_option', array( $this, 'menu_content' ) );
		}

		/** Menu Content function */
		public function menu_content() {
			$id = 1;
			if ( isset( $_POST['meps_setting_save'] ) ) {
				// $meps = array();
				if ( isset( $_POST['meps']['service'] ) ) {
					foreach ( $_POST['meps']['service'] as $key => $service ) {
						$service['id']                    = $id;
						$_POST['meps']['service'][ $key ] = $service;

						$id++;
					}
				}

				update_option( 'meps_fields', $_POST['meps'] );
			}
			?>
			<div class="meps-admin-setting-page">
				<h2><?php esc_html_e( 'WooCommerce Extra Product Services', 'extra-product-and-service' ); ?></h2>
				<div class="meps-admin-setting-content">
					<form id="meps_setting_form" action="" method="POST">
						<?php $this->field_content(); ?>
						<?php
						// $this->billing_field();
						?>
						<?php
						// $this->shipping_field();.
						?>

						<input type="submit" name="meps_setting_save" value="<?php esc_html_e( 'Save Setting', 'extra-product-and-service' ); ?>">
					</form>
				</div>
			</div>

			<?php
		}

		/**
		 * Field content */
		public function field_content() {
			$meps_fields = get_option( 'meps_fields', array() );
			// $meps_fields = array();
			$woo_products = wc_get_products( array( 'orderby' => 'name' ) );
			?>
			<div class="meps-section">
				<div class="meps-section-heading">
					<div class="left">
						<h3><?php esc_html_e( 'Order Summary', 'extra-product-and-service' ); ?></h3>
					</div>
					<div class="right"><button class="meps-section-btn btn-no-style"><i class="fa-solid fa-chevron-down"></i></button></div>
				</div>

				<!-- Form builder setting -->
				<div class="meps-section-content">
					<table class="meps-table meps-form-builder">
						<thead>
							<tr style="border:1px solid #efefef;">
								<th class="meps-draggable"></th>
								<th class="meps-check">
									<!-- <input type="checkbox" name="" id=""> -->
								</th>
								<th><?php esc_html_e( 'Service', 'extra-product-and-service' ); ?></th>
								<th class="meps-add-new-btn" style="width: 12%"><i class="fa-solid fa-plus"></i> <?php esc_html_e( 'Add new service', 'extra-product-and-service' ); ?></th>
							</tr>
						</thead>
						<tbody>

							<!-- From DB -->
							<?php
							if ( $meps_fields ) :
								$i = 0;
								foreach ( $meps_fields['service'] as $field ) :
									?>
									<tr class="meps-form-builder-row" data-index="<?php echo esc_attr( $i ); ?>">
										<td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
										<td class="meps-td-checkbox"><input type="checkbox" <?php echo isset( $field['active'] ) ? 'checked' : ''; ?> name="meps[service][<?php echo esc_attr( $i ); ?>][active]" class="meps-service-active-checkbox"></td>
										<td style="text-align:left">
											<div class="meps-form-builder-item-container">
												<div class="meps-field-header">
													<input class="meps-service-title" type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $field['title'] ); ?>" placeholder=<?php esc_html_e( 'Service title', 'extra-product-and-service' ); ?>>
													<input class="meps-service-price" type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][price]" value="<?php echo esc_attr( $field['price'] ); ?>" placeholder="<?php esc_html_e( 'Service price', 'extra-product-and-service' ); ?>">
												</div>
												<div class="meps-form-builder-inner-container"> <!-- Field container -->
													<div class="meps-form-builder-2nd-level-container">
														<h4><?php esc_html_e( 'Field', 'extra-product-and-service' ); ?></h4>
														<div class="meps-form-builder-2nd-level-sub-container">
															<?php
															if ( $field['item'] ) :
																$j = 0;
																foreach ( $field['item'] as $item ) :
																	?>
																	<div class="meps-form-builder-2nd-level-inner-container">
																		<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
																		<div class="meps-form-builder-2nd-level-inner-top">
																			<p><label for=""><?php esc_html_e( 'Label', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][item][<?php echo esc_attr( $j ); ?>][label]" value="<?php echo esc_html( $item['label'] ); ?>" class="meps-service-label"></p>
																			<p><label for=""><?php esc_html_e( 'Placeholder', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][item][<?php echo esc_attr( $j ); ?>][placeholder]" value="<?php echo esc_attr( $item['placeholder'] ); ?>" class="meps-service-placeholder"></p>
																			<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																				<select name="meps[service][<?php echo esc_attr( $i ); ?>][item][<?php echo esc_attr( $j ); ?>][field_type]" class="meps-service-field-type">
																					<option value="text" <?php echo 'text' === $item['field_type'] ? 'selected' : ''; ?>><?php esc_html_e( 'Text', 'extra-product-and-service' ); ?></option>
																					<option value="number" <?php echo 'number' === $item['field_type'] ? 'selected' : ''; ?>><?php esc_html_e( 'Number', 'extra-product-and-service' ); ?></option>
																					<option value="select" <?php echo ( 'select' === $item['field_type'] ? 'selected' : '' ); ?>><?php esc_html_e( 'Select', 'extra-product-and-service' ); ?></option>
																				</select>
																			</p>
																			<p><label for=""><?php esc_html_e( 'Required', 'extra-product-and-service' ); ?></label>
																				<select name="meps[service][<?php echo esc_attr( $i ); ?>][item][<?php echo esc_attr( $j ); ?>][required]" class="meps-service-required">
																					<option value="no" <?php echo ( 'no' === $item['required'] ? 'selected' : '' ); ?>><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
																					<option value="yes" <?php echo ( 'yes' === $item['required'] ? 'selected' : '' ); ?>><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
																				</select>
																			</p>
																		</div>
																		<div class="meps-field-value-container <?php echo ( 'select' === $item['field_type'] ? 'meps-show' : '' ); ?>">
																			<label for=""><?php esc_html_e( 'Values (for select, radio)', 'extra-product-and-service' ); ?></label>
																			<input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][item][<?php echo esc_attr( $j ); ?>][field_values]" value="<?php echo esc_attr( $item['field_values'] ); ?>" class="meps-service-field-values" placeholder=<?php esc_html_e( 'Separate multiple values using a comma', 'extra-product-and-service' ); ?>>
																		</div>
																	</div>

																	<?php
																	$j++;
																endforeach;
															endif;
															?>
														</div>
														<!-- Db item Blueprint -->
														<div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
															<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
															<div class="meps-form-builder-2nd-level-inner-top">
																<p><label for=""><?php esc_html_e( 'Label', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-label"></p>
																<p><label for=""><?php esc_html_e( 'Placeholder', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
																<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																	<select name="" class="meps-service-field-type">
																		<option value="text"><?php esc_html_e( 'Text', 'extra-product-and-service' ); ?></option>
																		<option value="number"><?php esc_html_e( 'Number', 'extra-product-and-service' ); ?></option>
																	</select>
																</p>
																<p><label for=""><?php esc_html_e( 'Required', 'extra-product-and-service' ); ?></label>
																	<select name="" class="meps-service-required">
																		<option value="no"><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
																		<option value="yes"><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
																	</select>
																</p>
															</div>
															<div class="meps-field-value-container">
																<label for=""><?php esc_html_e( 'Values (for select, radio)', 'extra-product-and-service' ); ?></label>
																<input type="text" name="" class="meps-service-field-values" placeholder="<?php esc_html_e( 'Separate multiple values using a comma', 'extra-product-and-service' ); ?>">
															</div>
														</div>
														<button class="meps-add-field-btn"><?php esc_html_e( 'Add field', 'extra-product-and-service' ); ?></button>
													</div>
												</div> <!-- Field container end -->


												<div class="meps-form-builder-inner-container"> <!-- Condition container -->
													<div class="meps-form-builder-2nd-level-container meps-condition-container">
														<h4><?php esc_html_e( 'Condition', 'extra-product-and-service' ); ?></h4>
														<div class="meps-form-builder-2nd-level-sub-container">
															<?php
															if ( $field['condition'] ) :
																$j = 0;
																foreach ( $field['condition'] as $condition ) :
																	?>

																	<div class="meps-form-builder-2nd-level-inner-container">
																		<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
																		<div class="meps-form-builder-2nd-level-inner-top">
																			<p>
																				<label for=""><?php esc_html_e( 'Products', 'extra-product-and-service' ); ?></label>
																				<select name="meps[service][<?php echo esc_attr( $i ); ?>][condition][<?php echo esc_attr( $j ); ?>][products][]" class="meps-service-condition-product" multiple>
																					<?php
																					if ( $woo_products ) :
																						foreach ( $woo_products as $wp ) :
																							?>
																							<option value="<?php echo $wp->get_id(); ?>" <?php echo in_array( $wp->get_id(), $condition['products'] ) ? 'selected' : ''; ?>><?php printf( '(%s) - %s', $wp->get_sku(), $wp->get_title() ); ?></option>
																							<?php
																					endforeach;
																					endif;
																					?>
																				</select>
																			</p>
																			<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																				<select name="meps[service][<?php echo esc_attr( $i ); ?>][condition][<?php echo esc_attr( $j ); ?>][type]" class="meps-service-condition-type">
																					<option value="cart_quantity" <?php echo $condition['type'] === 'cart' ? 'selected' : ''; ?>><?php esc_html_e( 'Cart quantity', 'extra-product-and-service' ); ?></option>
																					<option value="cart_total" <?php echo $condition['type'] === 'cart_total' ? 'selected' : ''; ?>><?php esc_html_e( 'Cart total', 'extra-product-and-service' ); ?></option>
																					<option value="cart_total_ex_taxes" <?php echo $condition['type'] === 'cart_total_ex_taxes' ? 'selected' : ''; ?>><?php esc_html_e( 'Cart total excluding taxes', 'extra-product-and-service' ); ?></option>
																				</select>
																			</p>
																			<p><label for=""><?php esc_html_e( 'Value', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][condition][<?php echo $j; ?>][value]" value="<?php echo esc_attr( $condition['value'] ); ?>" class="meps-service-condition-value"></p>
																			<p><label for=""><?php esc_html_e( 'Operator', 'extra-product-and-service' ); ?></label>
																				<select name="meps[service][<?php echo esc_attr( $i ); ?>][condition][<?php echo esc_attr( $j ); ?>][operator]" class="meps-service-condition-operator">
																					<option value="greater_equal" <?php echo $condition['operator'] === 'greater_equal' ? 'selected' : ''; ?>><?php esc_html_e( 'Greater or equal', 'extra-product-and-service' ); ?></option>
																					<option value="lesser_equal" <?php echo $condition['operator'] === 'lesser_equal' ? 'selected' : ''; ?>><?php esc_html_e( 'Lesser or equal', 'extra-product-and-service' ); ?></option>
																				</select>
																			</p>
																		</div>
																	</div>

																	<?php
																	$j++;
																endforeach;
															endif;
															?>
														</div>
														<!-- Db Blueprint -->
														<div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
															<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
															<div class="meps-form-builder-2nd-level-inner-top">
																<p>
																	<label for=""><?php esc_html_e( 'Products', 'extra-product-and-service' ); ?></label>
																	<select name="" class="meps-service-condition-product" multiple>
																		<?php
																		if ( $woo_products ) :
																			foreach ( $woo_products as $wp ) :
																				?>
																				<option value="<?php echo $wp->get_id(); ?>"><?php printf( '(%s) - %s', $wp->get_sku(), $wp->get_title() ); ?></option>
																				<?php
																		endforeach;
																		endif;
																		?>
																	</select>
																</p>
																<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																	<select name="" class="meps-service-condition-type">
																		<option value="cart_quantity"><?php esc_html_e( 'Cart quantity', 'extra-product-and-service' ); ?></option>
																		<option value="cart_total"><?php esc_html_e( 'Cart total', 'extra-product-and-service' ); ?></option>
																		<option value="cart_total_ex_taxes"><?php esc_html_e( 'Cart total excluding taxes', 'extra-product-and-service' ); ?></option>
																	</select>
																</p>
																<p><label for=""><?php esc_html_e( 'Value', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-condition-value"></p>
																<p><label for=""><?php esc_html_e( 'Operator', 'extra-product-and-service' ); ?></label>
																	<select name="" class="meps-service-condition-operator">
																		<option value="greater_equal"><?php esc_html_e( 'Greater or equal', 'extra-product-and-service' ); ?></option>
																		<option value="lesser_equal"><?php esc_html_e( 'Lesser or equal', 'extra-product-and-service' ); ?></option>
																	</select>
																</p>
															</div>
														</div>
														<button class="meps-add-field-btn"><?php esc_html_e( 'Add field', 'extra-product-and-service' ); ?></button>
													</div>
												</div> <!-- Condition container end -->
											</div>
										</td>
										<td>
											<button class="meps-field-group-btn meps-service-action-btn" data-status="close"><i class="fa-solid fa-bars"></i></button>
											<button class="mep-service-remove meps-service-action-btn"><i class="fa-solid fa-circle-xmark"></i></button>
										</td>
									</tr>

									<?php
									$i++;
								endforeach;
								?>

							<?php endif; ?>
							<!-- From DB END -->

							<!-- Service blueprint -->
							<tr class="meps-form-builder-row meps-form-field-blueprint">
								<td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
								<td class="meps-td-checkbox"><input type="checkbox" name="meps[service][][active]" id="" class="meps-service-active-checkbox"></td>
								<td style="text-align:left">
									<div class="meps-form-builder-item-container">
										<div class="meps-field-header">
											<input class="meps-service-title" type="text" name="" placeholder="<?php esc_html_e( 'Service title', 'extra-product-and-service' ); ?>">
											<input class="meps-service-price" type="text" name="" placeholder="<?php esc_html_e( 'Service price', 'extra-product-and-service' ); ?>">
										</div>
										<div class="meps-form-builder-inner-container"> <!-- Field container -->
											<div class="meps-form-builder-2nd-level-container">
												<h4><?php esc_html_e( 'Field', 'extra-product-and-service' ); ?></h4>
												<div class="meps-form-builder-2nd-level-sub-container">
													<div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
														<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
														<div class="meps-form-builder-2nd-level-inner-top">
															<p><label for=""><?php esc_html_e( 'Label', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-label">
															</p>
															<p><label for=""><?php esc_html_e( 'Placeholder', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
															<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																<select name="" class="meps-service-field-type">
																	<option value="text"><?php esc_html_e( 'Text', 'extra-product-and-service' ); ?></option>
																	<option value="number"><?php esc_html_e( 'Number', 'extra-product-and-service' ); ?></option>
																	<option value="select"><?php esc_html_e( 'Select', 'extra-product-and-service' ); ?></option>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Required', 'extra-product-and-service' ); ?></label>
																<select name="" class="meps-service-required">
																	<option value="no"><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
																	<option value="yes"><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
																</select>
															</p>
														</div>
														<div class="meps-field-value-container">
															<label for=""><?php esc_html_e( 'Values (for select, radio)', 'extra-product-and-service' ); ?></label>
															<input type="text" name="" class="meps-service-field-values" placeholder="<?php esc_html_e( 'Separate multiple values using a comma', 'extra-product-and-service' ); ?>">
														</div>
													</div>
												</div>
												<button class="meps-add-field-btn"><?php esc_html_e( 'Add field', 'extra-product-and-service' ); ?></button>
											</div>
										</div> <!-- Field container end -->

										<div class="meps-form-builder-inner-container">
											<div class="meps-form-builder-2nd-level-container meps-condition-container"> <!-- Condition container -->
												<h4><?php esc_html_e( 'Condition', 'extra-product-and-service' ); ?></h4>
												<div class="meps-form-builder-2nd-level-sub-container">
													<div class="meps-form-builder-2nd-level-inner-container meps-2nd-level-blueprint">
														<span class="meps-remove-2nd-level"><i class="fa-solid fa-trash"></i></span>
														<div class="meps-form-builder-2nd-level-inner-top">
															<p>
																<label for=""><?php esc_html_e( 'Products', 'extra-product-and-service' ); ?></label>
																<select name="" class="meps-service-condition-product" multiple>
																	<?php
																	if ( $woo_products ) :
																		foreach ( $woo_products as $wp ) :
																			?>
																			<option value="<?php echo $wp->get_id(); ?>"><?php printf( '(%s) - %s ', esc_html( $wp->get_sku() ), $wp->get_title() ); ?></option>
																			<?php
																	endforeach;
																	endif;
																	?>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																<select name="" class="meps-service-condition-type">
																	<option value="cart_quantity"><?php esc_html_e( 'Cart quantity', 'extra-product-and-service' ); ?></option>
																	<option value="cart_total"><?php esc_html_e( 'Cart total', 'extra-product-and-service' ); ?></option>
																	<option value="cart_total_ex_taxes"><?php esc_html_e( 'Cart total excluding taxes', 'extra-product-and-service' ); ?></option>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Value', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-condition-value"></p>
															<p><label for=""><?php esc_html_e( 'Operator', 'extra-product-and-service' ); ?></label>
																<select name="" class="meps-service-condition-operator">
																	<option value="greater_equal"><?php esc_html_e( 'Greater or equal', 'extra-product-and-service' ); ?></option>
																	<option value="lesser_equal"><?php esc_html_e( 'Lesser or equal', 'extra-product-and-service' ); ?></option>
																</select>
															</p>
														</div>
													</div>
												</div>
												<button class="meps-add-field-btn"><?php esc_html_e( 'Add condition', 'extra-product-and-service' ); ?></button>
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

		/** Billing field */
		public function billing_field() {
			?>
			<div class="meps-section">
				<div class="meps-section-heading">
					<div class="left">
						<h3><?php esc_html_e( 'Billing', 'extra-product-and-service' ); ?></h3>
					</div>
					<div class="right"><button class="meps-section-btn btn-no-style"><i class="fa-solid fa-chevron-down"></i></button></div>
				</div>

				<div class="meps-section-content"><?php esc_html_e( 'Billing field', 'extra-product-and-service' ); ?></div>
			</div>
			<?php
		}

		/** Shipping field */
		public function shipping_field() {
			$meps_fields = array();
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
								<th class="meps-add-new-btn" style="width: 12%"><i class="fa-solid fa-plus"></i> <?php esc_html_e( 'Add new service', 'extra-product-and-service' ); ?></th>
							</tr>
						</thead>
						<tbody>

							<!-- From DB -->
							<?php
							if ( $meps_fields ) :
								$i = 0;
								foreach ( $meps_fields['service'] as $field ) :
									?>
									<tr class="meps-form-builder-row">
										<td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
										<td class="meps-td-checkbox"><input type="checkbox" name="meps[service][<?php echo esc_attr( $i ); ?>][active]" <?php echo 'on' === $field['active'] ? 'checked' : ''; ?> id="" class="meps-service-active-checkbox"></td>
										<td>
											<div class="meps-form-builder-item-container">
												<div class="meps-field-header">
													<h3><?php echo esc_html( $field['label'] ); ?></h3>
												</div>
												<div class="meps-form-builder-inner-container">
													<div class="meps-form-builder-container">
														<div class="meps-left">
															<p><label for=""><?php esc_html_e( 'Title', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][label]" value="<?php echo esc_html( $field['label'] ); ?>" class="meps-service-label"></p>
															<p><label for=""><?php esc_html_e( 'Placeholder', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_attr( $i ); ?>][placeholder]" value="<?php echo esc_attr( $field['placeholder'] ); ?>" class="meps-service-placeholder"></p>
															<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
																<select name="meps[service][<?php echo esc_attr( $i ); ?>][field_type]" class="meps-service-field-type">
																	<option value="no_field" <?php echo 'no_field' === $field['field_type'] ? 'selected' : ''; ?>><?php esc_html_e( 'No field', 'extra-product-and-service' ); ?></option>
																	<option value="text" <?php echo 'text' === $field['field_type'] ? 'selected' : ''; ?>><?php esc_html_e( 'Text', 'extra-product-and-service' ); ?></option>
																	<option value="number" <?php echo 'number' === $field['field_type'] ? 'selected' : ''; ?>><?php esc_html_e( 'Number', 'extra-product-and-service' ); ?></option>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Price', 'extra-product-and-service' ); ?></label> <input type="number" name="meps[service][<?php echo esc_html( $i ); ?>][price]" value="<?php echo esc_attr( $field['price'] ); ?>" class="meps-service-price"></p>
															<p><label for=""><?php esc_html_e( 'Description', 'extra-product-and-service' ); ?></label> <input type="text" name="meps[service][<?php echo esc_html( $i ); ?>][desc]" value="<?php echo esc_attr( $field['desc'] ); ?>" class="meps-service-desc"></p>
														</div>
														<div class="meps-right">
															<p><label for=""><?php esc_html_e( 'Required', 'extra-product-and-service' ); ?></label>
																<select name="meps[service][<?php echo esc_attr( $i ); ?>][is_required]" class="meps-service-required">
																	<option value="no" <?php echo $field['is_required'] === 'no' ? 'selected' : ''; ?>>No</option>
																	<option value="yes" <?php echo $field['is_required'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Show in emails', 'extra-product-and-service' ); ?></label>
																<select name="meps[service][<?php echo esc_attr( $i ); ?>][show_in_email]" class="meps-service-showinemail">
																	<option value="no" <?php echo $field['show_in_email'] === 'no' ? 'selected' : ''; ?>>No</option>
																	<option value="yes" <?php echo $field['show_in_email'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
																</select>
															</p>
															<p><label for=""><?php esc_html_e( 'Show in order details page', 'extra-product-and-service' ); ?></label>
																<select name="meps[service][<?php echo esc_attr( $i ); ?>][show_in_detail]" class="meps-service-showindetail">
																	<option value="no" <?php echo $field['show_in_detail'] === 'no' ? 'selected' : ''; ?>>No</option>
																	<option value="yes" <?php echo $field['show_in_detail'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
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
									<?php
									$i++;
								endforeach;
								?>

							<?php endif; ?>
							<!-- From DB END -->

							<!-- Service blueprint -->
							<tr class="meps-form-builder-row meps-form-field-blueprint" style="display:block">
								<td class="meps-td-draggable"><i class="fa-solid fa-up-down-left-right"></i></td>
								<td class="meps-td-checkbox"><input type="checkbox" name="meps[service][][active]" id="" class="meps-service-active-checkbox"></td>
								<td>
									<div class="meps-form-builder-item-container">
										<div class="meps-field-header">
											<h3><?php esc_html_e( 'Service Title', 'extra-product-and-service' ); ?></h3>
										</div>
										<div class="meps-form-builder-inner-container">
											<div class="meps-form-builder-container">
												<div class="meps-left">
													<p><label for=""><?php esc_html_e( 'Title', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-label"></p>
													<p><label for=""><?php esc_html_e( 'Placeholder', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-placeholder"></p>
													<p><label for=""><?php esc_html_e( 'Type', 'extra-product-and-service' ); ?></label>
														<select name="" class="meps-service-field-type">
															<option value="no_field"><?php esc_html_e( 'No field', 'extra-product-and-service' ); ?></option>
															<option value="text"><?php esc_html_e( 'Text', 'extra-product-and-service' ); ?></option>
															<option value="number"><?php esc_html_e( 'Number', 'extra-product-and-service' ); ?></option>
														</select>
													</p>
													<p><label for=""><?php esc_html_e( 'Price', 'extra-product-and-service' ); ?></label> <input type="number" name="" class="meps-service-price"></p>
													<p><label for=""><?php esc_html_e( 'Description', 'extra-product-and-service' ); ?></label> <input type="text" name="" class="meps-service-desc"></p>
												</div>
												<div class="meps-right">
													<p><label for=""><?php esc_html_e( 'Required', 'extra-product-and-service' ); ?></label>
														<select name="" class="meps-service-required">
															<option value="no"><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
															<option value="yes"><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
														</select>
													</p>
													<p><label for=""><?php esc_html_e( 'Show in emails', 'extra-product-and-service' ); ?></label>
														<select name="" class="meps-service-showinemail">
															<option value="no"><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
															<option value="yes"><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
														</select>
													</p>
													<p><label for=""><?php esc_html_e( 'Show in order details page', 'extra-product-and-service' ); ?></label>
														<select name="" class="meps-service-showindetail">
															<option value="no"><?php esc_html_e( 'No', 'extra-product-and-service' ); ?></option>
															<option value="yes"><?php esc_html_e( 'Yes', 'extra-product-and-service' ); ?></option>
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
