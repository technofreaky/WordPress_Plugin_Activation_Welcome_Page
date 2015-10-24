<?php

class Activation_Welcome_Page{

    public $plugin_slug;
    public $plugin_url;
    
    public function  __construct($slug = '',$plugin_url = 'welcome-screen-about'){
        $this->plugin_slug = $slug;
        $this->plugin_url = $plugin_url;
        add_action( 'admin_init', array($this,'activation_redirect') );
        add_action( 'admin_menu', array($this,'welcome_screen_pages'));
        add_action( 'admin_head', array($this,'welcome_screen_remove_menus' )); 
    }
    
    /**
     * Sets Transient For Activation hook To Redirect To Welcome Page
     */
    public function activate(){ 
        set_transient( $this->plugin_slug.'_welcome_screen_activation_redirect', true, 30 );
    } 
    
    /**
     * Checks For Active Transient And Redirect To Welcome Page
     */
    public function activation_redirect(){
        if ( ! get_transient( $this->plugin_slug.'_welcome_screen_activation_redirect' ) ) { return; }
        delete_transient( $this->plugin_slug.'_welcome_screen_activation_redirect' );        
        if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) { return; }        
        
        
        wp_safe_redirect( add_query_arg( array( 'page' => $this->plugin_url ), admin_url( 'index.php' ) ) );
    }
    
    /**
     * Adds Welcome Page
     */
    public function welcome_screen_pages() {
        add_dashboard_page('','','read',$this->plugin_url, array($this,'welcome_screen_content'));
    }
    
    /**
     * Welcome Page html
     */
    public function welcome_screen_content() {
        require('page-html.php');
    }
    
    /**
     * Remove Dashboard Welcome Page
     */
    public function welcome_screen_remove_menus() {
        remove_submenu_page( 'index.php', $this->plugin_url );
    }    
}
?>
