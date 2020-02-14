<?php
/**
 * Plugin Name: Cookie Plugin
 */

add_filter("gform_field_value_hfref", "populate_hfref");
function populate_hfref() {
	return $_COOKIE["hfref"];
}



/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function hf-cookie_settings_init()
{
    // register a new setting for "hf-cookie" page
    register_setting('hf-cookie', 'hf-cookie_options');

    // register a new section in the "hf-cookie" page
    add_settings_section(
        'hf-cookie_section_developers',
        __('The Matrix has you.', 'hf-cookie'),
        'hf-cookie_section_developers_cb',
        'hf-cookie'
    );

    // register a new field in the "hf-cookie_section_developers" section, inside the "hf-cookie" page
    add_settings_field(
        'hf-cookie_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Pill', 'hf-cookie'),
        'hf-cookie_field_pill_cb',
        'hf-cookie',
        'hf-cookie_section_developers',
        [
            'label_for' => 'hf-cookie_field_pill',
            'class' => 'hf-cookie_row',
            'hf-cookie_custom_data' => 'custom',
        ]
    );
}

/**
 * register our hf-cookie_settings_init to the admin_init action hook
 */
add_action('admin_init', 'hf-cookie_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function hf-cookie_section_developers_cb($args)
{
?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'hf-cookie'); ?></p>
<?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function hf-cookie_field_pill_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('hf-cookie_options');
    // output the field
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['hf-cookie_custom_data']); ?>" name="hf-cookie_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="red" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?php esc_html_e('red pill', 'hf-cookie'); ?>
        </option>
        <option value="blue" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?php esc_html_e('blue pill', 'hf-cookie'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'hf-cookie'); ?>
    </p>
    <p class="description">
        <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'hf-cookie'); ?>
    </p>
<?php
}

/**
 * top level menu
 */
function hf-cookie_options_page()
{
    // add top level menu page
    add_menu_page(
        'hf-cookie',
        'hf-cookie Options',
        'manage_options',
        'hf-cookie',
        'hf-cookie_options_page_html'
    );
}

/**
 * register our hf-cookie_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'hf-cookie_options_page');

/**
 * top level menu:
 * callback functions
 */
function hf-cookie_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('hf-cookie_messages', 'hf-cookie_message', __('Settings Saved', 'hf-cookie'), 'updated');
    }

    // show error/update messages
    settings_errors('hf-cookie_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "hf-cookie"
            settings_fields('hf-cookie');
            // output setting sections and their fields
            // (sections are registered for "hf-cookie", each field is registered to a specific section)
            do_settings_sections('hf-cookie');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}
