<?PHP
	// don't load directly 
	if ( !defined('ABSPATH') ) 
		die('-1');	
	
	//menu entrys
	function wowraid_menu_entry() {
		$cfg=get_option('wowraid_cfg');
		add_menu_page(__('WoW Raid','wowraid'), __('WoW Raid','wowraid'),'read', 'WoWRaid','', WP_PLUGIN_URL.'/'.WOWRAID_PLUGIN_DIR.'/images/wow-icon16x16.png');
		$hook = add_submenu_page('WoWRaid',__('WoW Raid Planer','wowraid'), __('Planer','wowraid'), 'read', 'WoWRaid','wowraid_raid_page');
		add_action('load-'.$hook, 'wowraid_raid_load');
		add_contextual_help($hook,wowraid_show_help());
		$hook = add_submenu_page('WoWRaid',__('WoW Raid Chars','wowraid'), __('Chars','wowraid'), 'read', 'WoWRaidChars','wowraid_chars_page');
		add_action('load-'.$hook, 'wowraid_chars_load');
		if (current_user_can($cfg['rolechars'])) 
			register_column_headers($hook,array('cb'=>'<input type="checkbox" />','name'=>__('Name','wowraid'),'user'=>__('User','wowraid'),'level'=>__('Level','wowraid'),'type'=>__('Type','wowraid'),'points'=>__('Points','wowraid'),'professions'=>__('Professions','wowraid'),'talent'=>__('Talents','wowraid'),'status'=>__('Status','wowraid')));
		else
			register_column_headers($hook,array('name'=>__('Name','wowraid'),'user'=>__('User','wowraid'),'level'=>__('Level','wowraid'),'type'=>__('Type','wowraid'),'points'=>__('Points','wowraid'),'professions'=>__('Professions','wowraid'),'talent'=>__('Talents','wowraid'),'status'=>__('Status','wowraid')));
		add_contextual_help($hook,wowraid_show_help());
		$hook = add_submenu_page('WoWRaid',__('WoW Raid Items','wowraid'), __('Items','wowraid'), 'read', 'WoWRaidItems','wowraid_items_page');
		add_action('load-'.$hook, 'wowraid_items_load');
		if (current_user_can($cfg['roleitems'])) 
			register_column_headers($hook,array('cb'=>'<input type="checkbox" />','id'=>__('ID','wowraid'),'name'=>__('Name','wowraid'),'winner'=>__('Winner','wowraid'),'dkp'=>__('DKP','wowraid')));
		else 
			register_column_headers($hook,array('id'=>__('ID','wowraid'),'name'=>__('Name','wowraid'),'winner'=>__('Winner','wowraid'),'dkp'=>__('DKP','wowraid')));
		add_contextual_help($hook,wowraid_show_help());
		$hook = add_submenu_page('WoWRaid',__('WoW Raid Settings','wowraid'), __('Settings','wowraid'),$cfg['rolesettings'], 'WoWRaidSettings','wowraid_settings_page');
		add_action('load-'.$hook, 'wowraid_settings_load');
		add_contextual_help($hook,wowraid_show_help());		
		wowraid_item_link_tip('js');
	}	
	
	function wowraid_wp_head() {
		wowraid_item_link_tip('js');
	}
	
	function wowraid_wp_login_head() {
		//wp_enqueue_style('WoWRasidWPLogin',plugins_url('/'.WOWRAID_PLUGIN_DIR.'/css/wp-login.css'),'',WOWRAID_VERSION,'all');
		echo "<link rel=\"stylesheet\" id=\"WoWRasidWPLogin-css\"  href=\"".plugins_url('/'.WOWRAID_PLUGIN_DIR.'/css/wp-login.css')."?ver=".WOWRAID_VERSION."\" type=\"text/css\" media=\"all\" />";
	}
		
	//Help too display
	function wowraid_show_help() {
		$help .= '<div class="metabox-prefs">';
		$help .= '<a href="http://wordpress.org/tags/wowraid" target="_blank">'.__('Support').'</a>';
		$help .= ' | <a href="http://wordpress.org/extend/plugins/wowraid/faq/" target="_blank">' . __('FAQ') . '</a>';
		$help .= ' | <a href="http://danielhuesken.de/portfolio/wowraid" target="_blank">' . __('Plugin Homepage', 'wowraid') . '</a>';
		$help .= ' | <a href="http://wordpress.org/extend/plugins/wowraid" target="_blank">' . __('Plugin Home on WordPress.org', 'wowraid') . '</a>';
		$help .= ' | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=daniel%40huesken-net%2ede&amp;item_name=Daniel%20Huesken%20Plugin%20Donation&amp;item_number=WoWRaid&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=EUR&amp;lc=DE&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank">' . __('Donate') . '</a>';
		$help .= "</div>\n";	
		$help .= '<div class="metabox-prefs">';
		$help .= __('Version:', 'backwpup').' '.WOWRAID_VERSION.' | ';
		$help .= __('Author:', 'backwpup').' <a href="http://danielhuesken.de" target="_blank">Daniel H&uuml;sken</a>';
		$help .= "</div>\n";
		return $help;
	}
	
	//Raid Page
	function wowraid_raid_page() {
		global $wowraid_message,$wpdb;
		$current_screen==get_plugin_page_hookname('WoWRaidItems','WoWRaid');
		if(!empty($filebrowser_message)) 
			echo '<div id="message" class="updated fade"><p><strong>'.$wowraid_message.'</strong></p></div>';
		require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/raid-page.php');
	}
	
	//Raid Page load
	function wowraid_raid_load() {
		global $wowraid_message,$wpdb;
		$cfg=get_option('wowraid_cfg');
		//Css for Admin Section
		wp_enqueue_style('WoWRasidRaid',plugins_url('/'.WOWRAID_PLUGIN_DIR.'/css/raid.css'),'',WOWRAID_VERSION,'screen');
		//For save Options
		if (current_user_can($cfg['roleplaner']))
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/raid-load.php');
	}

	//Chars Page
	function wowraid_chars_page() {
		global $wowraid_message,$wpdb,$user_ID;
		$cfg=get_option('wowraid_cfg');
		if (empty($current_screen))
			$current_screen=get_plugin_page_hookname('WoWRaidChars','WoWRaid');
		if(!empty($wowraid_message)) 
			echo '<div id="message" class="updated fade"><p><strong>'.$wowraid_message.'</strong></p></div>';
		require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/chars-page.php');
	}
	
	//Chars Page
	function wowraid_chars_load() {
		global $wowraid_message,$wpdb,$user_ID;
		$cfg=get_option('wowraid_cfg');
		//Css for Admin Section
		wp_enqueue_style('WoWRaidChar',plugins_url('/'.WOWRAID_PLUGIN_DIR.'/css/chars.css'),'',WOWRAID_VERSION,'screen');
		//For save Options
		if (current_user_can($cfg['rolechars']))
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/chars-load.php');
	}

	//Items Page
	function wowraid_items_page() {
		global $wowraid_message,$wpdb;
		$cfg=get_option('wowraid_cfg');
		if (empty($current_screen))
			$current_screen=get_plugin_page_hookname('WoWRaidItems','WoWRaid');
		if(!empty($wowraid_message)) 
			echo '<div id="message" class="updated fade"><p><strong>'.$wowraid_message.'</strong></p></div>';
		if ($_REQUEST['action']=='edit' and current_user_can($cfg['roleitems'])) {
			check_admin_referer('WoWRaidItemsEdit');
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/items-edit.php');
		} else {
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/items-page.php');
		}
	}
	
	//Items Page
	function wowraid_items_load() {
		global $wowraid_message,$wpdb;
		$cfg=get_option('wowraid_cfg');
		//Css for Admin Section
		wp_enqueue_style('WoWRaidItems',plugins_url('/'.WOWRAID_PLUGIN_DIR.'/css/items.css'),'',WOWRAID_VERSION,'screen');
		//For save Options
		if (current_user_can($cfg['roleitems']))
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/items-load.php');
	}

	//Settings Page
	function wowraid_settings_page() {
		global $wowraid_message,$wpdb;
		if(!empty($wowraid_message)) 
			echo '<div id="message" class="updated fade"><p><strong>'.$wowraid_message.'</strong></p></div>';
		require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/settings-page.php');
	}
	
	//Settings Page load
	function wowraid_settings_load() {
		global $wowraid_message,$wpdb;
		$cfg=get_option('wowraid_cfg');
		//For save Options
		if (current_user_can($cfg['rolesettings']))
			require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/settings-load.php');
	}
	
	//add edit setting to plugins page
	function wowraid_plugin_options_link($links) {
		$settings_link='<a href="admin.php?page=WoWRaid" title="' . __('Go to Settings Page','wowraid') . '" class="edit">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); 
		return $links;
	}
	
	//add links on plugins page
	function wowraid_plugin_links($links, $file) {
		if ($file == WOWRAID_PLUGIN_DIR.'/wowraid.php') {
			$links[] = '<a href="http://wordpress.org/extend/plugins/wowraid/faq/" target="_blank">' . __('FAQ') . '</a>';
			$links[] = '<a href="http://wordpress.org/tags/wowraid/" target="_blank">' . __('Support') . '</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=daniel%40huesken-net%2ede&amp;item_name=Daniel%20Huesken%20Plugin%20Donation&amp;item_number=WoWRaid&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=EUR&amp;lc=DE&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank">' . __('Donate') . '</a>';
		}
		return $links;
	}

	//Update chars from WoW Armory
	function wowraid_update_chars() {
		global $wpdb;
		@set_time_limit(0); //unlimited execution time
		$cfg=get_option('wowraid_cfg');		
		$url = 'http://'.$cfg['wowarmoryregion'].'.wowarmory.com/guild-info.xml?r='.urlencode($cfg['realm']).'&n='.urlencode($cfg['guild']).'&p=1';  

		if (empty($cfg['guild']) or empty($cfg['realm'])) 
			return false;
	
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ".$cfg['wowarmorylang']."; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: ".$cfg['wowarmorylang'].",".substr($cfg['wowarmorylang'],strpos($cfg['wowarmorylang'],"_")+1))); 
		$content = curl_exec($ch); 
		curl_close($ch); 
		
		if ($content===false) 
			return false;
		
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $content,$vals,$index);
		xml_parser_free($parser);	
		
		print_r($vals);
		
		if (empty($vals[$index['GUILDHEADER'][0]]['attributes']['NAME'])) 
			return false;
		
		$timeupdatebegin=time();
		foreach($index['CHARACTER'] as $cahrid) {
			if (wowraid_get_char_data($vals[$cahrid]['attributes']['NAME'])) {
				if (($cfg['updatetwinkrank']>0 and $cfg['updatetwinkrank']==$vals[$cahrid]['attributes']['RANK']) or ($cfg['updatetwinklevel']>$vals[$cahrid]['attributes']['LEVEL']))
					$wpdb->update( $wpdb->wowraid_chars, array( 'Twink' =>1 ), array( 'Name' => $vals[$cahrid]['attributes']['NAME'] ), array( '%s' ), array( '%s' ) );
				if ($cfg['updateraiderrank']>0 and $cfg['updateraiderrank']>=$vals[$cahrid]['attributes']['RANK'])
					$wpdb->update( $wpdb->wowraid_chars, array( 'Raider' =>1 ), array( 'Name' => $vals[$cahrid]['attributes']['NAME'] ), array( '%s' ), array( '%s' ) );
			}
			sleep(1);  //query server not to fast
		}
		
		//Update char not in guild
		$chars = $wpdb->get_results("SELECT Name FROM $wpdb->wowraid_chars WHERE last_update < ".$timeupdatebegin." ORDER BY last_update",'ARRAY_A');
		foreach($chars as $char) {
			wowraid_get_char_data($char['Name']);
			sleep(1);  //query server not to fast
		}
		
		$twinkguilds=split(',',$cfg['twinkguild']);
		foreach($twinkguilds as $twinkguild) {
			if (!empty($twinkguild))
				$wpdb->update( $wpdb->wowraid_chars, array( 'Twink' =>1 ), array( 'Guild' => $twinkguild ), array( '%s' ), array( '%s' ) );
		}
		
	}
	
	
	//get item data from WoW Armory
	function wowraid_get_item_data($item="") {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		
		if (empty($item))
			return false;
		
		$url = 'http://'.$cfg['wowarmoryregion'].'.wowarmory.com/item-info.xml?i='.urlencode($item).'&p=1';  

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ".$cfg['wowarmorylang']."; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: ".$cfg['wowarmorylang'].",".substr($cfg['wowarmorylang'],strpos($cfg['wowarmorylang'],"_")+1))); 
		$content = curl_exec($ch); 
		curl_close($ch); 
		
		if ($content===false) 
			return false;
		
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $content,$vals,$index);
		xml_parser_free($parser);	
		
		if (empty($vals[$index['ITEM'][0]]['attributes']['NAME'])) 
			return false;
		
		$item = $wpdb->get_row("SELECT ID FROM $wpdb->wowraid_items WHERE ID = ".$vals[$index['ITEM'][0]]['attributes']['ID']);
		if ($wpdb->num_rows==0) 
			$wpdb->insert( $wpdb->wowraid_items, array( 'ID' => $vals[$index['ITEM'][0]]['attributes']['ID'], 'Name' => $vals[$index['ITEM'][0]]['attributes']['NAME'], 'Icon' => $vals[$index['ITEM'][0]]['attributes']['ICON'], 'Quality' => $vals[$index['ITEM'][0]]['attributes']['QUALITY'] ), array( '%d', '%s', '%s', '%d' ) );
		else 
			$wpdb->update( $wpdb->wowraid_items, array( 'Name' => $vals[$index['ITEM'][0]]['attributes']['NAME'], 'Icon' => $vals[$index['ITEM'][0]]['attributes']['ICON'], 'Quality' => $vals[$index['ITEM'][0]]['attributes']['QUALITY'] ), array( 'ID' => $vals[$index['ITEM'][0]]['attributes']['ID'] ), array( '%s', '%s', '%d' ), array( '%d' ) );
		
		return true;
	}	

	//get char data from WoW Armory
	function wowraid_get_char_data($cahr="") {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		
		if (empty($cahr) or empty($cfg['realm']))
			return false;
		
		$url = 'http://'.$cfg['wowarmoryregion'].'.wowarmory.com/character-sheet.xml?r='.urlencode($cfg['realm']).'&cn='.urlencode($cahr).'&p=1';  

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ".$cfg['wowarmorylang']."; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: ".$cfg['wowarmorylang'].",".substr($cfg['wowarmorylang'],strpos($cfg['wowarmorylang'],"_")+1))); 
		$content = curl_exec($ch); 
		curl_close($ch); 
		
		if ($content===false) 
			return false;
			
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $content,$vals,$index);
		xml_parser_free($parser);	
		
		if (empty($vals[$index['CHARACTER'][0]]['attributes']['NAME'])) 
			return false;
		
		$spec2="";
		if (isset($vals[$index['TALENTSPEC'][1]]['attributes']['TREEONE'])) 
			$spec2=$vals[$index['TALENTSPEC'][1]]['attributes']['TREEONE'].'/'.$vals[$index['TALENTSPEC'][1]]['attributes']['TREETWO'].'/'.$vals[$index['TALENTSPEC'][1]]['attributes']['TREETHREE'];
		
		$char = $wpdb->get_row("SELECT Name FROM $wpdb->wowraid_chars WHERE Name = '".$vals[$index['CHARACTER'][0]]['attributes']['NAME']."'");
		if ($wpdb->num_rows==0) {
			$wpdb->insert( $wpdb->wowraid_chars, array( 
			'Name' => $vals[$index['CHARACTER'][0]]['attributes']['NAME'], 
			'ClassID' => $vals[$index['CHARACTER'][0]]['attributes']['CLASSID'], 
			'FactionID' => $vals[$index['CHARACTER'][0]]['attributes']['FACTIONID'], 
			'GenderID' => $vals[$index['CHARACTER'][0]]['attributes']['GENDERID'], 
			'RaceID' => $vals[$index['CHARACTER'][0]]['attributes']['RACEID'], 
			'Guild' => $vals[$index['CHARACTER'][0]]['attributes']['GUILDNAME'], 
			'Level' => $vals[$index['CHARACTER'][0]]['attributes']['LEVEL'], 
			'Points' => $vals[$index['CHARACTER'][0]]['attributes']['POINTS'], 
			'ProfessionName1' => $vals[$index['SKILL'][0]]['attributes']['NAME'], 
			'ProfessionLevel1' => $vals[$index['SKILL'][0]]['attributes']['VALUE'], 
			'ProfessionName2' => $vals[$index['SKILL'][1]]['attributes']['NAME'], 
			'ProfessionLevel2' => $vals[$index['SKILL'][1]]['attributes']['VALUE'],
			'TalentSpec1' => $vals[$index['TALENTSPEC'][0]]['attributes']['TREEONE'].'/'.$vals[$index['TALENTSPEC'][0]]['attributes']['TREETWO'].'/'.$vals[$index['TALENTSPEC'][0]]['attributes']['TREETHREE'],
			'TalentSpec2' => $spec2,
			'last_update' => time() 
			), array( '%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d' , '%s', '%d', '%s', '%d', '%s', '%s', '%d'));
		} else {
			$wpdb->update( $wpdb->wowraid_chars, array( 
			'ClassID' => $vals[$index['CHARACTER'][0]]['attributes']['CLASSID'], 
			'FactionID' => $vals[$index['CHARACTER'][0]]['attributes']['FACTIONID'], 
			'GenderID' => $vals[$index['CHARACTER'][0]]['attributes']['GENDERID'], 
			'RaceID' => $vals[$index['CHARACTER'][0]]['attributes']['RACEID'], 
			'Guild' => $vals[$index['CHARACTER'][0]]['attributes']['GUILDNAME'], 
			'Level' => $vals[$index['CHARACTER'][0]]['attributes']['LEVEL'], 
			'Points' => $vals[$index['CHARACTER'][0]]['attributes']['POINTS'], 
			'ProfessionName1' => $vals[$index['SKILL'][0]]['attributes']['NAME'], 
			'ProfessionLevel1' => $vals[$index['SKILL'][0]]['attributes']['VALUE'], 
			'ProfessionName2' => $vals[$index['SKILL'][1]]['attributes']['NAME'], 
			'ProfessionLevel2' => $vals[$index['SKILL'][1]]['attributes']['VALUE'],
			'TalentSpec1' => $vals[$index['TALENTSPEC'][0]]['attributes']['TREEONE'].'/'.$vals[$index['TALENTSPEC'][0]]['attributes']['TREETWO'].'/'.$vals[$index['TALENTSPEC'][0]]['attributes']['TREETHREE'],
			'TalentSpec2' => $spec2,
			'last_update' => time() 
			), array( 'Name' => $vals[$index['CHARACTER'][0]]['attributes']['NAME'] ), 
			array( '%d', '%d', '%d', '%d', '%s', '%d', '%d' , '%s', '%d', '%s', '%d', '%s', '%s', '%d' ), array( '%s' ));
		}
		return true;
	}
	
	
   //delete Otions
	function wowraid_plugin_uninstall() {
		global $wpdb;
		delete_option('wowraid_cfg');
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->wowraid_chars);
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->wowraid_items);
	}
	
	//On Plugin activate
	function wowraid_plugin_activate() {
		global $wpdb;

		//Create log table
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$charset_collate = '';
		if($wpdb->supports_collation()) {
			if(!empty($wpdb->charset)) {
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if(!empty($wpdb->collate)) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}
		//WP Function to add or Upgrade Dtatabse
		dbDelta(
			"CREATE TABLE ".$wpdb->wowraid_chars." (
			ID bigint(20) NOT NULL AUTO_INCREMENT,
			userID bigint(20) NOT NULL,
			`Name` varchar(100) NOT NULL,
			ClassID tinyint(4) NOT NULL,
			FactionID BOOL NOT NULL DEFAULT '0',
			GenderID BOOL NOT NULL DEFAULT '0',
			RaceID tinyint(4) NOT NULL,
			Guild varchar(100) DEFAULT NULL,
			`Level` tinyint(4) NOT NULL,
			Points smallint(6) NOT NULL,
			ProfessionName1 varchar(100) DEFAULT NULL,
			ProfessionLevel1 smallint(6) DEFAULT NULL,
			ProfessionName2 varchar(100) DEFAULT NULL,
			ProfessionLevel2 smallint(6) DEFAULT NULL,
			TalentSpec1 varchar(10) DEFAULT NULL,
			TalentSpec2 varchar(10) DEFAULT NULL,
			Twink BOOL NOT NULL DEFAULT '0',
			Raider BOOL NOT NULL DEFAULT '0',
			last_update bigint(20) NOT NULL,
			PRIMARY KEY (ID)
			)".$charset_collate
		);
		dbDelta(
			"CREATE TABLE ".$wpdb->wowraid_items." (
			ID int(20) NOT NULL,
			`Name` varchar(255) NOT NULL,
			Icon varchar(50) NOT NULL,
			Quality tinyint(4) NOT NULL,
			DKP tinyint(4) NOT NULL,
			UNIQUE KEY ID (ID)
			)".$charset_collate
		);
		
		//Defaults for Options
		$cfg=get_option('wowraid_cfg');
		if (!isset($cfg['wowarmoryregion'])) $cfg['wowarmoryregion']="www";
		if (!isset($cfg['wowarmorylang'])) $cfg['wowarmorylang']="en_us";
		if (!isset($cfg['wowarmorylang'])) $cfg['itemlinktip']="wowhead_www";
		if (!isset($cfg['updatetwinkrank'])) $cfg['updatetwinkrank']=-1;
		if (!isset($cfg['updatetwinklevel'])) $cfg['updatetwinklevel']=10;
		if (!isset($cfg['updateraiderrank'])) $cfg['updateraiderrank']=-1;
		if (!isset($cfg['updateactivate'])) $cfg['updateactivate']=false;
		if (!isset($cfg['updatehour'])) $cfg['updatehour']=01;
		if (!isset($cfg['updateminute'])) $cfg['updateminute']=30;
		if (!isset($cfg['rolesettings'])) $cfg['rolesettings']=10;
		if (!isset($cfg['rolechars'])) $cfg['rolechars']=7;
		if (!isset($cfg['roleitems'])) $cfg['roleitems']=7;
		if (!isset($cfg['roleplaner'])) $cfg['roleplaner']=2;
		if (!isset($cfg['qualitycolor'][0])) $cfg['qualitycolor'][0]="gray";
		if (!isset($cfg['qualitycolor'][1])) $cfg['qualitycolor'][1]="white";
		if (!isset($cfg['qualitycolor'][2])) $cfg['qualitycolor'][2]="#00c400";
		if (!isset($cfg['qualitycolor'][3])) $cfg['qualitycolor'][3]="blue";
		if (!isset($cfg['qualitycolor'][4])) $cfg['qualitycolor'][4]="purple";
		if (!isset($cfg['qualitycolor'][5])) $cfg['qualitycolor'][5]="orange";
		if (!isset($cfg['qualitycolor'][7])) $cfg['qualitycolor'][7]="#eac787";
		update_option('wowraid_cfg',$cfg);
		
		if($_POST['updateactivate']) 
			wp_schedule_event(mktime($cfg['updatehour'],$cfg['updateminute']), 'daily', 'wowraid_char_update');
	
	}
	
	//on Plugin deaktivate
	function wowraid_plugin_deactivate() {
		if ($time=wp_next_scheduled('wowraid_char_update')) 
			wp_unschedule_event($time,'wowraid_char_update');
	}
	
	// replace iem text with tooltips an color
	function wowraid_the_content($the_content) {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		$pos=0;
		while ($pos = strpos($the_content, "[item=",$pos)) {
			if ($pos !== FALSE) {
				$end=strpos($the_content, "]",$pos);
				if ($end!== FALSE and $end-$pos<13) {
					$itemid=substr($the_content,$pos+6,$end-($pos+6));
					$item = $wpdb->get_row("SELECT ID FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
					if (empty($item->ID)) {
						wowraid_get_item_data($itemid);
						$item = $wpdb->get_row("SELECT ID FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
					}
					if (!empty($item->ID)) {
						if ($code=wowraid_item_link_tip($itemid))
							$the_content = str_replace("[item=".$itemid."]",$code,$the_content);
					}
				}
			}
		}
		$pos=0;
		while ($pos = strpos($the_content, "[char=",$pos)) {
			if ($pos !== FALSE) {
				$end=strpos($the_content, "]",$pos);
				if ($end!== FALSE and $end-$pos<13) {
					$charname=substr($the_content,$pos+6,$end-($pos+6));
					$char = $wpdb->get_row("SELECT Name FROM $wpdb->wowraid_chars WHERE Name = '$charname'");
					if (empty($char->Name)) {
						wowraid_get_char_data($charname);
						$char = $wpdb->get_row("SELECT Name FROM $wpdb->wowraid_chars WHERE Name = '$charname'");
					}
					if (!empty($char->Name)) {
						if ($code=wowraid_char_display($char->Name))
							$the_content = str_replace("[char=".$charname."]",$code,$the_content);
					}
				}
			}
		}
		
		if (strpos($the_content, "[show_guild_chars]")) {
			$cfg=get_option('wowraid_cfg');
			$code="";$where="";$switsh=1;
			$classes=wowraid_ids_to_name(-1,'Class');
			if ($cfg['guildcharsraider'])
				$where.=" AND Raider=1";
			if ($cfg['guildcharstwinks'])
				$where.=" AND Twink=0";
			$code.= "<table>";
			foreach($classes as $classid=>$classname) {	
				if ($switsh==1) {
					$code.= "<tr valign=\"top\"><td style=\"text-align:left;\">";
					$code.= "<h3><nobr><img style=\"vertical-align:middle;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/class/".$classid."_32.png\" title=\"".$classname."\" height=\"32\" width=\"32\" /> ".$classname."</nobr></h3>";
				} else {
					$code.= "<td style=\"text-align:right;\">";
					$code.= "<h3><nobr>".$classname." <img style=\"vertical-align:middle;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/class/".$classid."_32.png\" title=\"".$classname."\" height=\"32\" width=\"32\" /></nobr></h3>";
				}
				$chars = $wpdb->get_results("SELECT * FROM $wpdb->wowraid_chars WHERE Guild = '".$cfg['guild']."' AND ClassID = ".$classid.$where." ORDER BY Name",'ARRAY_A');
				if ($wpdb->num_rows>0) {
					$switshtwo=1;
					foreach($chars as $char) {
						if ($cfg['guildcharsdetailed']) {
							if ($switshtwo==1) {
								$code.="<div style=\"margin-top:5px;margin-bottom;0px;text-align:center;\">";
								$code.="<img style=\"float:left;margin-right:5px;width:64px;height:64px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/portrait/".$char['GenderID']."-".$char['RaceID']."-".$char['ClassID'].".gif\" title=\"".wowraid_ids_to_name($char['RaceID'],'Race')." - ".wowraid_ids_to_name($char['ClassID'],'Class')."\" />";
								$code.="<a style=\"font-weight:bold;\" href=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/character-sheet.xml?r=".urlencode($cfg['realm'])."&cn=".urlencode($char['Name'])."\" target=\"_blank\">".$char['Name']."</a><br />";
								$code.="<span style=\"font-size:0.8em;\">".__('Level','wowraid')." ".$char['Level']."</span><br />";
								$code.="<span style=\"font-size:0.8em;\">".$char['Points']." ".__('Points','wowraid')."</span><br class=\"clear\"/>";
								$code.="</div>";
								$switshtwo=2;
							} else {
								$code.="<div style=\"margin-top:5px;margin-bottom;0px;text-align:center;\">";
								$code.="<img style=\"float:right;margin-left:5px;width:64px;height:64px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/portrait/".$char['GenderID']."-".$char['RaceID']."-".$char['ClassID'].".gif\" title=\"".wowraid_ids_to_name($char['RaceID'],'Race')." - ".wowraid_ids_to_name($char['ClassID'],'Class')."\" />";
								$code.="<a style=\"font-weight:bold;\" href=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/character-sheet.xml?r=".urlencode($cfg['realm'])."&cn=".urlencode($char['Name'])."\" target=\"_blank\">".$char['Name']."</a><br />";
								$code.="<span style=\"font-size:0.8em;\">".__('Level','wowraid')." ".$char['Level']."</span><br />";
								$code.="<span style=\"font-size:0.8em;\">".$char['Points']." ".__('Points','wowraid')."</span><br class=\"clear\"/>";
								$code.="</div>";
								$switshtwo=1;							
							}
						} else {
							if ($switsh==1) {
								$code.="<img style=\"vertical-align:middle;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/race/".$char['RaceID']."-".$char['GenderID'].".gif\" title=\"".wowraid_ids_to_name($char['RaceID'],'RaceID')."\" height=\"18\" width=\"18\" /> <a href=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/character-sheet.xml?r=".urlencode($cfg['realm'])."&cn=".urlencode($char['Name'])."\" target=\"_blank\">".$char['Name']."</a><br />";
							} else {
								$code.="<a href=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/character-sheet.xml?r=".urlencode($cfg['realm'])."&cn=".urlencode($char['Name'])."\" target=\"_blank\">".$char['Name']."</a> <img style=\"vertical-align:middle;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/race/".$char['RaceID']."-".$char['GenderID'].".gif\" title=\"".wowraid_ids_to_name($char['RaceID'],'RaceID')."\" height=\"18\" width=\"18\" /><br />";
							}
						}
					}
				}
				if ($switsh==1) {
					$code.= "</td><td style=\"width:128px\"></td>";
					$switsh=2;
				} else {
					$code.= "</td></tr>";
					$switsh=1;
				}
			}
			if ($switsh==2)
				$code.= "<td></td></tr>";
			$code.= "</table>";
			$the_content = str_replace("[show_guild_chars]",$code,$the_content);
		}
		
		return $the_content;
	}
	
	function wowraid_char_display($charname="",$showclassimg=true,$showraceimg=true,$showfactionimg=false) {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		$raceimg=""; $classimg=""; $factionimg="";
		
		$char = $wpdb->get_row("SELECT Name,FactionID,RaceID,GenderID,ClassID,Guild FROM $wpdb->wowraid_chars WHERE Name = '$charname'");
		
		if (empty($char->Name)) 
			return $charname;
		
		if ($showraceimg)
			$raceimg="<img style=\"vertical-align:middle;width:16px;height:16px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/race/".$char->RaceID."-".$char->GenderID.".gif\" title=\"".wowraid_ids_to_name($char->RaceID,'RaceID')."\" /> ";
		if ($showclassimg)
			$classimg="<img style=\"vertical-align:middle;width:16px;height:16px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/class/".$char->ClassID.".gif\" title=\"".wowraid_ids_to_name($char->ClassID,'ClassID')."\" /> ";
		if ($showfactionimg)
			$factionimg="<img style=\"vertical-align:middle;width:16px;height:16px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/faction/".$char->FactionID.".gif\" title=\"".wowraid_ids_to_name($char->FactionID,'FactionID')."\" /> ";
			
		$link=$factionimg.$raceimg.$classimg."<a href=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/character-sheet.xml?r=".urlencode($cfg['realm'])."&cn=".urlencode($char->Name)."\" target=\"_blank\" title=\"".__('Guild: ','wowraid').$char->Guild."\">".$char->Name."</a>";
		
		return $link;
	}
	
	function wowraid_ids_to_name($ID=-1,$type='') {
		if (strtolower($type)=="class" or strtolower($type)=="classid") { //ClassIDs
			if ($ID==1)
				return __('Warrior','wowraid');
			elseif ($ID==2)
				return __('Paladin','wowraid');
			elseif ($ID==3)
				return __('Hunter','wowraid');
			elseif ($ID==4)
				return __('Rogue','wowraid');
			elseif ($ID==5)
				return __('Priest','wowraid');
			elseif ($ID==6)
				return __('Death Knight','wowraid');
			elseif ($ID==7)
				return __('Shaman','wowraid');
			elseif ($ID==8)
				return __('Mage','wowraid');
			elseif ($ID==9)
				return __('Warlock','wowraid');
			elseif ($ID==11)
				return __('Druid','wowraid');
			else {
				return array(
					1=>__('Warrior','wowraid'),
					2=>__('Paladin','wowraid'),
					3=>__('Hunter','wowraid'),
					4=>__('Rogue','wowraid'),
					5=>__('Priest','wowraid'),
					6=>__('Death Knight','wowraid'),
					7=>__('Shaman','wowraid'),
					8=>__('Mage','wowraid'),
					9=>__('Warlock','wowraid'),
					11=>__('Druid','wowraid')
				);
			}
		} elseif (strtolower($type)=="gender" or strtolower($type)=="genderid") {  //GenderIDs
			if ($ID==0)
				return __('Male','wowraid');
			elseif ($ID==1)
				return __('Femail','wowraid');
			else {
				return array(
					0=>__('Male','wowraid'),
					1=>__('Femail','wowraid')
				);
			}
		} elseif (strtolower($type)=="faction" or strtolower($type)=="factionid") {  //FactionIDs
			if ($ID==0)
				return __('Alliance','wowraid');
			elseif ($ID==1)
				return __('Horde','wowraid');
			else {
				return array(
					0=>__('Alliance','wowraid'),
					1=>__('Horde','wowraid')
				);
			}
		} elseif (strtolower($type)=="race" or strtolower($type)=="raceid") {  //RaceIDs
			if ($ID==1)
				return __('Human','wowraid');
			elseif ($ID==2)
				return __('Orc','wowraid');
			elseif ($ID==3)
				return __('Dwarf','wowraid');
			elseif ($ID==4)
				return __('Night Elf','wowraid');
			elseif ($ID==5)
				return __('Undead','wowraid');
			elseif ($ID==6)
				return __('Tauren','wowraid');
			elseif ($ID==7)
				return __('Gnome','wowraid');
			elseif ($ID==8)
				return __('Troll','wowraid');
			elseif ($ID==10)
				return __('Blood Elf','wowraid');
			elseif ($ID==11)
				return __('Draenei','wowraid');
			else {
				return array(
					1=>__('Human','wowraid'),
					2=>__('Orc','wowraid'),
					3=>__('Dwarf','wowraid'),
					4=>__('Night Elf','wowraid'),
					5=>__('Undead','wowraid'),
					6=>__('Tauren','wowraid'),
					7=>__('Gnome','wowraid'),
					8=>__('Troll','wowraid'),
					10=>__('Blood Elf','wowraid'),
					11=>__('Draenei','wowraid')
				);
			}
		} elseif (strtolower($type)=="function" or strtolower($type)=="functionid") {  //FunctionIDs
			if ($ID==0)
				return __('Tank','wowraid');
			elseif ($ID==1)
				return __('Heal','wowraid');
			elseif ($ID==2)
				return __('Range DD','wowraid');
			elseif ($ID==3)
				return __('Meele DD','wowraid');
			else {
				return array(
					0=>__('Tank','wowraid'),
					1=>__('Heal','wowraid'),
					2=>__('Range DD','wowraid'),
					3=>__('Meele DD','wowraid')
				);
			}
		} elseif (strtolower($type)=="quality" or strtolower($type)=="qualityid") {  //QualityIDs
			if ($ID==0)
				return __('Poor','wowraid');
			elseif ($ID==1)
				return __('Common','wowraid');
			elseif ($ID==2)
				return __('Uncommon','wowraid');
			elseif ($ID==3)
				return __('Rare','wowraid');
			elseif ($ID==4)
				return __('Epic','wowraid');
			elseif ($ID==5)
				return __('Legendary','wowraid');
			elseif ($ID==7)
				return __('Heirloom','wowraid');
			else {
				return array(
					0=>__('Poor','wowraid'),
					1=>__('Common','wowraid'),
					2=>__('Uncommon','wowraid'),
					3=>__('Rare','wowraid'),
					4=>__('Epic','wowraid'),
					5=>__('Legendary','wowraid'),
					7=>__('Heirloom','wowraid')
				);
			}				
		} else {
			return false;
		}
	}
	
	function wowraid_talent_spec($race,$talents='0/0/0') {
		$talenttrees=split('/',$talents);
		if ($race==1) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Arms','wowraid'); $spec['functionid']=3;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Fury','wowraid'); $spec['functionid']=3;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Protection','wowraid'); $spec['functionid']=0;
			}
		} elseif ($race==2) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Holy','wowraid'); $spec['functionid']=1;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Protection','wowraid'); $spec['functionid']=0;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Retribution','wowraid'); $spec['functionid']=3;
			}
		} elseif ($race==3) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Beast Mastery','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Marksmanship','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Survival','wowraid'); $spec['functionid']=2;
			}
		} elseif ($race==4) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Assassination','wowraid'); $spec['functionid']=3;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Combat','wowraid'); $spec['functionid']=3;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Subtlety','wowraid'); $spec['functionid']=3;
			}
		} elseif ($race==5) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Discipline','wowraid'); $spec['functionid']=1;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Holy','wowraid'); $spec['functionid']=1;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Shadow','wowraid'); $spec['functionid']=2;
			}
		} elseif ($race==6) { //function ids corrigiren
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Blood','wowraid'); $spec['functionid']=0;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Frost','wowraid'); $spec['functionid']=0;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Unholy','wowraid'); $spec['functionid']=0;
			}
		} elseif ($race==7) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Elemental','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Enhancement','wowraid'); $spec['functionid']=1;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Restoration','wowraid'); $spec['functionid']=1;
			}
		} elseif ($race==8) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Arcane','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Fire','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Frost','wowraid'); $spec['functionid']=2;
			}
		} elseif ($race==9) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Affliction','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Demonology','wowraid'); $spec['functionid']=2;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Destruction','wowraid'); $spec['functionid']=2;
			}
		} elseif ($race==11) {
			if ($talenttrees[0]>$talenttrees[1] and $talenttrees[0]>$talenttrees[2]) {
				$spec['name']=__('Balance','wowraid'); $spec['functionid']=3;
			} elseif ($talenttrees[1]>$talenttrees[0] and $talenttrees[1]>$talenttrees[2]) {
				$spec['name']=__('Feral Combat','wowraid'); $spec['functionid']=0;
			} elseif ($talenttrees[2]>$talenttrees[0] and $talenttrees[2]>$talenttrees[1]) {
				$spec['name']=__('Restoration','wowraid'); $spec['functionid']=1;
			}
		} else {
			return false;
		}
		return $spec;
	} 
	
	
	//show item links and tooltips
	function wowraid_item_link_tip($itemid="") {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		
		$itemtooltip['buffed_com']['js']='http://www.buffed.de/js/buffed-ext-wow-tooltips.js';
		$itemtooltip['buffed_com']['link']='http://wowdata.getbuffed.com/?i=';
		$itemtooltip['buffed_com']['icon']=true;
		$itemtooltip['buffed_de']['js']='http://www.buffed.de/js/buffed-ext-wow-tooltips.js';
		$itemtooltip['buffed_de']['link']='http://wowdata.buffed.de/?i=';
		$itemtooltip['buffed_de']['icon']=true;
		$itemtooltip['buffed_ru']['js']='http://www.buffed.de/js/buffed-ext-wow-tooltips.js';
		$itemtooltip['buffed_ru']['link']='http://wowdata.buffed.ru/?i=';
		$itemtooltip['buffed_ru']['icon']=true;
		$itemtooltip['wowhead_www']['js']='http://www.wowhead.com/widgets/power.js';
		$itemtooltip['wowhead_www']['link']='http://www.wowhead.com/?item=';
		$itemtooltip['wowhead_www']['icon']=true;
		$itemtooltip['wowhead_de']['js']='http://www.wowhead.com/widgets/power.js';
		$itemtooltip['wowhead_de']['link']='http://de.wowhead.com/?item=';
		$itemtooltip['wowhead_de']['icon']=true;	
		$itemtooltip['wowhead_fr']['js']='http://www.wowhead.com/widgets/power.js';
		$itemtooltip['wowhead_fr']['link']='http://fr.wowhead.com/?item=';
		$itemtooltip['wowhead_fr']['icon']=true;	
		$itemtooltip['wowhead_es']['js']='http://www.wowhead.com/widgets/power.js';
		$itemtooltip['wowhead_es']['link']='http://es.wowhead.com/?item=';
		$itemtooltip['wowhead_es']['icon']=true;	
		$itemtooltip['wowhead_ru']['js']='http://www.wowhead.com/widgets/power.js';
		$itemtooltip['wowhead_ru']['link']='http://ru.wowhead.com/?item=';
		$itemtooltip['wowhead_ru']['icon']=true;	
		$itemtooltip['allakhazam']['js']='http://common.allakhazam.com/shared/akztooltip.js';
		$itemtooltip['allakhazam']['link']='http://wow.allakhazam.com/db/item.html?witem=';
		$itemtooltip['allakhazam']['icon']=true;			
		$itemtooltip['wowdb']['js']='http://www.wowdb.com/js/extooltips.js';
		$itemtooltip['wowdb']['link']='http://www.wowdb.com/item.aspx?id=';
		$itemtooltip['wowdb']['icon']=true;
		$itemtooltip['armorylight']['js']='http://www.armory-light.com/powered.js';
		$itemtooltip['armorylight']['link']='http://www.armory-light.com/items/';
		$itemtooltip['armorylight']['icon']=true;
		$itemtooltip['pluendermeister']['js']='http://www.pluendermeister.de/sec/300301/pmtp_v0003.js';
		$itemtooltip['pluendermeister']['link']='http://www.pluendermeister.de/?s1=';
		$itemtooltip['pluendermeister']['icon']=false;
		$itemtooltip['thottbot']['js']='http://i.thottbot.com/power.js';
		$itemtooltip['thottbot']['link']='http://thottbot.com/i';
		$itemtooltip['thottbot']['icon']=true;
		$itemtooltip['wowarmory']['js']='';
		$itemtooltip['wowarmory']['link']='http://'.$cfg['wowarmoryregion'].'.wowarmory.com/item-info.xml?i=';
		$itemtooltip['wowarmory']['icon']=true;

		if ($itemid=="js") {
			if (!empty($itemtooltip[$cfg['itemlinktip']]['js'])) 
				wp_enqueue_script($cfg['itemlinktip'],$itemtooltip[$cfg['itemlinktip']]['js'],'',WOWRAID_VERSION,true);
			return;
		}

		$item = $wpdb->get_row("SELECT * FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
		if ($wpdb->num_rows==0)
			return false;
		
		$link="";
		if ($itemtooltip[$cfg['itemlinktip']]['icon'] and is_file(WP_PLUGIN_DIR."/".WOWRAID_PLUGIN_DIR."/images/items/".$item->Icon.".png"))
			$link="<img style=\"vertical-align:middle;width:16px;height:16px;\" src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/items/".$item->Icon.".png\" />";
		elseif ($itemtooltip[$cfg['itemlinktip']]['icon'])
			$link="<img style=\"vertical-align:middle;width:16px;height:16px;\" src=\"http://".$cfg['wowarmoryregion'].".wowarmory.com/wow-icons/_images/21x21/".$item->Icon.".png\" />";
		if ($itemtooltip[$cfg['itemlinktip']]['icon'] and !empty($link))
			$link="<a href=\"".$itemtooltip[$cfg['itemlinktip']]['link'].$item->ID."\" target=\"_blank\">".$link."</a> ";
		$link.="<a style=\"color:".$cfg['qualitycolor'][$item->Quality].";\" href=\"".$itemtooltip[$cfg['itemlinktip']]['link'].$item->ID."\" target=\"_blank\">".$item->Name."</a>";
		return $link;
	}
	
	function wowraid_register_cahr_form() {		
		$cfg=get_option('wowraid_cfg');
		echo "\t<p>\n";
		echo "\t\t<label>".__('WoW Char Name','wowraid')."<br />\n";
		echo "\t\t<input type=\"text\" name=\"user_char\" id=\"user_char\" class=\"input char_identifier\" value=\"".esc_attr(stripslashes($_POST['user_char']))."\" size=\"25\" tabindex=\"21\" /></label>\n";
		echo "\t</p>\n";
		if (!empty($cfg['regpassword'])) {
			echo "\t<p>\n";
			echo "\t\t<label>".__('Regestration Password','wowraid')."<br />\n";
			echo "\t\t<input type=\"password\" name=\"regpassword\" id=\"regpassword\" class=\"input char_identifier\" size=\"25\" tabindex=\"22\" /></label>\n";
			echo "\t</p>\n";	
		}
	}
	
	function wowraid_register_user_register($user_id) {
		global $wpdb;
		if (!empty($_POST['user_char'])) 
			$wpdb->update( $wpdb->wowraid_chars, array( 'userID' =>$user_id ), array( 'Name' => $_POST['user_char'] ), array( '%d' ), array( '%s' ) );	
	}	
	
	function wowraid_register_cahr_errors($errors) {
		global $wpdb;
		$cfg=get_option('wowraid_cfg');
		if (!empty($_POST['user_char'])) {
			$char = $wpdb->get_row("SELECT Name,userID FROM $wpdb->wowraid_chars WHERE Name = '".$_POST['user_char']."'");
			if (!empty($char->userID))
				$errors->add('invalid_char', __('<strong>ERROR</strong>: ', 'wowraid') . __('Char allredy assignt to a User', 'wowraid'));
			if ($wpdb->num_rows==0) {
				if (!wowraid_get_char_data($_POST['user_char']))
					$errors->add('invalid_char_get', __('<strong>ERROR</strong>: ', 'wowraid') . __('Char data can\'t reseve from WoW Armory', 'wowraid'));
			}
			if (!empty($cfg['regpassword'])) {
				if ($cfg['regpassword']!=$_POST['regpassword'])
					$errors->add('invalid_char_get', __('<strong>ERROR</strong>: ', 'wowraid') . __('Worng Password for Regestration', 'wowraid'));
			}
		}
		return $errors;	
	}	
	
	// add all action and so on only if plugin loaded.
	function wowraid_init() {
		//add Menu
		add_action('admin_menu', 'wowraid_menu_entry');
		//add filter for Items and more in content
		add_filter('the_content', 'wowraid_the_content');
		//For Tooltips on Page
		add_action('wp_head', 'wowraid_wp_head',1);
		//Action fpr WP Login
		add_action('login_head', 'wowraid_wp_login_head');
		//Action for Char Updates
		add_action('wowraid_char_update', 'wowraid_update_chars');
		//Actions for register form
		add_action('user_register', 'wowraid_register_user_register');
		add_action('register_form', 'wowraid_register_cahr_form',8);
		add_filter('registration_errors', 'wowraid_register_cahr_errors');
		//Additional links on the plugin page
		if (current_user_can('install_plugins')) {
			add_filter('plugin_action_links_'.WOWRAID_PLUGIN_DIR.'/wowraid.php', 'wowraid_plugin_options_link');		
			add_filter('plugin_row_meta', 'wowraid_plugin_links',10,2);
		}
	} 	

?>