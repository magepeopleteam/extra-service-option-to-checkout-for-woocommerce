<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


if (!class_exists('Meps_Form_Builder')) {
    class Meps_Form_Builder
    {
        public function field($field_data, $indexes)
        {
            switch ($field_data['field_type']) {
                case 'text':
                    $this->text($field_data, $indexes);
                    break;
                case 'number':
                    $this->number($field_data, $indexes);
                    break;
                case 'select':
                    $this->select($field_data, $indexes);
                    break;
                default:
                    $this->no_field($field_data, $indexes);
            }
        }

        public function no_field($field_data, $indexes)
        {
            if ($field_data["desc"]) {
                echo '<span class="meps-desc">&#9432; ' . $field_data["desc"] . '</span>';
            }
        }

        public function text($field_data, $indexes)
        { ?>
            <input type="text" name="meps_session[service][<?php echo $indexes['service_index']; ?>][item][<?php echo $indexes['item_index']; ?>]" placeholder="<?php echo $field_data['placeholder'] ?>">

        <?php
        }

        public function number($field_data, $indexes)
        { ?>
            <input type="number" placeholder="<?php echo $field_data['placeholder'] ?>">

            <?php
        }

        public function select($field_data, $indexes)
        {
            if ($field_data['field_values']) :
                $options = explode(',', $field_data['field_values']);
            ?>
                <select name="" id="">
                    <?php foreach ($options as $option) : ?>
                        <option value="<?php echo $option ?>"><?php echo $option ?></option>
                    <?php endforeach; ?>
                </select>

<?php
            endif;
        }
    }

    // $meps = array(
    //     'service' => array(
    //         0 => array(
    //             "id" => 1,
    //             "item" => array(
    //                 0 => 'gmail',
    //                 1 => 'shohan',
    //                 2 => '555345',
    //             )
    //         ),
    //         1 => array(
    //             "id" => 2,
    //             "item" => array(
    //                 0 => 'small',
    //                 1 => 'red',
    //             )
    //         )
    //     )
    // );
}
