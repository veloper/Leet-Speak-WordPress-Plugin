<?php
if (is_admin()) {
    // Admin Panel
    add_action('admin_menu', 'ddleetspeak_menu');
    function ddleetspeak_menu() {
        add_options_page('Leet Speak', 'Leet Speak', 8, 'ddleetspeak', 'ddleetspeak_options');
    }
    function ddleetspeak_options() {
        include dirname(__FILE__).'/options.php';
    }
    
    // Admin Panel Options
    add_action( 'admin_init', 'ddleetspeak_register_options' );
    function ddleetspeak_register_options() {
        // Alpha Character Translations
        foreach(range('a','z') as $char) register_setting('ddleetspeak-option-group', 'ddleetspeak_'.$char);
    }
}