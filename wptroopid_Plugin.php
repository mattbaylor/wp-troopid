<?php


include_once('wptroopid_LifeCycle.php');

class troopid_Plugin extends troopid_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'client_id' => array(__('id.me client_id', 'wordpress-troopid-plugin')),
			 'client_secret' => array(__('id.me client_secret', 'wordpress-troopid-plugin')),		
			 'redirect_uri' => array(__('redirect uri (url) for id.me to target on success', 'wordpress-troopid-plugin')),
			 'sandbox' => array(__('run plugin in sandbox mode', 'wordpress-troopid-plugin'), 'true', 'false'),
			 'button-style-size' => array(__('Size of the button', 'wordpress-troopid-plugin'), 'small', 'medium', 'large'),
			 'button-style-color' => array(__('Color of the button', 'wordpress-troopid-plugin'), 'red', 'black', 'light gray', 'dark gray', 'white'),
			 'button-style-type' => array(__('Shape of the button', 'wordpress-troopid-plugin'), 'rounded', 'square')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'WordPress TroopID plugin';
    }

    protected function getMainPluginFileName() {
        return 'wptroopid.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
		
		//make sure jquery is loaded
		wp_enqueue_script('jquery');
		
		//load up bbq specifically for url deserialization in js
		wp_enqueue_script('jquery-bbq', plugins_url('js/jquery-bbq-1.2.1/jquery.ba-bbq.min.js', __FILE__), 'jquery');
		//load up jquery cookies, so we can see cookies in js
		wp_enqueue_script('jquery-cookie', plugins_url('js/jquery.cookie-1.4.1.min.js', __FILE__), 'jquery');
		//load up the redir js file for this plugin. This is used if we're not using the popup window.
		wp_enqueue_script('troopid-redir', plugins_url('js/redir.js', __FILE__), 'jquery');
		//load up jquery session storage, nicer than cookies...
		wp_enqueue_script('jquery-storage', plugins_url('js/jQuery-Storage-API-1.7.2/jquery.storageapi.min.js', __FILE__), 'jquery');
		//load up jquery post pessage, allows us to post variables on a simulated ajax call
		wp_enqueue_script('jquery-postmessage', plugins_url('js/jquery.ba-postmessage.min.js', __FILE__), 'jquery');
		//load up the receiver logic for this plugin
		wp_register_script('troopid-receiver', plugins_url('js/receiver.js', __FILE__), 'jquery-postmessage');
		//load up the pass through variables for the popup window.
		wp_enqueue_script('troopid-popup', plugins_url('js/popup.js',__FILE__), 'jquery');
		//set popup options from wp-options. Localized to js variable.
		wp_localize_script('troopid-popup', 'idmepopupvars', array(
			'sandbox' => __($this->getOption('sandbox'), 'wordpress-troopid-plugin'),
			'client_id' => __($this->getOption('client_id'), 'wordpress-troopid-plugin'),
			'scope' => __($this->getOption('scope'), 'wordpress-troopid-plugin'),
			'redirect_uri' => __(esc_url($this->getOption('redirect_uri')), 'wordpress-troopid-plugin'),
			'response_type' => __($this->getOption('response_type'), 'wordpress-troopid-plugin')
		));



        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39
		
		//Add a nice TroopID link if we don't want the button
		add_shortcode('troopid_link', array($this, 'troopid_link'));
		//Set up a button and allow for parameters for prepopulation of a form
		add_shortcode('troopid_formload', array($this, 'troopid_formload'));
		//Set up a button for a popup, no prefill options.
		add_shortcode('troopid_popup', array($this, 'troopid_popup'));
		//Set up a container to show verified in a nice display.
		add_shortcode('troopid_verified_container', array($this, 'troopid_verified_container'));
		


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
		
		
		//add other things
		add_action('init', array($this, 'myStartSession'), 1);
		add_action('wp_logout', array($this, 'myEndSession'));
		add_action('wp_login', array($this, 'myEndSession'));

		

    }
	
	public function troopid_link() {
		// get wp-options
   		$client_id = $this->getOption('client_id');
		$redirect_uri = $this->getOption('redirect_uri');
		$response_type = $this->getOption('response_type');
		$sandbox = $this->getOption('sandbox');
	    $scope = $this->getOption('scope');
		
		//test for sandbox so we know what api to call
	   if($sandbox=='true'){
			$out = "<a href=\"https://api.sandbox.id.me/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_uri&response_type=$response_type&scope=$scope\" class=\"idme_button\">ID.me</a>";
	   } else {
		   $out = "<a href=\"https://api.id.me/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_uri&response_type=$response_type$scope=$scope\" class=\"idme_button\">ID.me</a>";
	   }
		
		return $out;
	}
	
	public function troopid_verified_container(){
		//drop the verified container in the page
		$out = <<<EOT
<div id="troopid_verified" class="troopid-verified"></div>	
EOT;
		return $out;	
	}

	public function myStartSession() {
    	if(session_status() == PHP_SESSION_NONE) {
        	session_start();
			
    	}
	}

	public function myEndSession() {
    	session_destroy ();
	}
	
	public function troopid_popup() {
		//set up the button with options
		switch($this->getOption('button-style-size')){
			case 'small':
				$size = '20';
				break;
			case 'medium':
				$size = '32';
				break;
			case 'large':
				$size = '40';
				break;
			default:
				$size = '32';
		}
		
		switch($this->getOption('button-style-color')){
			case 'red':
				$color = 'red';
				break;
			case 'light gray':
				$color = 'gray1';
				break;
			case 'dark gray':
				$color = 'gray2';
				break;
			case 'black':
				$color = 'black';
				break;
			case 'white':
				$color = 'white';
				break;
			default:
				$color = 'red';
		}
		
		switch($this->getOption('button-style-type')){
			case 'rounded':
				$type = 'rnd';
				break;
			case 'square':
				$type = 'squ';
				break;
			default:
				$type = 'rnd';
		}
		
		switch($this->getOption('scope')){
			case 'military':
				$kind = 'troop';	
				break;
			case 'student':
				$kind = 'Student';
				break;
			case 'responder':
				$kind = 'Responder';
				break;
			default:
				$kind = 'troop';	
		}
		
		$scope = $this->getOption('scope');
		
		$button = $kind.'_'.$color.'_'.$type.'_'.$size.'.png'; 
		
		wp_enqueue_script('troopid-receiver');
		
		//output the button html
		$out = <<<EOT
<a class="troopid-login-trigger" href="#">
  <img alt="Verify your $scope affiliation with TroopID" src="https://s3.amazonaws.com/idme/buttons/$button" />
</a>
EOT;
		
		return $out;	
	}
	
	public function troopid_formload($atts) {
		//handle shortcode attributes
		$a = shortcode_atts( array(
        'first_name' => 'first_name',
        'last_name' => 'last_name',
		 'email' => 'email',
		 'phone' => 'phone',
		 'zip' => 'zip',
		 'verified' => 'verified',
		 'affiliation' => 'affiliation',
		 'service_started' => 'service_started',
		 'service_ended' => 'service_ended'
    ), $atts );
		//get wp-options for button display
		switch($this->getOption('button-style-size')){
			case 'small':
				$size = '20';
				break;
			case 'medium':
				$size = '32';
				break;
			case 'large':
				$size = '40';
				break;
			default:
				$size = '32';
		}
		
		switch($this->getOption('button-style-color')){
			case 'red':
				$color = 'red';
				break;
			case 'light gray':
				$color = 'gray1';
				break;
			case 'dark gray':
				$color = 'gray2';
				break;
			case 'black':
				$color = 'black';
				break;
			case 'white':
				$color = 'white';
				break;
			default:
				$color = 'red';
		}
		
		switch($this->getOption('button-style-type')){
			case 'rounded':
				$type = 'rnd';
				break;
			case 'square':
				$type = 'squ';
				break;
			default:
				$type = 'rnd';
		}
		
		switch($this->getOption('scope')){
			case 'military':
				$kind = 'troop';	
				break;
			case 'student':
				$kind = 'Student';
				break;
			case 'responder':
				$kind = 'Responder';
				break;
			default:
				$kind = 'troop';	
		}
		
		$scope = $this->getOption('scope');
		
		$button = $kind.'_'.$color.'_'.$type.'_'.$size.'.png'; 
		
		//drop the shortcode attribute values into the preload variable
		wp_localize_script('troopid-receiver', 'troopidformload', array(
			'first_name' => $a['first_name'],
			'last_name' => $a['last_name'],
			'email' => $a['email'],
			'phone' => $a['phone'],
			'zip' => $a['zip'],
			'verified' => $a['verified'],
			'affiliation' => $a['affiliation'],
			'service_started' => $a['service_started'],
			'service_ended' => $a['service_ended']
		));
		wp_enqueue_script('troopid-receiver');
		
		//output the button html
		$out = <<<EOT
<a class="troopid-login-trigger" href="#">
  <img alt="Verify your $scope affiliation with TroopID" src="https://s3.amazonaws.com/idme/buttons/$button" />
</a>
EOT;
		
		return $out;	
	}
	
}
