<?PHP
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');	
?>	
<div class="wrap"> 
	<div id="icon-options-general" class="icon32"><br /></div> 
<h2><?PHP _e('WoW Raid Edit Item','wowraid'); ?></h2> 
 
<form method="post">
<input type="hidden" name="page" value="WoWRaidSettings">
<input type="hidden" name="action" value="edit">
<?PHP
wp_nonce_field('WoWRaidItemsEdit');

if (!empty($_REQUEST['item_id']))
	$item = $wpdb->get_row("SELECT * FROM $wpdb->wowraid_items WHERE ID = ".$_REQUEST['item_id'],'ARRAY_A');
if (!empty($_POST['ItemID']) and empty($_REQUEST['item_id'])) {
	$item['ID']=$_POST['ItemID'];
	$olditemid=$_POST['ItemOldID'];
	$item['Name']=$_POST['ItemName'];
	$item['Quality']=$_POST['ItemQuality'];
	$item['Icon']=$_POST['ItemIcon'];
	$item['DKP']=$_POST['ItemDKP'];
}
if (empty($olditemid))
	$olditemid=$_REQUEST['item_id'];
?>
<input type="hidden" name="ItemOldID" value="<?PHP echo $olditemid; ?>">
<table class="form-table">
<tr valign="top"> 
<th scope="row"><label for="ItemID"><?PHP _e('Item ID','wowraid'); ?></label></th> 
<td><input name="ItemID" type="text" id="ItemID" value="<?PHP echo $item['ID']; ?>" class="medium-text" />
<input type="submit" name="Submit" class="button" value="<?PHP _e('Get Data from Armory'); ?>" /> 
</td> 
</tr>

<tr valign="top"> 
<th scope="row"><label for="ItemName"><?PHP _e('Item Name','wowraid'); ?></label></th> 
<td><input name="ItemName" type="text" id="ItemName" value="<?PHP echo $item['Name']; ?>" class="regular-text" /></td> 
</tr>

<tr valign="top"> 
<th scope="row"><label for="ItemQuality"><?PHP _e('Item Quality','wowraid'); ?></label></th> 
<td><select name="ItemQuality" id="ItemQuality" class="postform">
<?PHP foreach (wowraid_ids_to_name(-1,'quality') as $QualityID => $QualityName) {?>
	<option value="<?PHP echo $QualityID; ?>"<?PHP selected($item['Quality'],$QualityID); ?>><?PHP echo $QualityName; ?></option>
<?PHP } ?>
</select></td></tr>

<tr valign="top"> 
<th scope="row"><label for="ItemIcon"><?PHP _e('Item Icon','wowraid'); ?></label></th> 
<td><input name="ItemIcon" type="text" id="ItemIcon" value="<?PHP echo $item['Icon']; ?>" class="medium-text" /></td> 
</tr>

<tr valign="top"> 
<th scope="row"><label for="ItemDKP"><?PHP _e('Item DKP','wowraid'); ?></label></th> 
<td><input name="ItemDKP" type="text" id="ItemDKP" value="<?PHP echo $item['DKP']; ?>" class="medium-text" /></td> 
</tr>

</table> 

<p class="submit"> 
<input type="submit" name="Submit" class="button-primary" value="<?PHP _e('Save Changes'); ?>" /> 
</p> 
</form> 
</div> 