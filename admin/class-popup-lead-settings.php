<?php

/**
 * The settings of the plugin.
 *
 * @link       https://www.mikehit.com
 * @since      1.1.0
 * @package    PopupLead
 * @subpackage PopupLead/includes
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class popup_lead_Admin_Settings
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level	 
	 */
	public function setup_plugin_options_menu()
	{

		//Add the menu to the Plugins set of menu items
		add_menu_page(
			'Popup Lead', 					// The title to be displayed in the browser window for this page.
			'Popup Lead',					// The text to be displayed for this menu item
			'manage_options',					// Which type of users can see this menu item
			'popup_lead_options',			// The unique ID - that is, the slug - for this menu item
			array($this, 'render_settings_page_content')				// The name of the function to call when rendering this menu's page
		);
	}

	/**
	 * Provides default values for the Display Options.
	 *
	 * @return array
	 */
	public function default_display_options()
	{

		$defaults = array(
			'show_header' =>	'',
			'mikehitID' => 'jaDPstT2YA',
			'actionLabel' => 'P L A Y',
			'labelColor' => '#000',
			'actionSizeW' => '100',
			'actionIconW' => '50',
			'positionTop' => '10',
			'positionRight' => '2'
		);

		return $defaults;
	}

	/**
	 * Renders a page to display for the theme menu defined above.
	 */
	public function render_settings_page_content($active_tab = '')
	{
?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e('Mikehit Popup Lead Option', 'mikehit-plugin'); ?></h2>
			<?php settings_errors(); ?>

			<?php
			$active_tab = 'display_options';
			?>


			<h2 class="nav-tab-wrapper">
				<a href="?page=mikehit_options&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php _e('MikeHit Popup Lead Options', 'mikehit-plugin'); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php

				if ($active_tab == 'display_options') {
					settings_fields('mikehit_display_options');
					do_settings_sections('mikehit_display_options');
				} // end if/else
				submit_button();
				?>
			</form>

		</div><!-- /.wrap -->
	<?php

	}

	//Page metabox operation for Mikehit Enable/Disable to show at page
	public function add_metabox_post_sidebar()
	{
		add_meta_box("Enable Mikehit", "Enable Mikehit", array($this, 'enable_sidebar_posts'), "page", "side", "high");
	}

	public function enable_sidebar_posts()
	{
		global $post;
		$check = get_post_custom($post->ID);
		$checked_value = isset($check['enable_mikehit']) ? esc_attr($check['enable_mikehit'][0]) : 'no';
	?>

		<label for="enable_mikehit">Enable Mikehit Popup Lead:</label>
		<input type="checkbox" name="enable_mikehit" id="enable_mikehit" <?php if ($checked_value == "yes") {
																				echo "checked=checked";
																			} ?>>
		<p><em>( Check to enable MikeHit Popup Lead. )</em></p>
<?php
	}

	public function save_metabox_post_sidebar($post_id)
	{
		// Bail if we're doing an auto save
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post')) return;

		$checked_value = isset($_POST['enable_mikehit']) ? 'yes' : 'no';
		update_post_meta($post_id, 'enable_mikehit', $checked_value);
	}
	//End thePage metabox operation for Mikehit Enable/Disable to show at page

	public function show_mikehit()
	{

		global $post;
		$check = get_post_custom($post->ID);
		if (isset($check['enable_mikehit']['0'])) {
			if ($check['enable_mikehit']['0'] === 'yes') {
				$options = get_option('mikehit_display_options');
				//Set default value for Mikehit ID demo session
				if (!$options['mikehitID']) {
					$options['mikehitID'] = 'jaDPstT2YA';
				}
				if (!$options['actionLabel']) {
					$options['actionLabel'] = "P L A Y";
				}
				if (!$options['labelColor']) {
					$options['labelColor'] = "#000";
				}
				if (!$options['actionSizeW']) {
					$options['actionSizeW'] = "100";
				}
				if (!$options['actionIconW']) {
					$options['actionIconW'] = "50";
				}				
				if (!$options['positionTop']) {
					$options['positionTop'] = "10";
				}
				if (!$options['positionRight']) {
					$options['positionRight'] = "2";
				}



				if ($options['show_header'] == '1') {
					if (function_exists('wp_add_inline_script')) {
						wp_register_script('mikehit-handle-footer', '', [], '', true);
						wp_enqueue_script('mikehit-handle-footer');
						wp_add_inline_script(
							'mikehit-handle-footer',
							"let mikeData = {
							\"guid\":\"{$options['mikehitID']}\"
							,\"actionLabel\":\"{$options['actionLabel']}\"
							,\"labelColor\":\"{$options['labelColor']}\"
							,\"actionSizeW\":\"{$options['actionSizeW']}px\"
							,\"actionSizeH\":\"{$options['actionSizeW']}px\"
							,\"actionPosTop\":\"{$options['positionTop']}%\"
							,\"actionPosRight\":\"{$options['positionRight']}%\"
							,\"actionIcon\":\"https://www.mikehit.com/assets/img/icons/winner-trophy-2.png\"
							,\"actionIconW\":\"{$options['actionIconW']}px\"							
						};"
						);

						wp_enqueue_script('mikehit-js', 'https://www.mikehit.com/assets/js/mikehit.js', array(), '3', true);
					}
				}
			}
		}
	}


	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the 'mikehit_initialize_theme_options' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function general_options_callback()
	{

		echo '<p>' . __('Setup MikeHit Popup Lead ID.', 'mikehit-plugin') . '</p>';
	} // end general_options_callback


	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options()
	{

		// If the theme options don't exist, create them.
		if (false == get_option('mikehit_display_options')) {
			$default_array = $this->default_display_options();
			add_option('mikehit_display_options', $default_array);
		}


		add_settings_section(
			'general_settings_section',			            // ID used to identify this section and with which to register options
			__('MikeHit Popup lead is the ultimate tool to engage visitors', 'mikehit-plugin'),		        // Title to be displayed on the administration page
			array($this, 'general_options_callback'),	    // Callback used to render the description of the section
			'mikehit_display_options'		                // Page on which to add this section of options
		);

		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(
			'show_mikehit',						        // ID used to identify the field throughout the theme
			__('Enable MikeHit Popup Lead', 'mikehit-plugin'),					// The label to the left of the option interface element
			array($this, 'toggle_header_callback'),	// The name of the function responsible for rendering the option interface
			'mikehit_display_options',	            // The page on which this option will be displayed
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__('Show MikeHit.', 'mikehit-plugin'),
			)
		);

		add_settings_field(
			'mikehitID',
			__('MikeHit ID', 'mkehit-plugin'),
			array($this, 'mikeID_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);

		add_settings_field(
			'actionLabel',
			__('Button Label', 'mkehit-plugin'),
			array($this, 'actionLabel_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);

		add_settings_field(
			'labelColor',
			__('Button Label Color', 'mkehit-plugin'),
			array($this, 'labelColor_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);
		add_settings_field(
			'actionSizeW',
			__('Trophy label Width px', 'mkehit-plugin'),
			array($this, 'actionSizeW_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);
		add_settings_field(
			'actionIconW',
			__('Trophy Icon Width px', 'mkehit-plugin'),
			array($this, 'actionIconW_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);
		add_settings_field(
			'positionTop',
			__('Trophy button Positon Top %', 'mkehit-plugin'),
			array($this, 'positionTop_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);
		add_settings_field(
			'positionRight',
			__('Trophy button Positon Right %', 'mkehit-plugin'),
			array($this, 'positionRight_callback'),
			'mikehit_display_options',
			'general_settings_section'
		);



		// Finally, we register the fields with WordPress
		register_setting(
			'mikehit_display_options',
			'mikehit_display_options',
			array($this, 'validate_input')
		);
	} // end mikehit_initialize_theme_options


	/**
	 * This function renders the interface elements for toggling the visibility of the header element.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function toggle_header_callback($args)
	{

		// First, we read the options collection
		$options = get_option('mikehit_display_options');

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="show_header" name="mikehit_display_options[show_header]" value="1" ' . checked(1, isset($options['show_header']) ? $options['show_header'] : 0, false) . '/>';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="show_header">&nbsp;'  . $args[0] . '</label>';

		echo $html;
	} // end toggle_header_callback


	public function mikeID_callback()
	{

		$options = get_option('mikehit_display_options');

		// Render the output
		echo '<input type="text" id="mikehitID" name="mikehit_display_options[mikehitID]" value="' . $options['mikehitID'] . '" />';
	} // end input_element_callback
	public function actionLabel_callback()
	{

		$options = get_option('mikehit_display_options');

		// Render the output
		echo '<input type="text" id="actionLabel" name="mikehit_display_options[actionLabel]" value="' . $options['actionLabel'] . '" />';
	} // end input_element_callback
	public function labelColor_callback()
	{
		$options = get_option('mikehit_display_options');


		// Render the output
		echo '<input type="text" id="labelColor" name="mikehit_display_options[labelColor]" value="' . $options['labelColor'] . '" />';
	} // end input_element_callback
	public function actionSizeW_callback()
	{
		$options = get_option('mikehit_display_options');


		// Render the output
		echo '<input type="text" id="actionSizeW" name="mikehit_display_options[actionSizeW]" value="' . $options['actionSizeW'] . '" />';
	} // end input_element_callback

	public function actionIconW_callback()
	{
		$options = get_option('mikehit_display_options');


		// Render the output
		echo '<input type="text" id="actionIconW" name="mikehit_display_options[actionIconW]" value="' . $options['actionIconW'] . '" />';
	} // end input_element_callback

	
	public function positionTop_callback()
	{
		$options = get_option('mikehit_display_options');


		// Render the output
		echo '<input type="text" id="positionTop" name="mikehit_display_options[positionTop]" value="' . $options['positionTop'] . '" />';
	} // end input_element_callback

	public function positionRight_callback()
	{
		$options = get_option('mikehit_display_options');


		// Render the output
		echo '<input type="text" id="positionRight" name="mikehit_display_options[positionRight]" value="' . $options['positionRight'] . '" />';
	} // end input_element_callback

	public function validate_input($input)
	{

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ($input as $key => $value) {

			// Check to see if the current option has a value. If so, process it.
			if (isset($input[$key])) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags(stripslashes($input[$key]));
			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters('validate_input', $output, $input);
	} // end validate_input




}
