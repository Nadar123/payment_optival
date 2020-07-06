<?php

/*
  Plugin Name: payment plugin
  Description: payment plugin task
  Version: 1
  Author: Nadar Rossano
  Author URI: http://payment.local/
 */

if ( ! defined( 'ABSPATH' ) ){
    die();
}
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function jal_install() {

    if( version_compare( get_bloginfo('version'), '4.0', '<' ) ) {
        wp_die(__('Plz update Wordpress version to use this current Plugin :-)', 'payment_plugin'));
    }

    jal_create_paymet_list_table();
    
    jal_create_user_view_table();
}

register_activation_hook( __FILE__, 'jal_install' );
//adding in menu
add_action('admin_menu', 'at_try_menu');

function at_try_menu() {
    //adding plugin in menu
    add_menu_page(
        'payment_list', 
        'payment listing', 
        'manage_options',
        'payment_listing', 
        'payment_list'
    );
    //adding submenu to a menu
    add_submenu_page(
        'payment_listing',
        'payment_item',
        'payment insert',
        'manage_options',
        'payment_item',
        'payment_item'
    );
}

function jal_create_paymet_list_table(){
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'payment_list';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT, 
        payment_method tinytext NOT NULL, 
        min_deposit text NOT NULL, 
        max_deposit text NOT NULL, 
        deposit_fee text NOT NULL, 
        deposit_processing_time text NOT NULL, 
        min_withdrawal text NOT NULL, 
        max_withdrawal text NOT NULL, 
        withdrawal_fee text NOT NULL, 
        withdrawal_processing_time text,
        PRIMARY KEY (id)
    )";
    
    dbDelta( $sql );
}

function jal_create_user_view_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'payment_user_view';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT, 
        client_ip varchar(15) NOT NULL, 
        page_id mediumint(9) NOT NULL,
        created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        device_type text NOT NULL,
        PRIMARY KEY (id)
    )";
     dbDelta( $sql );
}

function jal_log_client_view(){
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'payment_user_view';

    $args = array(
        'client_ip' => jal_get_client_ip(),
        'page_id'=> get_the_ID(),
        'device_type' => jal_get_client_device()
    );
    $response = $wpdb->insert($table_name,$args);
}

function jal_get_client_ip(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    $ip_array = explode(',',$ipaddress);
    return $ip_array[0];
}

function jal_get_client_device(){
    return $_SERVER['HTTP_USER_AGENT'];
}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . './payment_crud/payment_list.php');
require_once (ROOTDIR.'./payment_crud/payment_item.php');
require_once (ROOTDIR.'./payment_crud/payment_delete.php');
