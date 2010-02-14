<?PHP 
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

switch ($_REQUEST['action']) {
	case 'update' :
		if (isset($_GET['item_id'])) {
			check_admin_referer('update-item_' . $_GET['item_id']);
			$item = $wpdb->get_row("SELECT Name,ID FROM $wpdb->wowraid_items WHERE ID = ".$_GET['item_id']);
			if (wowraid_get_item_data($item->ID))
				$wowraid_message=sprintf(__('Item "%s" Updated from WoW Armory','wowraid'),$item->Name);
			else 
				$wowraid_message=sprintf(__('Item "%s" could not Updated from WoW Armory','wowraid'),$item->Name);
		}
		if (is_array($_GET['itemcheck'])) {
			check_admin_referer('WoWRaidItems');
			foreach ($_GET['itemcheck'] as $itemid) {
				$item = $wpdb->get_row("SELECT Name,ID FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
				if (wowraid_get_item_data($item->ID))
					$wowraid_message.=sprintf(__('Item "%s" Updated from WoW Armory','wowraid'),$item->Name)."<br />";
				else 
					$wowraid_message.=sprintf(__('Item "%s" could not Updated from WoW Armory','wowraid'),$item->Name)."<br />";				
			}
		}
		break;
	case 'delete' :
		if (isset($_GET['item_id'])) {
			check_admin_referer('delete-item_' . $_GET['item_id']);
			$item = $wpdb->get_row("SELECT Name FROM $wpdb->wowraid_items WHERE ID = ".$_GET['item_id']);
			$wpdb->query("DELETE FROM $wpdb->wowraid_items WHERE ID = ".$_GET['item_id']);
			$wowraid_message=sprintf(__('Item "%s" Deleted','wowraid'),$item->Name);
		}
		if (is_array($_GET['itemcheck'])) {
			check_admin_referer('WoWRaidItems');
			foreach ($_GET['itemcheck'] as $itemid) {
				$item = $wpdb->get_row("SELECT Name FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
				$wpdb->query("DELETE FROM $wpdb->wowraid_items WHERE ID = ".$itemid);
				$wowraid_message.=sprintf(__('Item "%s" Deleted','wowraid'),$item->Name)."<br />";			
			}
		}
		break;
	case 'edit' :
		check_admin_referer('WoWRaidItemsEdit');
		if ($_POST['Submit']==__('Save Changes')) {
			if (empty($_POST['ItemOldID'])) 
				$wpdb->insert( $wpdb->wowraid_items, array( 'ID' => $_POST['ItemID'], 'Name' => $_POST['ItemName'], 'Icon' => $_POST['ItemIcon'], 'Quality' => $_POST['ItemQuality'], 'DKP'=>$_POST['ItemDKP'] ), array( '%d', '%s', '%s', '%d', '%d' ) );
			else 
				$wpdb->update( $wpdb->wowraid_items, array( 'ID' => $_POST['ItemID'], 'Name' => $_POST['ItemName'], 'Icon' => $_POST['ItemIcon'], 'Quality' => $_POST['ItemQuality'], 'DKP'=>$_POST['ItemDKP'] ), array( 'ID' => $_POST['ItemOldID'] ), array( '%d', '%s', '%s', '%d', '%d' ), array( '%d' ) );
			$wowraid_message=sprintf(__('Item "%s" Changes Saved','wowraid'),$_POST['ItemName']);
			$_REQUEST['action']='';
		}
		if ($_POST['Submit']==__('Get Data from Armory')) {
			if (wowraid_get_item_data($_POST['ItemID'])) {
				$wpdb->update( $wpdb->wowraid_items, array( 'DKP'=>$_POST['ItemDKP'] ), array( 'ID' => $_POST['ItemID'] ), array( '%d' ), array( '%d' ) );
				$wowraid_message=sprintf(__('Data for Item "%s" reseved from Armory and Saved','wowraid'),$_POST['ItemID']);
				$_REQUEST['item_id']=$_POST['ItemID'];
			} else {
				$wowraid_message=sprintf(__('Can\'t recive data for Item "%s" from Armory','wowraid'),$_POST['ItemID']);
			}
		}
	break;
}
?>