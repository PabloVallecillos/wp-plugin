<?php
/*
 * Plugin Name: Chucknorris
 * Author: Pablo Vallecillos
 * Description: Application for job
 * Version: 1.0.0
 */

function test_plugin_setup_menu(){
    // This function takes a capability which will be used to determine whether or not a page is included in the menu.
    add_menu_page( 'Chucknorris', 'Chucknorris', 'manage_options', 'Chucknorris', 'test_init' );
}
// Hooks a function on to a specific action.
// admin_menu Fires before the administration menu loads in the admin.
add_action('admin_menu', 'test_plugin_setup_menu');

function test_init()
{
    // Create custom database wp_category

    // superglobal $GLOBALS['wpdb'] WordPress database access
    $table = $GLOBALS['wpdb']->prefix.'category';
    $charset_collate = $GLOBALS['wpdb']->get_charset_collate();
    $query = "CREATE TABLE IF NOT EXISTS  ".$table." (id int NOT NULL AUTO_INCREMENT, category VARCHAR(255), PRIMARY KEY (id)) $charset_collate;";
    $GLOBALS['wpdb']->query($query);
    ?>
    <!-- Select in admin area -->
        <div class="top">
            <label for="categories"> <?php _e('Select category'); ?> </label><br>
            <select class="top" name="categories" id="appendCategories"></select>
        </div>
    <?php
}

function add_css_js_plugin() {
    // Register and enqueue css and script.
    wp_register_style('mycss',plugin_dir_url(__FILE__).'/css/mycss.css');
    wp_enqueue_style('mycss');
    wp_register_script('myjs',plugin_dir_url(__FILE__).'js/myjs.js', ['jquery'], null, true);
    wp_enqueue_script('myjs');

    // Additionally, we must use wp_localize_script() to pass values into JavaScript object properties,
    // since PHP cannot directly echo values into our JavaScript file.
    // In JavaScript, object properties are accessed as ajax_object.ajax_url.
    wp_localize_script('myjs', 'object_ajax', ['ajax' => admin_url('admin-ajax.php')]);
}
// admin_init is triggered before any other hook when a user accesses the admin area.
add_action('admin_init', 'add_css_js_plugin');
add_action('wp_enqueue_scripts', 'add_css_js_plugin');

/**
 * [chuckNorrisLog] returns the HTML code for a content box with colored categories.
 * @return string HTML code for boxed log content
 */
function chucknorris_log( $atts, $content = null, $tag = '' )
{
    $value = $GLOBALS['wpdb']->get_results("SELECT category FROM {$GLOBALS['wpdb']->prefix}category LIMIT 1");
    $a = shortcode_atts( [
        'title' => 'Current category: '.$value[0]->category,
        'title_color' => 'white',
        'color' => 'blue',
    ], $atts );

    $output = '<div id="appendBox" style="border:2px solid ' . esc_attr( $a['color'] ) . ';">
                '.'<div style="background-color:' . esc_attr( $a['color'] ) . ';">
                    <h3 id="category" style="margin-left: 5px; color:' . esc_attr( $a['title_color'] ) . ';">' . esc_attr( $a['title'] ) . '</h3>
                   </div> 
               </div>';

    return $output;
}
add_shortcode( 'chuckNorrisLog', 'chucknorris_log' );

function create_database_or_update()
{
    $category = $_POST['categories'];
    $value = $GLOBALS['wpdb']->get_results("SELECT category FROM {$GLOBALS['wpdb']->prefix}category LIMIT 1");
    if(!$value[0]->category) {
        $query = "INSERT INTO {$GLOBALS['wpdb']->prefix}category (category) VALUES ('$category');";
    } else {
        $query = "UPDATE {$GLOBALS['wpdb']->prefix}category SET category = '$category';";
    }
    $GLOBALS['wpdb']->query($query);
}
// The dynamic portion of the hook name, $action ( wp_ajax_{$action} ), refers to the name of the Ajax action callback being fired.
add_action('wp_ajax_create_or_update', 'create_database_or_update');

function get_category_ajax_api()
{
    $value = $GLOBALS['wpdb']->get_results("SELECT category FROM {$GLOBALS['wpdb']->prefix}category LIMIT 1");
    echo $value[0]->category;
    wp_die(); // this is required to terminate immediately and return a proper response.
}
add_action('wp_ajax_get_category_api', 'get_category_ajax_api');
