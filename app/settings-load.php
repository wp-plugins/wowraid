<?PHP

// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');	
	
if ($_POST['Submit']) {
	global $wowraid_message;
	check_admin_referer('WoWRaidSettings');
	$cfg=get_option('wowraid_cfg'); //Load Settings
	$cfg['guild']=$_POST['guild'];
	$cfg['twinkguild']=$_POST['twinkguild'];
	$cfg['realm']=$_POST['realm'];
	$cfg['wowarmoryregion']=$_POST['wowarmoryregion'];
	$cfg['wowarmorylang']=$_POST['wowarmorylang'];
	$cfg['itemlinktip']=$_POST['itemlinktip'];
	$cfg['updatetwinkrank']=$_POST['updatetwinkrank'];
	$cfg['updatetwinklevel']=$_POST['updatetwinklevel'];
	$cfg['updateraiderrank']=$_POST['updateraiderrank'];
	$cfg['updatehour']=$_POST['updatehour'];
	$cfg['updateminute']=$_POST['updateminute'];
	$cfg['regpassword']=$_POST['regpassword'];
	$cfg['guildcharsraider']=(array)$_POST['guildcharsraider'];
	$cfg['guildcharstwinks']=(array)$_POST['guildcharstwinks'];
	$cfg['guildcharsdetailed']=$_POST['guildcharsdetailed'];
	$cfg['rolesettings']=$_POST['rolesettings'];
	$cfg['rolechars']=$_POST['rolechars'];
	$cfg['roleitems']=$_POST['roleitems'];
	$cfg['roleplaner']=$_POST['roleplaner'];
	$cfg['qualitycolor'][0]=$_POST['qualitycolor'][0];
	$cfg['qualitycolor'][1]=$_POST['qualitycolor'][1];
	$cfg['qualitycolor'][2]=$_POST['qualitycolor'][2];
	$cfg['qualitycolor'][3]=$_POST['qualitycolor'][3];
	$cfg['qualitycolor'][4]=$_POST['qualitycolor'][4];
	$cfg['qualitycolor'][5]=$_POST['qualitycolor'][5];
	$cfg['qualitycolor'][7]=$_POST['qualitycolor'][7];
	
	if ($time=wp_next_scheduled('wowraid_char_update')) 
		wp_unschedule_event($time,'wowraid_char_update');	
	if(isset($_POST['updateactivate'])) {
		$cfg['updateactivate']=true;
		wp_schedule_event(mktime($cfg['updatehour'],$cfg['updateminute']), 'daily', 'wowraid_char_update');
	} else {
		$cfg['updateactivate']=false;
	}
	
	if (update_option('wowraid_cfg',$cfg))
		$wowraid_message=__('Settings changed', 'wowraid');
}
?>