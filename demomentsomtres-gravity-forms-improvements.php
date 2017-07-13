<?php
/*
  Plugin Name: DeMomentSomTres Gravity Forms Improvements
  Plugin URI: http://demomentsomtres.com/en/wordpress-plugins/demomentsomtres-gf/
  Description: Gravity Forms Improvements
  Version: 1.3
  Author: Marc Queralt
  Author URI: http://demomentsomtres.com
 */

// Create a helper function for easy SDK access.
function dms3_gfimpr_fs() {
    global $dms3_gfimpr_fs;

    if ( ! isset( $dms3_gfimpr_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $dms3_gfimpr_fs = fs_dynamic_init( array(
            'id'                => '589',
            'slug'              => 'demomentsomtres-gravity-forms-improvements',
            'type'              => 'plugin',
            'public_key'        => 'pk_476d4bda304ced9c8643914061047',
            'is_premium'        => false,
            'has_addons'        => false,
            'has_paid_plans'    => false,
            'menu'              => array(
                'first-path' => 'plugins.php',
                'account'    => false,
                'contact'    => false,
                'support'    => false,
            ),
        ) );
    }

    return $dms3_gfimpr_fs;
}

// Init Freemius.
dms3_gfimpr_fs();

$dms3GF=new DeMomentSomTresGF();

class DeMomentSomTresGF {

    const VERSION = 1.3;
    const DATEINLINE = "dms3dateinline";
  
    private $pluginURL;
    private $pluginPath;
    private $langDir;

    /**
     * @since 1.0
     */
    function __construct() {
        $this->pluginURL = plugin_dir_url(__FILE__);
        $this->pluginPath = plugin_dir_path(__FILE__);
        $this->langDir = dirname(plugin_basename(__FILE__)) . '/languages';

        add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );      

        add_action('tgmpa_register', array($this,'required_plugins'));
        add_action('gform_loaded',array($this,"gform_loaded"),5);
    }
    
    function required_plugins() {
        $plugins = array(
            array(
                'name' => 'Gravity Forms',
                'slug' => 'gravityforms',
                'required' => true
            ),
        );
        tgmpa($plugins);
      }
      
      function gform_loaded() {
					if(!method_exists("GFForms","include_addon_framework")):
						return;
					endif;
					require_once("class-demomentsomtres-gravity-forms.php");
					GFAddOn::register("DeMomentSomTresGravityFormsAddon");
      }
}
