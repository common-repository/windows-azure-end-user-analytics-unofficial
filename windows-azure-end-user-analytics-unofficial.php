<?php
/**
 * Plugin Name: Windows Azure End User Analytics for WordPress (Unofficial)
 * Plugin URI: http://blog.timhward.net/windows-azure-end-user-analytics-for-wordpress-unofficial/
 * Description: Adds Windows Azure End User Analytics to WordPress pages.
 * Version: 1.0
 * Author: timward60
 * Author URI: http://blog.timhward.net/
 * License: GPL2
 */

/*  Copyright 2014  Timothy Ward  (email : timward60@hotmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

defined('ABSPATH') or die("Requires loading via WordPress plugin.");

if(!class_exists('WindowsAzureEndUserAnalytics'))
{
    class WindowsAzureEndUserAnalytics {
        private static $_instance;
        private $_options_group = "windows-azure-end-user-analytics-options";
        private $_options_app_insight_key = "app_insights_app_key";
        private $_script_handle = "windows-azure-end-user-analytics";
        private $_script_filename = "js/windows-azure-end-user-analytics.js";
        private $_script_version = "1.0";
        private $_script_param_app_insight_app_key = "app_insights_app_key";
        private $_script_param = "php_param";
        private $_options_page_title = "Windows Azure End User Analytics (Unofficial) for WordPress Settings";
        private $_options_page_slug = "windows-azure-end-user-analytics-unofficial_settings";
        private $_options_page_filename = "templates/settings.php";
                
        public static function get_instance() {
            if(!self::$_instance) { 
                self::$_instance = new self(); 
            }
            return self::$_instance; 
        }
        
        private function __construct() {
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
            add_action('wp_enqueue_scripts', array(&$this, 'enable'));           
        }
        
        public function admin_init() {
            register_setting($this->_options_group, $this->_options_app_insight_key);
        }   
        
        public function enable() {
            wp_register_script(
                $this->_script_handle,
                plugins_url($this->_script_filename, __FILE__),
                false,
                $this->_script_version,
                true);            
            
            wp_enqueue_script($this->_script_handle);

            $params = array($this->_script_param_app_insight_app_key => get_option($this->_options_app_insight_key));
            
            wp_localize_script($this->_script_handle, $this->_script_param, $params);
        }
           
        public function add_menu() {
            add_options_page($this->_options_page_title, $this->_options_page_title, 'manage_options', $this->_options_page_slug, array(&$this, 'plugin_settings_page'));
        }
            
        public function plugin_settings_page() {
            include(sprintf("%s/%s", dirname(__FILE__), $this->_options_page_filename));
        }
        
        public static function activation() {

        }
        
        public static function deactivation() {

        }      
    };
}

if(class_exists('WindowsAzureEndUserAnalytics'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WindowsAzureEndUserAnalytics', 'activate'));
    register_deactivation_hook(__FILE__, array('WindowsAzureEndUserAnalytics', 'deactivate'));

    // instantiate the plugin class
    $windows_azure_end_user_analytics = WindowsAzureEndUserAnalytics::get_instance();
    
    if(isset($windows_azure_end_user_analytics))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        { 
            if(!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            
            $settings_link = '<a href="options-general.php?page=windows-azure-end-user-analytics-unofficial_settings">Settings</a>'; 
            array_unshift($links, $settings_link); 
            return $links; 
        }

        $plugin = plugin_basename(__FILE__); 
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }   
}

?>
