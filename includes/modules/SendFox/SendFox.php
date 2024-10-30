<?php

class MFE_SendFox extends ET_Builder_Module {

	public $slug       = 'mfe_send_fox';
  public $vb_support = 'on';
  
	protected $module_credits = array(
		'module_uri' => 'https://my.likeable.link/sfwp3',
		'author'     => 'likeablepress',
		'author_uri' => 'https://my.likeable.link/sfwplp',
  );
  

	public function init() {
    $this->name = esc_html__( 'SendFox for Divi', 'mfe-send-fox-extension' );
    // Module Icon
		// Load customized svg icon and use it on builder as module icon. If you don't have svg icon, you can use
    // $this->icon for using etbuilder font-icon. (See CustomCta / DICM_CTA class)
    //$this->icon =  plugin_dir_path( __FILE__ ) . 'icon.svg';
		$this->icon_path        =  plugin_dir_path( __FILE__ ) . 'icon.svg';

    $this->settings_modal_toggles = [

      'advanced' => array(
				'toggles' => array(
          "colors" => esc_html__("Colors", "mfe-send-fox-extension"),
				),
      ),
      
      'custom_css' => array(
				'toggles' => array(
					'limitation' => esc_html__( 'Limitation', 'dicm-divi-custom-modules' ), // totally made up
				),
      ),

      'general'  => [
				'toggles' => [
          'button'   => array(
						'title' => esc_html__( 'Button', 'et_builder' ),
						'priority' => 51,
          ),
        ],
      ],
    ];

    $this->main_css_element = '%%order_class%%';


    $this->custom_css_fields = array(
			
      'main_cointainer' => array(
				'label'    => esc_html__( 'Container Left and Right', 'mfe-send-fox-extension' ),
				'selector' => '.holderSendFox',
      ),
      'left_layout' => array(
				'label'    => esc_html__( 'Left Layout (Content)', 'mfe-send-fox-extension' ),
				'selector' => '.lsf_left',
			),
			'right_layout' => array(
				'label'    => esc_html__( 'Right Layout (Form)', 'mfe-send-fox-extension' ),
        'selector' => '.lsf_right, lsf_info_body',
      ),
      'form' => array(
				'label'    => esc_html__( 'Fields', 'mfe-send-fox-extension' ),
				'selector' => '.lsf_fields_color',
      ),
      'button' => array(
				'label'    => esc_html__( 'Button', 'mfe-send-fox-extension' ),
				'selector' => '.et_pb_button',
      ),
		);
	}

	public function get_fields() {

    $fields["title"] = [
			"label" => esc_html__("Title", 'et_builder'),
			"type" => "text",
			'option_category' => 'basic_option',
      'description'     => esc_html__( 'Choose a title of your signup box.', 'et_builder' ),
      'toggle_slug'     => 'main_content',
      'dynamic_content' => 'text',
      
    ];


    $bodyDefault = "Your content goes here. Edit or remove this text in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.";
    $fields["body"] = [
			"label" => esc_html__("Body", 'et_builder'),
			"type" => "tiny_mce",
      'description'     => esc_html__( 'This content will appear below the title.', 'et_builder' ),
      'toggle_slug'     => 'main_content',
      'dynamic_content' => 'text',
			'mobile_options'  => true,
			'hover'           => 'tabs',
    ];

    

    $options = get_option( 'sfdivi_options' );
    $myToken = "";
    if( !empty( $options['api_key'] ) )
    {
      $myToken  = $options['api_key'];
    }

    $arrList = apply_filters( 'sf_list_filter', $myToken);

    $deafultList = "";
    if( count($arrList) > 0 )
    {
      foreach($arrList as $item => $value)
      {
        $deafultList = $item;
        break;
      }
    }
		$fields["list"] = [
			"label" =>  esc_html__("Sendfox list", 'mfe-send-fox-extension'),
			"type" => "select",
      "options" => $arrList,
      "default" => $deafultList,
      'toggle_slug' => 'email',
      'description'     => esc_html__( "Choose a list. If you don't see any lists, go to the SendFox for Divi Menu and connect your account.", 'mfe-send-fox-extension' ),
		];

    $fields["after_submit"] = [
      'label' => esc_html__('Success action', 'mfe-send-fox-extension'),
      'type' => 'select',
      "options" => array(
        "1" => esc_html__("Redirect to a custom URL", "mfe-send-fox-extension"),
        "2" => esc_html__("Display a message", "mfe-send-fox-extension"),
      ),
      "default" => "1",
      'option_category' => 'basic_option',
      'description' => esc_html__('Choose what happens when a site visitor has been successfully subscribed to your list.', 'mfe-send-fox-extension'),
      'toggle_slug' => 'email',
    ];
    
    $fields["redirect"] = [
			"label" => esc_html__("Redirect URL", 'mfe-send-fox-extension'),
      "type" => "text",
      'default' => '',
      'option_category' => 'basic_option',
			'description'     => esc_html__( 'URL to redirect contacts after they submit', 'mfe-send-fox-extension' ),
      'toggle_slug'     => 'email',
      "show_if" => [
				"after_submit" => ["1"],
			],
    ];

    $fields["show_message"] = [
			"label" => esc_html__("Message", 'et_builder'),
      "type" => "text",
      'default' => '',
      'option_category' => 'basic_option',
			'description'     => esc_html__( 'The message that will be shown to site visitors who subscribe to your list.', 'mfe-send-fox-extension' ),
      'toggle_slug'     => 'email',
      "show_if" => [
				"after_submit" => ["2"],
			],
    ];

    $fields['check_name'] = [
      'label' => esc_html__('Show first name Field', 'mfe-send-fox-extension'),
      'type' => 'yes_no_button',
      'option_category' => 'basic_option',
      'description' => esc_html__('Whether or not the First Name field should be included in the opt-in form.', 'simp-simple-extension'),
      'toggle_slug' => 'fields',
      'options' => array(
        'on'  => esc_html__( 'Yes', 'mfe-send-fox-extension'),
        'off' => esc_html__( 'No', 'mfe-send-fox-extension'),
      ),
      'default' => 'on',
    ];
    
    $fields["label_name"] = [
			"label" => esc_html__("First name label", 'mfe-send-fox-extension'),
			"type" => "text",
      'description'     => esc_html__( 'Define custom text for the First Name label.', 'mfe-send-fox-extension' ),
      'default' => esc_html__( 'First Name', 'et_builder' ),
      'toggle_slug'     => 'fields',
      "show_if" => [
				"check_name" => ["on"],
			],
    ];

    $fields['check_last_name'] = [
      'label' => esc_html__('Show last name Field', 'mfe-send-fox-extension'),
      'type' => 'yes_no_button',
      'option_category' => 'basic_option',
      'description' => esc_html__('Whether or not the Last Name field should be included in the opt-in form.', 'simp-simple-extension'),
      'toggle_slug' => 'fields',
      'options' => array(
        'on'  => esc_html__( 'Yes', 'mfe-send-fox-extension'),
        'off' => esc_html__( 'No', 'mfe-send-fox-extension'),
      ),
      'default' => 'on',
    ];
    
    $fields["label_last_name"] = [
			"label" => esc_html__("Last name label", 'mfe-send-fox-extension'),
			"type" => "text",
      'description'     => esc_html__( 'Define custom text for the Last Name label.', 'mfe-send-fox-extension' ),
      "default" => esc_html__( 'Last Name', 'et_builder' ),
      'toggle_slug'     => 'fields',
      "show_if" => [
				"check_last_name" => ["on"],
			],
    ];

    $fields["email"] = [
			"label" => esc_html__("Email label", 'mfe-send-fox-extension'),
			"type" => "text",
      'description'     => esc_html__( 'By default Email', 'mfe-send-fox-extension' ),
      "default" => esc_html__( 'Email', 'et_builder' ),
			'toggle_slug'     => 'fields',
    ];
    

    //==========================================
    //Title
    //==========================================
    $fields["title_color"] = [
      "label"           => esc_html__("Color", 'mfe-send-fox-extension'),
      "type"            => "color",
      "tab_slug"        => "advanced",
      'toggle_slug'     => 'tab_title',
    ];

    
    $fields['title_text_align'] = [
      'label'           => esc_html__( 'Text Align', 'mfe-send-fox-extension' ),
      'type'            => 'text_align',
      'options'         => et_builder_get_text_orientation_options(),
      'tab_slug'        => "advanced",
      'toggle_slug'     => 'tab_title',
    ];
    

    $fields['title_select_font'] = [
      'label'           => esc_html__( 'Select Font', 'mfe-send-fox-extension' ),
      'type'            => 'font',
      'tab_slug'        => 'advanced',
      'toggle_slug'     => 'tab_title',
    ];

    $fields['title_input_margin'] = [
      'label'           => esc_html__( 'Margin', 'mfe-send-fox-extension' ),
      'type'            => 'custom_margin',
      'tab_slug'        => 'advanced',
      'toggle_slug'     => 'tab_title',
    ];

    //==========================================
    //Body
    //==========================================
    $fields["body_color"] = [
      "label"           => esc_html__("Color", 'mfe-send-fox-extension'),
      "type"            => "color",
      "tab_slug"        => "advanced",
      'toggle_slug'     => 'tab_body',
    ];

    
    $fields['body_text_align'] = [
      'label'           => esc_html__( 'Text Align', 'mfe-send-fox-extension' ),
      'type'            => 'text_align',
      'options'         => et_builder_get_text_orientation_options(),
      'tab_slug'        => "advanced",
      'toggle_slug'     => 'tab_body',
    ];
    

    $fields['body_select_font'] = [
      'label'           => esc_html__( 'Select Font', 'mfe-send-fox-extension' ),
      'type'            => 'font',
      'tab_slug'        => 'advanced',
      'toggle_slug'     => 'tab_body',
    ];

    $fields['body_input_margin'] = [
      'label'           => esc_html__( 'Margin', 'mfe-send-fox-extension' ),
      'type'            => 'custom_margin',
      'tab_slug'        => 'advanced',
      'toggle_slug'     => 'tab_body',
    ];

    //==========================================
    //Button
    //==========================================

    $fields["send"] = [
			"label" => esc_html__("Submit button label", 'mfe-send-fox-extension'),
			"type" => "text",
			'option_category' => 'basic_option',
      'description'     => esc_html__( 'Define custom text for the subscribe button.', 'mfe-send-fox-extension' ),
      "default" => esc_html__( 'Subscribe', 'et_builder' ),
			'toggle_slug'     => 'button',
    ];


    //==========================================
    //layuot
    //==========================================
    $fields["layout"] = [
      'label'       => esc_html__( 'Layout', 'et_builder' ),
      'description' => esc_html__( 'Choose where you would like the input fields to appear in relation to the body text and title text.', 'et_builder' ),
      'type'        => 'select',
      'options'     => array(
        'left_right' => esc_html__( 'Body On Left, Form On Right', 'et_builder' ),
        'right_left' => esc_html__( 'Body On Right, Form On Left', 'et_builder' ),
        'top_bottom' => esc_html__( 'Body On Top, Form On Bottom', 'et_builder' ),
        'bottom_top' => esc_html__( 'Body On Bottom, Form On Top', 'et_builder' ),
      ),
      'default'     => 'left_right',
      'tab_slug'    => 'advanced',
      'toggle_slug' => 'layout',
    ];

    $fields["first_name_fullwidth"]  = [
      'label'           => esc_html__( 'First Name Fullwidth', 'et_builder' ),
      'description'     => esc_html__( 'Enabling this will extend the input field to 100% of the width of the module.', 'et_builder' ),
      'type'            => 'yes_no_button',
      'option_category' => 'configuration',
      'options'         => array(
        'on'  => esc_html__( 'Yes', 'et_builder' ),
        'off' => esc_html__( 'No', 'et_builder' ),
      ),
      'default'         => 'on',
      'toggle_slug'     => 'layout',
      'tab_slug'        => 'advanced',
      'show_if'         => array(
        'check_name' => 'on',
      ),
      'mobile_options'  => true,
    ];

    $fields["last_name_fullwidth"] = [
      'label'           => esc_html__( 'Last Name Fullwidth', 'et_builder' ),
      'description'     => esc_html__( 'Enabling this will extend the input field to 100% of the width of the module.', 'et_builder' ),
      'type'            => 'yes_no_button',
      'option_category' => 'configuration',
      'options'         => array(
        'on'  => esc_html__( 'Yes', 'et_builder' ),
        'off' => esc_html__( 'No', 'et_builder' ),
      ),
      'default'         => 'on',
      'toggle_slug'     => 'layout',
      'tab_slug'        => 'advanced',
      'show_if' => array(
        'check_last_name' => 'on',
      ),
      'mobile_options'  => true,
    ];

    $fields["email_fullwidth"]   = [
      'label'           => esc_html__( 'Email Fullwidth', 'et_builder' ),
      'description'     => esc_html__( 'Enabling this will extend the input field to 100% of the width of the module.', 'et_builder' ),
      'type'            => 'yes_no_button',
      'option_category' => 'configuration',
      'options'         => array(
        'on'  => esc_html__( 'Yes', 'et_builder' ),
        'off' => esc_html__( 'No', 'et_builder' ),
      ),
      'default'         => 'on',
      'toggle_slug'     => 'layout',
      'tab_slug'        => 'advanced',
      'mobile_options'  => true,
    ];
    


		return $fields;
  }

  function get_advanced_fields_config() {
		// Advanced fields
		// The goal of advanced fields are to reduce repetitiveness of field definition. Many modules
		// use the same set of fields with slight differences (i.e.: most modules have box shadow
		// fields but Blurb has additional box-shadow fields for its image) so advanced fields
		// enables module to declare minimum variable to auto generate commonly used fields.

		// NOTE:
		// IF MODULE HAS PARTIAL OR FULL BUILDER SUPPORT, ALL ADVANCED OPTIONS (EXCEPT BUTTON) ARE ADDED BY DEFAULT
		$advanced_fields = array();

		// The following advanced fields are automatically added regardless builder support or explicit definition
		// Tabs     | Toggles          | Fields
		// --------- ------------------ -------------
		// Design   | Border           | Rounded Corners (multiple fields)
		// Design   | Border           | Border Styles (multiple fields)
		// Design   | Box Shadow       | Box Shadow (multiple fields)
		// Design   | Animation        | Animation (multiple fields)

		// Note: "// default" comment after the configuration attribute means that Divi automatically
		// adds this value. The attribute can be left undeclared if you want to use default value

		// Add advanced fields: module background
		// There can only be one module background so its setting is as minimal as possible.
		// The location of the background is at Content > Background > Background
		$advanced_fields['background'] = array(
			'has_background_color_toggle'   => false, // default. Warning: to be deprecated
			'use_background_color'          => true, // default
			'use_background_color_gradient' => true, // default
			'use_background_image'          => true, // default
			'use_background_video'          => true, // default
		);

		// Add advanced fields: fonts
		// There can be multiple advanced font options in a module, so it is designed to accept
		// multiple advanced fields
		// Adding very basic font options
    
    $advanced_fields['fonts']  = array(
      'header'         => array(
        'label'        => esc_html__( 'Title', 'et_builder' ),
        'css'          => array(    
          'main'      => "{$this->main_css_element} .lsf_info_title",           
          'important' => 'all',
        ),
      ),
      'body'           => array(
        'label' => esc_html__( 'Body', 'et_builder' ),
        'css'   => array(
          'main'        => "{$this->main_css_element} .lsf_info_body, {$this->main_css_element} .sendfox-form",
          'line_height' => "{$this->main_css_element} p",
        ),
        'block_elements' => array(
          'tabbed_subtoggles' => true,
          'bb_icons_support'  => true,
          'css'               => array(
            'link'           => "{$this->main_css_element} .lsf_info_body a, {$this->main_css_element} .sendfox-form a",
            'ul'             => "{$this->main_css_element} .lsf_info_body ul li, {$this->main_css_element} .sendfox-form ul li",
            'ul_item_indent' => "{$this->main_css_element} .lsf_info_body ul, {$this->main_css_element} .sendfox-form ul",
            'ol'             => "{$this->main_css_element} .lsf_info_body ol li, {$this->main_css_element} .sendfox-form ol li",
            'ol_item_indent' => "{$this->main_css_element} .lsf_info_body ol, {$this->main_css_element} .sendfox-form ol",
            'quote'          => "{$this->main_css_element} .lsf_info_body blockquote, {$this->main_css_element} .sendfox-form blockquote",
          ),
        ),
      ),
      'result_message' => array(
        'label' => esc_html__( 'Result Message', 'et_builder' ),
        'css'   => array(
          'main' => "{$this->main_css_element} .lsf_newsletter_result",
        ),
      ),
      
    );
    

		// Add advanced fields: border (radius & style)
		// Most module has border, thus it is automatically added even without explicit definition
		// However, some module might have multiple border (ie. blurb which has module and image
		// border options) or slightly different border configuration
		$advanced_fields['borders'] = array(
			'default' => array(), // default
		);

		// Add advanced fields: text
		// Automatically add text orientation field (left|center|right|justified) to advanced tab.
		// text_orientation are commonly not printing anything; the attribute is used to outputs
		// text-align affecting class name. To manually output CSS styling, `css` attribute containing
		// `text` orientation and valid selector template needs to be declared
    
    
    $advanced_fields['text'] = array(
        'use_text_orientation'  => true, // default
        
        'use_background_layout' => true,
        
        'css' => array(
          'text_orientation' => '%%order_class%%',
        ),
        'options'               => array(
          'text_orientation'  => array(
            'default' => 'left',
          ),
          'background_layout' => array(
            'label' => esc_html__( 'Text Color', 'et_builder' ),
            'type' => 'select',
            'option_category' => 'color_option',
            'options' => array(
              'light' => esc_html__( 'Light', 'et_builder' ),
              'dark' => esc_html__( 'Dark', 'et_builder' ),
            ),
            'css'   => array(
              'main' => '%%order_class%% .lsf_info_body p, %%order_class%% .lsf_info_title',
            ),
          )
      ),
		);;

		// Add advanced fields: Max Width (sizing)
		// This advanced fields automatically adds Width and Module Alignment (responsive) fields
		// on Design > Sizing toggle. Module Alignment only appears if Width value isn't (100%)
		// because Module Alignment is irrelvant if the module widht fills its entire wrapper
		$advanced_fields['max_width'] = array(
			'use_max_width'        => true, // default
			'use_module_alignment' => true, // default
		);

		// Add advanced fields: margin & padding
		// Adding advanced fields automatically adds Margin and Padding fields on Design > Spacing
		// Module is expected to have max one margin and padding option so the only option this
		// advanced field has is either to activate / deactivate margin / padding options
		$advanced_fields['margin_padding'] = array(
			'use_margin'  => true,
			'use_padding' => true,
		);

		// Add advanced fields: button
		// Similar to advanced font options, there can be multiple advanced button options in a
		// module (ie. Fullwidth Header module), so it is designed to accept multiple advanced
		// options and requires module to at least explicitly set one setting

		// NOTE:
		// Button fields are not automatically added even if the module has builder support
		$advanced_fields['button'] = array(
			'button' => array(
				'label' => esc_html__( 'Button', 'et_builder' ),
				'css'   => array(
          'alignment'   => "%%order_class%% .et_pb_button_wrapper",
          'main' => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
					'limited_main' => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
        ),
        'box_shadow' => array(
          'css' => array(
            'main' => '%%order_class%% .et_pb_button_wrapper .et_pb_button',
          ),
        ),
        'margin_padding' => array(
          'css' => array(
            'important' => 'all',
          ),
        ),
			),
		);

		// Add advanced fields: filter
		// Adding CSS-based color filter options to the module. CSS filter is pre-deterministic:
		// It is assumed that module can only have maximum two filters advanced fields at the same
		// time so there's no flexibility in terms of attribute naming (unlike font and button options)


		// Add advanced fields: animation
		// Advanced animation options is automatically added to all module except to module item
		// It doesn't have many option except to have it disabled (uncomment the line below to try it)
		// $advanced_fields['animation'] = false;

		// Add advanced fields: text shadow
		// Text shadow option is automatically added when advanced_options property is defined.
		// Module normally only defined one advanced advanced text shadow fields but it accepts
		// parameter to define additional text shadow options
		$advanced_fields['text_shadow'] = array(
			'default' => array(), // default
    );


    //New doing tests
    $advanced_fields['form_field'] = array(
      'form_field' => array(
        'label'         => esc_html__( 'Fields', 'et_builder' ),
        'css'           => array(
          'main'                   => '%%order_class%% .sendfox-form p .input',
          'background_color'       => '%%order_class%% .sendfox-form p input[type="text"], %%order_class%% .sendfox-form p textarea, %%order_class%% .sendfox-form p select, %%order_class%% .sendfox-form p .input[type="checkbox"] + label i, %%order_class%% .sendfox-form p .input[type="radio"] + label i',
          'background_color_hover' => '%%order_class%% .sendfox-form p input[type="text"]:hover, %%order_class%% .sendfox-form p textarea:hover, %%order_class%% .sendfox-form p select:hover, %%order_class%% .sendfox-form p .input[type="checkbox"] + label:hover i, %%order_class%% .sendfox-form p .input[type="radio"] + label:hover i',
          'focus_background_color' => '%%order_class%% .sendfox-form p input.input:focus, %%order_class%% .sendfox-form p textarea:focus, %%order_class%% .sendfox-form p select:focus',
          'form_text_color'        => '%%order_class%% .sendfox-form p input[type="text"], %%order_class%% .sendfox-form p textarea, %%order_class%% .sendfox-form p select, %%order_class%% .sendfox-form p .input[type="checkbox"] + label i:before',
          'form_text_color_hover'  => '%%order_class%% .sendfox-form p input[type="text"]:hover, %%order_class%% .sendfox-form p textarea:hover, %%order_class%% .sendfox-form p select:hover, %%order_class%% .sendfox-form p .input[type="checkbox"] + label:hover i:before',
          'focus_text_color'       => '%%order_class%% .sendfox-form p .input:focus',
          'placeholder_focus'      => '%%order_class%% .sendfox-form p .input:focus::-webkit-input-placeholder, %%order_class%% .sendfox-form p .input:focus::-moz-placeholder, %%order_class%% .sendfox-form p .input:focus:-ms-input-placeholder, %%order_class%% .sendfox-form p textarea:focus::-webkit-input-placeholder, %%order_class%% .sendfox-form p textarea:focus::-moz-placeholder, %%order_class%% .sendfox-form p textarea:focus:-ms-input-placeholder',
          'important'              => array( 'form_text_color' ),
        ),
        'box_shadow'    => array(
          'name'              => 'fields',
          'css'               => array(
            'main' => '%%order_class%% .sendfox-form .input',
          ),
          'default_on_fronts' => array(
            'color'    => '',
            'position' => '',
          ),
        ),
        'border_styles'  => array(
          'form_field'       => array(
            'name'         => 'fields',
            'css'          => array(
              'main' => array(
                'border_radii'  => '%%order_class%% .sendfox-form p input[type="text"], %%order_class%% .sendfox-form p textarea, %%order_class%% .sendfox-form p select, %%order_class%% .sendfox-form p .input[type="radio"] + label i, %%order_class%% .sendfox-form p .input[type="checkbox"] + label i',
                'border_styles' => '%%order_class%% .sendfox-form p input[type="text"], %%order_class%% .sendfox-form p textarea, %%order_class%% .sendfox-form p select, %%order_class%% .sendfox-form p .input[type="radio"] + label i, %%order_class%% .sendfox-form p .input[type="checkbox"] + label i',
              ),
            ),
            'label_prefix' => esc_html__( 'Fields', 'et_builder' ),
          ),
          'form_field_focus' => array(
            'name'         => 'fields_focus',
            'css'          => array(
              'main' => array(
                'border_radii'  => '%%order_class%% .sendfox-form p input[type="text"]:focus',
                'border_styles' => '%%order_class%% .sendfox-form p input[type="text"]:focus',
              ),
            ),
            'label_prefix' => esc_html__( 'Fields Focus', 'et_builder' ),
          ),
        ),
        'font_field'     => array(
          'css' => array(
            'main'      => array(
              '%%order_class%%.et_pb_contact_field .et_pb_contact_field_options_title',
              "{$this->main_css_element} .sendfox-form .input",
              "{$this->main_css_element} .sendfox-form .input::-webkit-input-placeholder",
              "{$this->main_css_element} .sendfox-form .input::-moz-placeholder",
              "{$this->main_css_element} .sendfox-form .input:-ms-input-placeholder",
              "{$this->main_css_element} .sendfox-form .input[type=checkbox] + label",
              "{$this->main_css_element} .sendfox-form .input[type=radio] + label",
            ),
            'hover'     => array(
              '%%order_class%%.et_pb_contact_field .et_pb_contact_field_options_title:hover',
              "{$this->main_css_element} .sendfox-form .input:hover",
              "{$this->main_css_element} .sendfox-form .input:hover::-webkit-input-placeholder",
              "{$this->main_css_element} .sendfox-form .input:hover::-moz-placeholder",
              "{$this->main_css_element} .sendfox-form .input:hover:-ms-input-placeholder",
              "{$this->main_css_element} .sendfox-form .input[type=checkbox] + label:hover",
              "{$this->main_css_element} .sendfox-form .input[type=radio] + label:hover",
            ),
            'important' => 'plugin_only',
          ),
        ),
        'margin_padding' => array(
          'css' => array(
            'main'      => '%%order_class%% .sendfox-form p.et_pb_sendfox_field',
            'padding'   => '%%order_class%% .sendfox-form .input, %%order_class%% .sendfox-form input[type="text"], %%order_class%% .sendfox-form p.et_pb_sendfox_field input[type="text"], %%order_class%% .sendfox-form textarea, %%order_class%% .sendfox-form p.et_pb_sendfox_field textarea, %%order_class%% .sendfox-form select',
            'important' => array( 'custom_padding' ),
          ),
        ),
      ),
    );

		return $advanced_fields;
	}


	public function render( $attrs, $content = null, $render_slug ) {

    //Get parameters
    $options = get_option( 'sfdivi_options' );

    

    $isErrorSendFox = false;
    
    //$options = get_option( 'sfdivi_options' );
    if( !empty( $options['api_key'] ) )
    {
      $myToken = $options['api_key'];
    }


      $endpoint = 'lists';
      $method = "GET";
      $data = array();

      $response = $this->sffdivi_api_request( $myToken, $endpoint = $endpoint, $data = $data, $method = $method );
      $this->sffdivi_api_response( $response );

      if( $response["error"] != "")
      {
        $isErrorSendFox = $response["error_text"];
      }

      

      $urlRedirect = trim($this->props["redirect"]);
      if( $this->props["after_submit"] == 1 ) //Redirect
      {
        if( $urlRedirect == ""  )
        {
          $urlRedirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
      }

      $showMessage = et_builder_convert_line_breaks( html_entity_decode($this->props["show_message"]) ) ; 
      if( $showMessage == "" )
      {
        $showMessage = esc_html__("Success!");
      }

      
    $title = et_builder_convert_line_breaks( html_entity_decode($this->props["title"]) ) ;

    $body = et_builder_convert_line_breaks( html_entity_decode($this->props["body"]) ) ;
    
    if( !$this->props["background_color"] )
    {
      $bgClass = "holderSendFoxBg";
    }else{
      $bgClass = "holderSendFoxColor";
    }

    if( $isErrorSendFox )
    {
      $form = "<div class='holderSendFox  ".$bgClass." '><p> Error SendFox Connection: ".$isErrorSendFox."</p></div>";
    }
    else
    {
      $form = ""; 

      $button_text           = $this->props['send'];
      $button_url            = "";
      $button_url_new_window = "";

      // Design related props are added via $this->advanced_options['button']['button']
      $button_custom         = $this->props['custom_button'];
      $button_rel            = $this->props['button_rel'];
      $button_use_icon       = $this->props['button_use_icon'];

      

    

        $custom_icon_values = et_pb_responsive_options()->get_property_values( $this->props, 'button_icon' );
				$custom_icon        = isset( $custom_icon_values['desktop'] ) ? $custom_icon_values['desktop'] : '';
				$custom_icon_tablet = isset( $custom_icon_values['tablet'] ) ? $custom_icon_values['tablet'] : '';
				$custom_icon_phone  = isset( $custom_icon_values['phone'] ) ? $custom_icon_values['phone'] : '';

				$button_icon        = $custom_icon && 'on' === $this->props['custom_button'];
				$button_icon_tablet = $custom_icon_tablet && 'on' === $this->props['custom_button'];
				$button_icon_phone  = $custom_icon_phone && 'on' === $this->props['custom_button'];

				$button_rel = $this->props['button_rel'];
				$icon_class = $button_icon || $button_icon_tablet || $button_icon_phone ? ' et_pb_custom_button_icon' : '';

				$icon_attr        = $button_icon ? et_pb_process_font_icon( $custom_icon ) : '';
				$icon_attr_tablet = $button_icon_tablet ? et_pb_process_font_icon( $custom_icon_tablet ) : '';
				$icon_attr_phone  = $button_icon_phone ? et_pb_process_font_icon( $custom_icon_phone ) : '';



      // Render button
      $button = $this->render_button( array(
        'button_text'      => $button_text,
        'button_url'       => $button_url,
        'url_new_window'   => $button_url_new_window,
        'button_custom'    => $button_custom,
        'button_rel'       => $button_rel,
        'custom_icon'      => $icon_attr
      ) );


      $form .= "<div class='holderSendFox  ".$bgClass." ' id='lsf_holder_form'>";


        $directionLayout = $this->props['layout'];

        $orderLayoutLeft = "";
        $orderLayoutRight = "";
        if( $directionLayout == "right_left" )
        {
          $orderLayoutLeft = "directionRF";
        }else if( $directionLayout == "top_bottom" )
        {
          $orderLayoutLeft = " fullwidth ";
          $orderLayoutRight = " fullwidth ";
        }
        else if( $directionLayout == "bottom_top" )
        {
          $orderLayoutLeft = " directionRF fullwidth ";
          $orderLayoutRight = " fullwidth ";
        }

        $classHolderLeft = " lsf_left lsf_left_description ".$orderLayoutLeft;
        $classHolderRight = " lsf_right ".$orderLayoutRight;


        $form .= "<div class='".$classHolderLeft."'>";
        $form .= "<div id='lsf_title' class='lsf_info_title ".$this->props['background_layout']."'>".$title."</div>";
        $form .= "<div id='lsf_body ' class='lsf_info_body ".$this->props['background_layout']."'>".$body."</div></div>";

        $form .= "<div class='".$classHolderRight."'>";
        
        $resolveCaptcha = "";
        if( $options['enableReCaptcha'] == 1 && $options[ 'sitekey' ] != "" && $options[ 'secretkey' ] != "" )
        {
          $resolveCaptcha = "resolveCaptcha";
        }
        $form .= "<form method='post' action='#' class='sendfox-form ".$resolveCaptcha." ".$this->props['background_layout']."' id='sendfox-form' data-async='true'>";
        $form .= "<div class='holderFlex'>";
          if( $this->props['check_name'] == 'on' )
          {
            $haftName = "";
            if( $this->props['first_name_fullwidth'] == "off" )
            {
              $haftName = "et_pb_sendfox_field_half";
            }
            $form .= "<p class='et_pb_sendfox_field ".$haftName."'><input type='text' class='lsf_fields_bg lsf_fields_color input' placeholder='".$this->props["label_name"]."' data-lsfname='".$this->props["label_name"]."' id='lsf_first_name' name='first_name' required /></p>";
          }

          if( $this->props['check_last_name'] == 'on' )
          {
            $haftLastName = "";
            if( $this->props['last_name_fullwidth'] == "off" )
            {
              $haftLastName = "et_pb_sendfox_field_half";
            }
            $form .= "<p class='et_pb_sendfox_field ".$haftLastName."'><input type='text' class='lsf_fields_bg lsf_fields_color input' placeholder='".$this->props["label_last_name"]."' data-lsflastname='".$this->props["label_last_name"]."' id='lsf_last_name' name='last_name' required /></p>";
          }

          $haftEmail = "";
          if( $this->props['email_fullwidth'] == "off" )
          {
            $haftEmail = "et_pb_sendfox_field_half";
          }
          
          $form .= "<p class='et_pb_sendfox_field ".$haftEmail."'><input type='text' class='lsf_fields_bg lsf_fields_color input' placeholder='".$this->props["email"]."' data-lsfemail='".$this->props["email"]."' id='lsf_email' name='email' required /></p>";
          $form .= "<input type='hidden' id='lst_lists' value='".$this->props["list"]."'>";
          $form .= "<input type='hidden' id='lst_after_submit' value='".$this->props["after_submit"]."'>";
          $form .= "<input type='hidden' id='lst_redirect' value='".$urlRedirect."'>";
        $form .= "</div>";
        $form .= "".et_sanitized_previously( $button )."";

        
        if( $options['enableReCaptcha'] == 1 && $options[ 'sitekey' ] != "" )
        {
          
          $form .= "<div style='padding:2vh 0px' class='g-recaptcha' data-sitekey='".$options[ 'sitekey' ]."' data-callback='onReCaptcha'></div>";
        }

        
        $form .= "</form>";
        $form .= "<div id='lsf_holder_msgbox'><div class='lsf_newsletter_result'>".$showMessage."</div></div>";
        $form .= "<div id='lsf_holder_loading'><div class='lsf_spinner'>
                    <div class='rect1'></div>
                    <div class='rect2'></div>
                    <div class='rect3'></div>
                    <div class='rect4'></div>
                    <div class='rect5'></div>
              </div></div>";
        $form .= "</div>";
        $form .= "<div class='lsf_clear'></div>";
      $form .= "</div>";


      


      $form .= "<script>
                //const myForm = document.getElementById('sendfox-form');
                var myBtn = document.getElementById('lsf_holder_form').querySelector('.et_pb_button');
                myBtn.classList.remove('et_pb_custom_button_icon');
               </script>";

      if( $options['enableReCaptcha'] == 1 && $options[ 'sitekey' ] != "" && $options[ 'secretkey' ] != "")
      {     
        $form .= "<script>
               var onReCaptcha = function () {
                 if (grecaptcha.getResponse().length !== 0) {
                   var btSubmit = document.getElementById('sendfox-form');
                   btSubmit.classList.remove('resolveCaptcha');
                 }
               };
             </script>
             <script src='https://www.google.com/recaptcha/api.js' async defer></script>"; 
      }   
      
    } 
    return $form;
  }

  
  
  public function sffdivi_api_request( $mytoken, $endpoint = 'me', $data = array(), $method = 'GET' )
  {
      $result = FALSE;

      $base = 'https://api.sendfox.com/';

      $options['api_key'] = $mytoken;

      if( empty( $options['api_key'] ) )
      {
          $result = array(
              'status'     => 'error',
              'error'      => 'empty_api_key',
              'error_text' => __( 'API Key is not set.', 'sf4wp' ),
          );

          return $result;
    }
    
    //https://api.sendfox.com/
    $args = apply_filters( 'mfe_send_fox_request_data', $data, $endpoint, $method );
    
    $args = array( 
      'body' => $args,        
    );
    

    $args['headers'] = array(
      'Authorization' => 'Bearer ' . $options['api_key'],
    );

    $args['method']  = $method;
    $args['timeout'] = 30;
    

    $result = wp_remote_request( $base . $endpoint, $args );


    $this->sffdivi_logs(
          array(
              '_time' => date( 'H:i:s d.m.Y' ),
              'event' => 'API_REQUEST',
              'endpoint' => $base . $endpoint,
              'args' => $args,
              'response_raw' => $result,
          )
    );
    

    if( !is_wp_error( $result ) && ( $result['response']['code'] == 200 || $result['response']['code'] == 201   ) )
    {
          $result = wp_remote_retrieve_body( $result );

          $result = json_decode( $result, TRUE );

          

          if( !empty( $result ) )
          {
              $result = array(
                  'status'     => 'success',
                  'result'     => $result,
                  'error'      => null,
              );
          }
          else
          {
              $result = array(
                  'status'     => 'error',
                  'error'      => 'json_parse_error',
                  'error_text' => __( 'JSON Parse', 'sf4wp' ),
              );
          }
    }
    else // if WP_Error happened
    {
        if( is_object( $result ) )
        {
            $result = array(
                  'status'     => 'error',
                  'error'      => 'request_error',
                  'error_text' => $result->get_error_message(),
            );
        }
        else
        {
            $result = wp_remote_retrieve_body( $result );

            $result = array(
                  'status'     => 'error',
                  'error'      => 'request_error',
                  'error_text' => $result,
            );
        }
    }

    return $result;
  }

  public function sffdivi_logs( $data = array(), $file = 'debug.log', $force = FALSE )
  {
      if( empty( $file ) )
      {
          $file = 'debug.log';
      }

      if( !empty( $data ) )
      {
          $options = get_option( 'sfdivi_options' );

          if( empty( $options['enable_log'] ) && $force === FALSE )
          {
              return;
          }

          if( empty( $data['_time'] ) )
          {
              $data[ '_time' ] = date( 'H:i:s d.m.y' );
          }

          $data = json_encode( $data );

          if( !empty( $options['api_key'] ) )
          {
              $data = str_replace( $options['api_key'], '###_API_KEY_REMOVED_###', $data );
          }

          $data = $data . PHP_EOL . PHP_EOL;

          return file_put_contents( dirname( __FILE__ ) . '/' . $file, $data, FILE_APPEND );
      }
  }

  public function sffdivi_api_response( $response = array() )
  {
    $result = array(
          'status'     => 'error',
          'error'      => 'status_error',
          'error_text' => __( 'Error: Response Status', 'sf4wp' ),
    );

    if( !empty( $response['status'] ) )
    {
      $result = $response;
    }

    return $result;
  }
}

new MFE_SendFox;
