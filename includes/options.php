<?php
  if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//* add the admin options page
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
  add_options_page('DOMS Search Page', 'DOMS Search Menu', 'manage_options', 'doms-search', 'doms_search_options_page');
}

// display the admin options page
function doms_search_options_page() {
?>
  <div>
  <h2><?php _e('DOMS Search settings', 'doms-search'); ?></h2>
  <?php
    _e( 'Options to enable filter search by format Plugin.', 'doms-search' );
  ?>
  <form action="options.php" method="post">
  <?php settings_fields('plugin_options'); ?>
  <?php do_settings_sections('plugin'); ?>

  <input name="Submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes', 'doms-search'); ?>" />
  </form></div>

<?php
}
// add the admin settings and such
add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init(){
  add_settings_section('plugin_main', __('Main Settings', 'doms-search'), 'plugin_section_text', 'plugin');
  register_setting( 'plugin_options', 'format_options', 'format_option_validate' );

  add_settings_field('choose_template', __('Choose SELECT or CHECKBOX', 'doms-search'), 'choose_setting_template', 'plugin', 'plugin_main' );

  add_settings_field('format_pdf_check', __('Enable filter PDF file', 'doms-search'), 'format_setting_pdf', 'plugin', 'plugin_main');
  add_settings_field('format_doc_check', __('Enable filter DOC file', 'doms-search'), 'format_setting_doc', 'plugin', 'plugin_main');
  add_settings_field('format_img_check', __('Enable filter IMG file', 'doms-search'), 'format_setting_img', 'plugin', 'plugin_main');
  add_settings_field('format_video_check', __('Enable filter VIDEO file', 'doms-search'), 'format_setting_video', 'plugin', 'plugin_main');

}
?>
<?php
function plugin_section_text() {
  _e('This section is for setup the settings for Search filter plugin','doms-search');
}
?>

<?php
function choose_setting_template () {
  $options = get_option('format_options');
  ?>
  <select id="choose_template" class="widefat form-control" name="format_options[template]">
    <option value="select" <?php  selected( $options['template'], 'select' ) ?>>
      <?php _e('Select Tag', 'doms-search' ) ?></option>
    <option value="checkbox" <?php selected( $options['template'], 'checkbox') ?>>
      <?php _e('Checkbox tag','doms-search') ?> </option>
  </select>
  <?php

}

function format_setting_pdf () {
  $options = get_option('format_options');

  echo "<label><input id='format_pdf_check' type='checkbox' name='format_options[pdf_check]' value='1' " . checked( 1, $options['pdf_check'], false ). " /> <span class='dashicons dashicons-media-document'></span></label>";
}

function format_setting_doc () {
  $options = get_option('format_options');

  echo "<label><input id='format_doc_check' type='checkbox' name='format_options[doc_check]'
    value='1' " . checked( 1, $options['doc_check'], false ). " /> <span
      class='dashicons dashicons-format-aside'></span></label>";
}

function format_setting_img () {
  $options = get_option('format_options');

  echo "<label><input id='format_img_check' type='checkbox'
    name='format_options[img_check]'
  value='1' " . checked( 1, $options['img_check'], false ). " /> <span
    class='dashicons dashicons-format-image'></span></label>";
}

function format_setting_video () {
  $options = get_option('format_options');

  echo "<label><input id='format_video_check' type='checkbox' name='format_options[video_check]'
  value='1' " . checked( 1, $options['video_check'], false ). " /> <span class='dashicons dashicons-format-video'></span></label>";
}

function format_option_validate ($input) {
    $options = get_option('format_options');
    $options['pdf_check'] = trim($input['pdf_check']);
    $options['pdf_check'] = filter_var($options['pdf_check'], FILTER_SANITIZE_NUMBER_INT);
    $options['doc_check'] = trim($input['doc_check']);
    $options['doc_check'] = filter_var($options['doc_check'], FILTER_SANITIZE_NUMBER_INT);
    $options['img_check'] = trim($input['img_check']);
    $options['img_check'] = filter_var($options['img_check'], FILTER_SANITIZE_NUMBER_INT);
    $options['video_check'] = trim($input['video_check']);
    $options['video_check'] = filter_var($options['video_check'], FILTER_SANITIZE_NUMBER_INT);
    $options['template'] = trim($input['template']);

    return $options;
}

?>
