<?php
/*
Plugin Name:  LikeablePress Integration of SendFox for Divi
Plugin URI:   https://likeablepress.com/plugins/sendfox-for-divi/?utm_source=sendfoxplugin&utm_medium=wporg&utm_campaign=pluginuri
Description:  SendFox for Divi by LikeablePress gives you full design control over your SendFox Opt-In forms. Design your forms in real time and see the results instantly.
Version:      1.0.1
Author:       LikeablePress
Author URI:   https://likeablepress.com/?utm_source=sendfoxplugin&utm_medium=wporg&utm_campaign=authoruri
Requires at least: 4.5
Requires PHP: 5.6
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  likeablepress-sendFox-for-divi
Domain Path:  /languages

SendFox for Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

SendFox for Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Cooked. If not, see http://www.gnu.org/licenses/.
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'sffdivi_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function sffdivi_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/SenfoxExtension.php';
}
add_action( 'divi_extensions_init', 'sffdivi_initialize_extension' );
endif;


//Add Additional Links To The WordPress Plugin Admin
function sffdivi_append_support_and_faq_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
  if ( strpos( $plugin_file_name, basename(__FILE__) ) ) {

      // You can still use `array_unshift()` to add links at the beginning.
      $links_array[] = "<a href='https://likeablepress.com/plugins/sendfox-for-divi/sendfox-help/?utm_source=sendfoxplugin&utm_medium=wpplugindash&utm_campaign=help' target='_blank'>".__('Get Help','sf4wp')."</a>";
      $links_array[] = "<a href='https://likeablepress.com/plugins/sendfox-for-divi/documentation/?utm_source=sendfoxplugin&utm_medium=wpplugindash&utm_campaign=docs' target='_blank'>".__('Documentation','sf4wp')."</a>";
      $links_array[] = "<a href='https://review.likeable.link/collect' target='_blank'>".__('Rate this plugin','sf4wp')."</a>";
  }

  return $links_array;
}

add_filter( 'plugin_row_meta', 'sffdivi_append_support_and_faq_links', 10, 4 );


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sffdivi_link_action_on_plugin' );
/**
 * add links to the left side menu on the plugin screen.
 *
 * @see 	http://www.paulund.co.uk/add-additional-links-to-the-wordpress-plugin-admin
 */
function sffdivi_link_action_on_plugin( $links ) {
	return array_merge( array(
            'premium support' => '<a href="https://likeablepress.com/plugins/sendfox-for-divi/sendfox-help/?utm_source=sendfoxplugin&utm_medium=wpplugindash&utm_campaign=help" target="_blank">' . __( 'Premium Support', 'sf4wp' ) . '</a>',
            'settings' => '<a href="' . admin_url( '/admin.php?page=sendfox-for-divi' ) . '">' . __( 'Settings', 'sf4wp' ) . '</a>')

    , $links);
}



$arrList = array();

add_action("wp_ajax_my_first_ajax_action","sffdivi_ajax_function");

function sffdivi_ajax_function()
{
	$post_id = sanitize_key($_POST["post_id"]);
  $title = get_the_title($post_id);
  $mytoken = sanitize_text_field($_POST["myToken"]);
  $test = sanitize_text_field($_POST["test"]);

  $arrList = array();
  if( $mytoken !="" )
  {
    $endpoint = 'lists';
    $method = "GET";
    $data = array();

      $resList = sffdivi_api_request( $mytoken, $endpoint = $endpoint, $data = $data, $method = $method );

      $info = ($resList["result"]["data"]);

      if( count($info) > 0 )
      {

        if( count($info) > 0 )
        {
          foreach($info as $item)
          {
            $arrList[] = array('id'=>$item["id"], "title"=>$item["name"]);
          }
        }
      }
  }

	$result = [
		'title' => $title,
    "test" => $test,
    "distroList" => $arrList,
	];

	wp_send_json($result);
}


add_filter( 'sffdivi_api_request_filter', 'sffdivi_api_request', 10, 3);
function sffdivi_api_request( $mytoken, $endpoint = 'me', $data = array(), $method = 'GET' )
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

    sffdivi_logs(
          array(
              '_time' => date( 'H:i:s d.m.Y' ),
              'event' => 'API_REQUEST',
              'endpoint' => $base . $endpoint,
              'args' => $args,
              'response_raw' => $result,
          )
    );


      if(
          !is_wp_error( $result ) &&
          ( $result['response']['code'] == 200 || $result['response']['code'] == 201   ) || 1
      )
      {
          $result = wp_remote_retrieve_body( $result );

          $result = json_decode( $result, TRUE );

          if( !empty( $result ) )
          {
              $result = array(
                  'status'     => 'success',
                  'result'     => $result,
              );
          }
          else
          {
              $result = array(
                  'status'     => 'error',
                  'error'      => 'json_parse_error',
                  'error_text x' => __( 'JSON Parse', 'sf4wp' ),
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

add_filter( 'sffdivi_logs_filter', 'sffdivi_logs',10, 3);
function sffdivi_logs( $data = array(), $file = 'debug.log', $force = FALSE )
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

          // remove api_key from logs

          if( !empty( $options['api_key'] ) )
          {
              $data = str_replace( $options['api_key'], '###_API_KEY_REMOVED_###', $data );
          }

          $data = $data . PHP_EOL . PHP_EOL;

          return file_put_contents( dirname( __FILE__ ) . '/' . $file, $data, FILE_APPEND );
      }
}

add_filter( 'sffdivi_api_response_filter', 'sffdivi_api_response',10, 3);
function sffdivi_api_response( $response = array() )
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


add_filter( 'sf_list_filter', 'sffdivi_list_callback', 10, 3 );

function sffdivi_list_callback( $myToken ) {

  $endpoint = 'lists';
  $method = "GET";
  $data = array();

  $result = sffdivi_api_request($myToken, $endpoint = $endpoint, $data = $data, $method = $method );

  $arrList = array();
  if( count($result) > 0 )
  {
    $data = $result["result"]["data"];

    if( count($data) > 0 )
    {
      foreach($data as $item)
      {
        $arrList[$item["id"]] = $item["name"];
      }
    }
  }

  return $arrList;
}




define( 'SFFDIVI_GB_NAME', 'SendFox for Divi' );
define( 'SFFDIVI_GB_VERSION', '1.0' );
define( 'SFFDIVI_GB_ID', 'sendfox-for-divi' );

define( 'SFFDIVI_GB_FILE', __FILE__ );
define( 'SFFDIVI_GB_TIMEOUT', 250 );

function sffdivi_add_page()
{

    add_menu_page(
        SFFDIVI_GB_NAME,
        'SendFox for Divi',
        'manage_options',
        SFFDIVI_GB_ID,
        'sffdivi_create_page',
        plugins_url( 'assets/img/sendfox.png', __FILE__ ),
        89.75
    );
}
add_action( 'admin_menu', 'sffdivi_add_page' );

/**
 * Displays plugin's admin page
 *
 * @since 1.0.0
 */

function sffdivi_create_page()
{
    if( !current_user_can( 'manage_options' ) )
    {
        wp_die( __( 'Oops, you can\'t access this page.', 'sf4wp' ) );
    }

    include_once 'divi-sendfox-wp-admin.php';
}

/**
 * Registers plugins admin page options
 *
 * @param string $option_group A settings group name. Must exist prior to the register_setting call.
 * This must match the group name in settings_fields()
 * @param string $option_name The name of an option to sanitize and save.
 *
 * @since 1.0.0
 */

function sffdivi_admin_start()
{
    register_setting( 'sfdivi_options', 'sfdivi_options' );
}
add_action( 'admin_init', 'sffdivi_admin_start' );

/**
 * Redirect to the settings page on the first activation
 *
 * @since 1.0.0
 */

function sffdivi_plugin_install( $plugin )
{
    if( $plugin == plugin_basename( __FILE__ ) )
    {
        $options = get_option( 'sfdivi_options' );

        if( empty( $options ) )
        {
            if( wp_redirect( admin_url( 'admin.php?page=' . SFFDIVI_GB_ID ) ) )
            {
                exit;
            }
        }
    }
}
add_action( 'activated_plugin', 'sffdivi_plugin_install' );



function sffdivi_admin_header()
{
    echo '<style type="text/css">
            #adminmenu .toplevel_page_sendfox-for-divi .wp-menu-image img { width: 18px; padding: 6px 0 0 0; }
            #adminmenu .toplevel_page_sendfox-for-divi.current .wp-menu-image img { opacity: 1; }
    </style>';
}
add_action( 'admin_head', 'sffdivi_admin_header' );

/**
 * Pre-update plugin settings filter
 *
 * @since 1.0.0
 */

function sfdivi_set_pre_update_option( $new_value = '', $old_value = '' )
{
    if( !empty( $old_value ) )
    {

        foreach( $old_value as $k => $v )
        {
            if( isset( $new_value[ $k ] ) )
            {
                $old_value[ $k ] = $new_value[ $k ];
            }
        }


        foreach( $new_value as $k => $v )
        {
            if( ! isset( $old_value[ $k ] ) )
            {
                $old_value[ $k ] = $new_value[ $k ];
            }
        }
    }
    else
    {
        $old_value = $new_value;
    }

    return $old_value;
}
add_filter( 'pre_update_option_sfdivi_options', 'sfdivi_set_pre_update_option', 10, 2 );


add_action('wp_enqueue_scripts', 'sffdivi_insert_js');

function sffdivi_insert_js()
{
  wp_register_script( 'dcms_miscript', plugins_url( '/includes/script.js', __FILE__ ), array('jquery') );
  wp_localize_script('dcms_miscript','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);

  // enqueue jQuery library and the script you registered above
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'dcms_miscript' );
}

add_action('wp_ajax_nopriv_dcms_ajax_suscribe_sendfox','sffdivi_send_content');
add_action('wp_ajax_dcms_ajax_suscribe_sendfox','sffdivi_send_content');

function sffdivi_send_content()
{
  $options = get_option( 'sfdivi_options' );

  $myToken = $options[ 'api_key' ];

  $tokenCaptcha = sanitize_text_field($_POST["tokenCaptcha"]);
  $contact['email'] = sanitize_email($_POST["email"]);
  $contact['first_name'] = sanitize_text_field($_POST["first_name"]);
  $contact['last_name'] = sanitize_text_field($_POST["last_name"]);
  $listId = sanitize_key($_POST['lists']); //This will return just one ID
  $contact['lists'] = array( $listId );


  if( $options['enableReCaptcha'] == 1 && $options[ 'secretkey' ] != "" )
  {
      $resForm = ($tokenCaptcha != "") ? $tokenCaptcha: null;
      $apiUrl = 'https://www.google.com/recaptcha/api/siteverify';
      $params = [
          'secret' => $options[ 'secretkey' ],
          'response' => $resForm
      ];

      $response = sffdivi_submitRequest($apiUrl, $params);

      if ($response->success) {

      } else {
        return "reCaptcha is not valid";
      }
  }





  $getEmailForm = trim($contact['email']);
  if( $getEmailForm == "")
  {
    echo "Email is empty";
  }
  else
  {


    $response = sffdivi_api_request( $myToken, 'contacts', $contact, 'POST' );
    sffdivi_api_response( $response );

    if( $response["error"] != "" )
    {
      echo "Error Sendfox ".$response["error_text"];
    }else{
      echo 1;
    }
  }
	wp_die();
}

/**
   * Send POST data to API  and return response on JSON format
   */
function sffdivi_submitRequest($url, $params)
{
      $strParams = http_build_query($params, null, '&');

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $strParams);
      $http_response = curl_exec($curl);

      return json_decode($http_response);
}



class SFFDIVI_Installed_Plugin_Details {
  /**
	 * Set up the plugin.
	 *
	 * @since 1.0.0
	 */
	public function ipd_setup() {
		if ( is_admin() ) {
			// Display the link with the plugin action links.
			add_filter( 'plugin_action_links', array( $this, 'ipd_plugin_links' ), 10, 3 );

			// Display the link with the plugin meta.
			add_filter( 'plugin_row_meta', array( $this, 'ipd_plugin_links' ), 10, 4 );
		}
	}

	/**
	 * Add a "details" link to open a thickbox popup with information about
	 * the plugin from the public directory.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links List of links.
	 * @param string $plugin_file Relative path to the main plugin file from the plugins directory.
	 * @param array $plugin_data Data from the plugin headers.
	 * @return array
	 */
	public function ipd_plugin_links( $links, $plugin_file, $plugin_data ) {
		if ( isset( $plugin_data['PluginURI'] ) && false !== strpos( $plugin_data['PluginURI'], 'http://wordpress.org/extend/plugins/' ) ) {
			$slug = basename( $plugin_data['PluginURI'] );

			$links[] = sprintf( '<a href="%s" class="thickbox" title="%s">%s</a>',
				self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $slug . '&amp;TB_iframe=true&amp;width=600&amp;height=550' ),
				esc_attr( sprintf( __( 'More information about %s' ), $plugin_data['Name'] ) ),
				__( 'Details' )
			);
		}

		return $links;
	}
}

// Initialize the plugin.
$SFFDIVI_Installed_Plugin_Details = new SFFDIVI_Installed_Plugin_Details;
$SFFDIVI_Installed_Plugin_Details->ipd_setup();
