<?php
GFForms::include_addon_framework();

class DeMomentSomTresGravityFormsAddon extends GFAddOn {
	protected $_version = DeMomentSomTresGF::VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'demomentsomtres-gravity-forms-improvements';
	protected $_path = 'demomentsomtres-gravity-forms-improvements/demomentsomtres-gravity-forms-improvements.php';
	protected $_full_path = __FILE__;
	protected $_title = 'DeMomentSomTres Gravity Forms Improvements';
	protected $_short_title = 'DeMomentSomTres Gravity Forms';
	
  private static $_instance = null;

  /**
	 * Get an instance of this class.
	 *
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new DeMomentSomTresGravityFormsAddon();
		}
		return self::$_instance;
	}

  public function pre_init() {
    parent::pre_init();

    if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' ) ) {
        require_once( 'includes/class-dateinline-gf-field.php' );
    }
  }
  
  /**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();
	
    add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );
		add_action( 'gform_editor_js', array($this,'gform_editor_js' ));
    //add_action( 'gform_field_appearance_settings', array( $this, 'field_appearance_settings' ), 10, 2 );
    add_action( 'gform_field_standard_settings', array( $this, 'field_standard_settings' ), 10, 2 );
	}

  public function tooltips($tooltips) {
    $theTooltips = array(
      'dms3locale_setting' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Locale', 'demomentsomtres-gravity-forms-improvements' ), esc_html__( 'The locale used to show the DatePicker', "demomentsomtres-gravity-forms-improvements" ) ),
			'dms3nodefault_setting' => sprintf( '<h6>%s</h6>%s', esc_html__( 'No default value', 'demomentsomtres-gravity-forms-improvements' ), esc_html__( 'If selected no default value will be selected', "demomentsomtres-gravity-forms-improvements" ) ),
    );
    return array_merge($tooltips,$theTooltips);
  }
  
  public function field_appearance_settings( $position, $form_id ) {
    // Add our custom setting just before the 'Custom CSS Class' setting.
    if ( $position == 250 ) {
        ?>
        <li class="input_class_setting field_setting">
            <label for="input_class_setting">
                <?php esc_html_e( 'Locale', "demomentsomtres-gravity-forms-improvements" ); ?>
                <?php gform_tooltip( 'dms3locale_setting' ) ?>
            </label>
            <input id="input_class_setting" type="text" class="fieldwidth-1" onkeyup="SetInputClassSetting(jQuery(this).val());" onchange="SetInputClassSetting(jQuery(this).val());"/>
        </li>
        <?php
    }
  }

	public function field_standard_settings( $position, $form_id ) {
    // Add our custom setting just before the 'Custom CSS Class' setting.
    if ( $position == 250 ) {
        ?>
        <li class="input_class_setting field_setting">
            <label for="field_dms3nodefault_value">
                <?php esc_html_e( 'No default', "demomentsomtres-gravity-forms-improvements" ); ?>
                <?php gform_tooltip( 'dms3nodefault_setting' ) ?>
            </label> 
						<input type="checkbox" id="field_dms3nodefault_value" onclick="SetFieldProperty('dms3NoDefault', this.checked);" />
        </li>
        <?php
    }
  }

	public function gform_editor_js(){
    ?>
    <script type='text/javascript'>
        //adding setting to fields of type "text"
        //fieldSettings["text"] += ", .encrypt_setting";

        //binding to the load field settings event to initialize the checkbox
        jQuery(document).bind("gform_load_field_settings", function(event, field, form){
            jQuery("#field_dms3nodefault_value").attr("checked", field["dms3NoDefault"] == true);
        });
    </script>
    <?php
	}
	
	public function scripts() {
	  $scripts = array(
        array(
            'handle'    => 'dms3-gf-dateinline',
            'src'       => $this->get_base_url() . '/js/dms3-gform-dateinline.js',
            'version'   => $this->_version,
            'deps'      => array( 'jquery','jquery-ui-datepicker', ),
            'in_footer' => true,
            //'callback'  => array( $this, 'localize_scripts' ),
            /*'strings'   => array(
                'first'  => __( 'First Choice', 'simpleaddon' ),
                'second' => __( 'Second Choice', 'simpleaddon' ),
                'third'  => __( 'Third Choice', 'simpleaddon' )
            ),*/
            'enqueue'   => array(
                array(
                    //'admin_page' => array( 'form_settings' ),
                    'field_types' => array(DeMomentSomTresGF::DATEINLINE),
                )
            )
        ),
    );
    return array_merge( parent::scripts(), $scripts );
	}

	public function styles() {
		$styles = array(
      array(
				'handle'  => 'gforms_datepicker_css-css',
				'src'     => $this->get_base_url() . '/../gravityforms/css/datepicker.min.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 
						'field_types' => array( DeMomentSomTresGF::DATEINLINE ),
					)
				)
			)
		);
		return array_merge( parent::styles(), $styles );
	}

	// # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------
	
  // # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------
	/**
	 * Define the markup for the my_custom_field_type type field.
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	/* public function settings_my_custom_field_type( $field, $echo = true ) {
		echo '<div>' . esc_html__( 'My custom field contains a few settings:', 'simpleaddon' ) . '</div>';
		// get the text field settings from the main field and then render the text field
		$text_field = $field['args']['text'];
		$this->settings_text( $text_field );
		// get the checkbox field settings from the main field and then render the checkbox field
		$checkbox_field = $field['args']['checkbox'];
		$this->settings_checkbox( $checkbox_field );
	}
	// # HELPERS -------------------------------------------------------------------------------------------------------
	/**
	 * The feedback callback for the 'mytextbox' setting on the plugin settings page and the 'mytext' setting on the form settings page.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	 */
	/* public function is_valid_setting( $value ) {
		return strlen( $value ) < 10;
	}*/
}