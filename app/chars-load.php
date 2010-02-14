<?PHP 
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');
global $wpdb;

switch ($_GET['action']) {
	case 'update' :
		if (isset($_GET['char_id']) and !empty($_GET['char_id'])) {
			check_admin_referer('update-char_' . $_GET['char_id']);
			$char = $wpdb->get_row("SELECT Name FROM,ID $wpdb->wowraid_chars WHERE ID = ".$_GET['char_id']);
			if (wowraid_get_char_data($char->ID))
				$wowraid_message=sprintf(__('Char "%s" Updated from WoW Armory','wowraid'),$char->Name);
			else 
				$wowraid_message=sprintf(__('Char "%s" could not Updated from WoW Armory','wowraid'),$char->Name);
		}
		if (is_array($_GET['charcheck'])) {
			check_admin_referer('WoWRaidChars');
			foreach ($_GET['charcheck'] as $charid) {
				$char = $wpdb->get_row("SELECT Name,ID FROM $wpdb->wowraid_chars WHERE ID = ".$charid);
				if (wowraid_get_char_data($char->ID))
					$wowraid_message.=sprintf(__('Char "%s" Updated from WoW Armory','wowraid'),$char->Name)."<br />";
				else 
					$wowraid_message.=sprintf(__('Char "%s" could not Updated from WoW Armory','wowraid'),$char->Name)."<br />";				
			}
		}
		break;
	case 'delete' :
		if (isset($_GET['char_id']) and !empty($_GET['char_id'])) {
			check_admin_referer('delete-char_' . $_GET['char_id']);
			$char = $wpdb->get_row("SELECT Name,ID FROM $wpdb->wowraid_chars WHERE ID = ".$_GET['char_id']);
			$wpdb->query("DELETE FROM $wpdb->wowraid_chars WHERE ID = ".$char->ID);
			$wowraid_message=sprintf(__('Char "%s" Deleted','wowraid'),$char->Name);
		}
		if (is_array($_GET['charcheck'])) {
			check_admin_referer('WoWRaidChars');
			foreach ($_GET['charcheck'] as $charid) {
				$char = $wpdb->get_row("SELECT Name,ID FROM $wpdb->wowraid_chars WHERE ID = ".$charid);
				$wpdb->query("DELETE FROM $wpdb->wowraid_chars WHERE ID = ".$char->ID);
				$wowraid_message.=sprintf(__('Char "%s" Deleted','wowraid'),$char->Name)."<br />";			
			}
		}
		break;
}


?>