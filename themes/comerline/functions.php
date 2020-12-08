<?php

function my_theme_scripts()
{
    wp_register_script('ajax', get_template_directory_uri().'/assets/js/script.js', ['jquery'], null, true);
    wp_enqueue_script('ajax');
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');