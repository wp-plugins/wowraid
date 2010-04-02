<?PHP
// don't load directly 
if ( !defined('ABSPATH')) 
	die('-1');	
?>	
<div class="wrap"> 
	<div id="icon-options-general" class="icon32"><br /></div> 
<h2><?PHP _e('WoW Raid Settings','wowraid'); ?></h2> 
 
<form method="post">
<input type="hidden" name="page" value="WoWRaidSettings">
<?PHP
wp_nonce_field('WoWRaidSettings');
$cfg=get_option('wowraid_cfg');
?>
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="guild"><?PHP _e('Guild Name','wowraid'); ?></label></th> 
<td><input name="guild" type="text" id="guild" value="<?PHP echo $cfg['guild']; ?>" class="regular-text" /></td> 
</tr>
<tr valign="top"> 
<th scope="row"><label for="twinkguild"><?PHP _e('Twink Guild Name','wowraid'); ?></label></th> 
<td><input name="twinkguild" type="text" id="twinkguild" value="<?PHP echo $cfg['twinkguild']; ?>" class="regular-text" /></td> 
</tr>
<tr valign="top"> 
<th scope="row"><label for="realm"><?PHP _e('Guild on Realm','wowraid'); ?></label></th> 
<td><input name="realm" type="text" id="realm" value="<?PHP echo $cfg['realm']; ?>" class="regular-text" /></td> 
</tr>
</table>

<h3><?PHP _e('WoW Armory','wowraid'); ?></h3> 
<p></p> 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="wowarmoryregion"><?PHP _e('Armory Region','wowraid'); ?></label></th> 
<td><select name="wowarmoryregion" id="wowarmoryregion" class="postform">
<option value="www"<?PHP selected($cfg['wowarmoryregion'],'www'); ?>><?PHP _e('Americas','wowraid'); ?></option>
<option value="eu"<?PHP selected($cfg['wowarmoryregion'],'eu'); ?>><?PHP _e('Europe','wowraid'); ?></option>
<option value="kr"<?PHP selected($cfg['wowarmoryregion'],'kr'); ?>><?PHP _e('Korea','wowraid'); ?></option>
<option value="cn"<?PHP selected($cfg['wowarmoryregion'],'cn'); ?>><?PHP _e('China','wowraid'); ?></option>
<option value="tw"<?PHP selected($cfg['wowarmoryregion'],'tw'); ?>><?PHP _e('Taiwan','wowraid'); ?></option>
</select></td></tr>
<tr valign="top"> 
<th scope="row"><label for="wowarmorylang"><?PHP _e('Armory Language','wowraid'); ?></label></th> 
<td><select name="wowarmorylang" id="wowarmorylang" class="postform">
<option value="en_gb"<?PHP selected($cfg['wowarmorylang'],'en_gb'); ?>><?PHP _e('English (EU)','wowraid'); ?></option>
<option value="en_us"<?PHP selected($cfg['wowarmorylang'],'en_us'); ?>><?PHP _e('English (US)','wowraid'); ?></option>
<option value="de_de"<?PHP selected($cfg['wowarmorylang'],'de_de'); ?>><?PHP _e('German','wowraid'); ?></option>
<option value="es_mx"<?PHP selected($cfg['wowarmorylang'],'es_mx'); ?>><?PHP _e('Spanish (AL)','wowraid'); ?></option>
<option value="es_es"<?PHP selected($cfg['wowarmorylang'],'es_es'); ?>><?PHP _e('Spanish (EU)','wowraid'); ?></option>
<option value="fr_fr"<?PHP selected($cfg['wowarmorylang'],'fr_fr'); ?>><?PHP _e('French','wowraid'); ?></option>
<option value="ru_ru"<?PHP selected($cfg['wowarmorylang'],'ru_ru'); ?>><?PHP _e('Russian','wowraid'); ?></option>
<option value="ko_kr"<?PHP selected($cfg['wowarmorylang'],'ko_kr'); ?>><?PHP _e('Korean','wowraid'); ?></option>
</select></td></tr>
</table> 

<h3><?PHP _e('Item Tooltips and Link to','wowraid'); ?></h3> 
<p>[item=12345] [char=name]</p> 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="itemlinktip"><?PHP _e('Use from','wowraid'); ?></label></th> 
<td><select name="itemlinktip" id="itemlinktip" class="postform">
<option value="wowhead_www"<?PHP selected($cfg['itemlinktip'],'wowhead_www'); ?>><?PHP _e('WoWHead (US)','wowraid'); ?></option>
<option value="wowhead_de"<?PHP selected($cfg['itemlinktip'],'wowhead_de'); ?>><?PHP _e('WoWHead (DE)','wowraid'); ?></option>
<option value="wowhead_fr"<?PHP selected($cfg['itemlinktip'],'wowhead_fr'); ?>><?PHP _e('WoWHead (FR)','wowraid'); ?></option>
<option value="wowhead_es"<?PHP selected($cfg['itemlinktip'],'wowhead_es'); ?>><?PHP _e('WoWHead (ES)','wowraid'); ?></option>
<option value="wowhead_ru"<?PHP selected($cfg['itemlinktip'],'wowhead_ru'); ?>><?PHP _e('WoWHead (RU)','wowraid'); ?></option>
<option value="buffed_com"<?PHP selected($cfg['itemlinktip'],'buffed_com'); ?>><?PHP _e('Buffed (US)','wowraid'); ?></option>
<option value="buffed_de"<?PHP selected($cfg['itemlinktip'],'buffed_de'); ?>><?PHP _e('Buffed (DE)','wowraid'); ?></option>
<option value="buffed_ru"<?PHP selected($cfg['itemlinktip'],'buffed_ru'); ?>><?PHP _e('Buffed (RU)','wowraid'); ?></option>
<option value="wowdb"<?PHP selected($cfg['itemlinktip'],'wowdb'); ?>><?PHP _e('WoWDB (curse.com)','wowraid'); ?></option>
<option value="allakhazam"<?PHP selected($cfg['itemlinktip'],'allakhazam'); ?>><?PHP _e('Allakhazam','wowraid'); ?></option>
<option value="armorylight"<?PHP selected($cfg['itemlinktip'],'armorylight'); ?>><?PHP _e('Armory Light','wowraid'); ?></option>
<option value="pluendermeister"<?PHP selected($cfg['itemlinktip'],'pluendermeister'); ?>><?PHP _e('Pluendermeister','wowraid'); ?></option>
<option value="thottbot"<?PHP selected($cfg['itemlinktip'],'thottbot'); ?>><?PHP _e('Thottbot','wowraid'); ?></option>
<option value="wowarmory"<?PHP selected($cfg['itemlinktip'],'wowarmory'); ?>><?PHP _e('WoW Armory (no Tooltip)','wowraid'); ?></option>
</select></td></tr>

<tr valign="top"> 
<th scope="row"><label for="qualitycolor"><?PHP _e('Item Quality Colors','wowraid'); ?></label></th> 
<td>
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][0]; ?>"><?PHP _e('Poor','wowraid'); ?>:</span> <input name="qualitycolor[0]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][0]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][1]; ?>"><?PHP _e('Common','wowraid'); ?>:</span> <input name="qualitycolor[1]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][1]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][2]; ?>"><?PHP _e('Uncommon','wowraid'); ?>:</span> <input name="qualitycolor[2]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][2]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][3]; ?>"><?PHP _e('Rare','wowraid'); ?>:</span> <input name="qualitycolor[3]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][3]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][4]; ?>"><?PHP _e('Epic','wowraid'); ?>:</span> <input name="qualitycolor[4]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][4]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][5]; ?>"><?PHP _e('Legendary','wowraid'); ?>:</span> <input name="qualitycolor[5]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][5]; ?>" class="medium-text" /><br />
<span style="background-color:black;color:<?PHP echo $cfg['qualitycolor'][7]; ?>"><?PHP _e('Heirloom','wowraid'); ?>:</span> <input name="qualitycolor[7]" type="text" id="guild" value="<?PHP echo $cfg['qualitycolor'][7]; ?>" class="medium-text" /><br />
</td> 
</tr>
</table> 


<h3><?PHP _e('WoW Armory Guild update','wowraid'); ?></h3> 
<p></p> 
<table class="form-table"> 

<tr valign="top"> 
<th scope="row"><label for="updateactivate"><?PHP _e('Automatic Char Updates','wowraid'); ?></label></th> 
<td>
<input name="updateactivate" type="checkbox" id="updateactivate" value="1"<?PHP checked($cfg['updateactivate'],true); ?> /> 
<?PHP _e('Activate automatic char Updates from Armory at','wowraid') ?> 
<select name="updatehour" id="updatehour" class="postform">
<?PHP for ($i=0; $i<=23;$i++) { ?>
<option value="<?PHP echo $i; ?>"<?PHP selected($cfg['updatehour'],$i); ?>><?PHP echo $i; ?></option>
<?PHP } ?>
</select>:
<select name="updateminute" id="updateminute" class="postform">
<?PHP for ($i=0; $i<=59;$i++) { ?>
<option value="<?PHP echo $i; ?>"<?PHP selected($cfg['updateminute'],$i); ?>><?PHP echo $i; ?></option>
<?PHP } ?>
</select> <?PHP _e('a clock.','wowraid'); ?>
</td></tr>

<tr valign="top"> 
<th scope="row"><label for="updatetwinkrank"><?PHP _e('Twinks Guild rank','wowraid'); ?></label></th> 
<td>
<input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#updatetwinkrank >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#updatetwinkrank > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
<select style="height:70px;font-size:11px;" name="updatetwinkrank[]" id="updatetwinkrank" multiple="multiple">
<?PHP for ($i=1; $i<=10;$i++) { ?>
	<option value="<?PHP echo $i; ?>"<?PHP selected(in_array($i,(array)$cfg['updatetwinkrank']),true); ?>><?PHP _e('Rank','wowraid'); ?> <?PHP echo $i; ?></option>
<?PHP } ?>     
</select>
</td></tr>
<tr valign="top"> 
<th scope="row"><label for="updatetwinklevel"><?PHP _e('Twinks are Chars under Level','wowraid'); ?></label></th> 
<td><select name="updatetwinklevel" id="updatetwinklevel" class="postform">
<?PHP for ($i=10; $i<=80;$i++) { ?>
<option value="<?PHP echo $i; ?>"<?PHP selected($cfg['updatetwinklevel'],$i); ?>><?PHP _e('Level','wowraid'); ?> <?PHP echo $i; ?></option>
<?PHP } ?>
</select></td></tr>
<tr valign="top"> 
<th scope="row"><label for="updateraiderrank"><?PHP _e('Raider Guild rank','wowraid'); ?></label></th> 
<td>
<input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#updateraiderrank >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#updateraiderrank > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
<select style="height:70px;font-size:11px;" name="updateraiderrank[]" id="updateraiderrank" multiple="multiple">
<?PHP for ($i=1; $i<=10;$i++) { ?>
	<option value="<?PHP echo $i; ?>"<?PHP selected(in_array($i,(array)$cfg['updateraiderrank']),true); ?>><?PHP _e('Rank','wowraid'); ?> <?PHP echo $i; ?></option>
<?PHP } ?>     
</select>
</td></tr>
</table> 

<h3><?PHP _e('Register for Blog','wowraid'); ?></h3> 
<p></p> 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="regpassword"><?PHP _e('Regestration Password','wowraid'); ?></label></th> 
<td><input name="regpassword" type="text" id="regpassword" value="<?PHP echo $cfg['regpassword']; ?>" class="medium-text" /></td> 
</tr>
</table> 

<h3><?PHP _e('Display Guild Chars','wowraid'); ?></h3> 
<p>[show_guild_chars]</p> 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="displayguild"><?PHP _e('Chars to display from Guild','wowraid'); ?></label></th> 
<td> 
<input name="guildcharsraider" type="checkbox" id="guildcharsraider" value="1"<?PHP checked($cfg['guildcharsraider'],true); ?> /> <?PHP _e('Raider only','wowraid') ?><br />
<input name="guildcharstwinks" type="checkbox" id="guildcharstwinks" value="1"<?PHP checked($cfg['guildcharstwinks'],true); ?> /> <?PHP _e('No Twinks','wowraid') ?>
</td>
<tr valign="top"> 
<th scope="row"><label for="guildcharsdetailed"><?PHP _e('Display detailed','wowraid'); ?></label></th> 
<td> 
<input name="guildcharsdetailed" type="checkbox" id="guildcharsdetailed" value="1"<?PHP checked($cfg['guildcharsdetailed'],true); ?> /> <?PHP _e('Detailed Guild list','wowraid') ?><br />
</td> 
</tr>
</table> 


<h3><?PHP _e('Roles','wowraid'); ?></h3> 
<p></p> 
<table class="form-table"> 
	<tr class="form-field">
		<th scope="row"><label for="rolesettings"><?php _e('Change Settings'); ?></label></th>
		<td><select name="rolesettings" id="rolesettings">
			<option value="0"<?PHP selected($cfg['rolesettings'],'0'); ?>><?PHP _e('Level 0','wowraid'); ?> (<?PHP _e('Subscriber'); ?>)</option>
			<option value="1"<?PHP selected($cfg['rolesettings'],'1'); ?>><?PHP _e('Level 1','wowraid'); ?> (<?PHP _e('Contributor'); ?>)</option>
			<option value="2"<?PHP selected($cfg['rolesettings'],'2'); ?>><?PHP _e('Level 2','wowraid'); ?> (<?PHP _e('Author'); ?>)</option>
			<option value="3"<?PHP selected($cfg['rolesettings'],'3'); ?>><?PHP _e('Level 3','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="4"<?PHP selected($cfg['rolesettings'],'4'); ?>><?PHP _e('Level 4','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="5"<?PHP selected($cfg['rolesettings'],'5'); ?>><?PHP _e('Level 5','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="6"<?PHP selected($cfg['rolesettings'],'6'); ?>><?PHP _e('Level 6','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="7"<?PHP selected($cfg['rolesettings'],'7'); ?>><?PHP _e('Level 7','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="8"<?PHP selected($cfg['rolesettings'],'8'); ?>><?PHP _e('Level 8','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="9"<?PHP selected($cfg['rolesettings'],'9'); ?>><?PHP _e('Level 9','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="10"<?PHP selected($cfg['rolesettings'],'10'); ?>><?PHP _e('Level 10','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			</select>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="rolechars"><?php _e('Edit Chars'); ?></label></th>
		<td><select name="rolechars" id="rolechars">
			<option value="0"<?PHP selected($cfg['rolechars'],'0'); ?>><?PHP _e('Level 0','wowraid'); ?> (<?PHP _e('Subscriber'); ?>)</option>
			<option value="1"<?PHP selected($cfg['rolechars'],'1'); ?>><?PHP _e('Level 1','wowraid'); ?> (<?PHP _e('Contributor'); ?>)</option>
			<option value="2"<?PHP selected($cfg['rolechars'],'2'); ?>><?PHP _e('Level 2','wowraid'); ?> (<?PHP _e('Author'); ?>)</option>
			<option value="3"<?PHP selected($cfg['rolechars'],'3'); ?>><?PHP _e('Level 3','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="4"<?PHP selected($cfg['rolechars'],'4'); ?>><?PHP _e('Level 4','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="5"<?PHP selected($cfg['rolechars'],'5'); ?>><?PHP _e('Level 5','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="6"<?PHP selected($cfg['rolechars'],'6'); ?>><?PHP _e('Level 6','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="7"<?PHP selected($cfg['rolechars'],'7'); ?>><?PHP _e('Level 7','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="8"<?PHP selected($cfg['rolechars'],'8'); ?>><?PHP _e('Level 8','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="9"<?PHP selected($cfg['rolechars'],'9'); ?>><?PHP _e('Level 9','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="10"<?PHP selected($cfg['rolechars'],'10'); ?>><?PHP _e('Level 10','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			</select>
		</td>
	</tr>	
	<tr class="form-field">
		<th scope="row"><label for="roleitems"><?php _e('Edit Items'); ?></label></th>
		<td><select name="roleitems" id="roleitems">
			<option value="0"<?PHP selected($cfg['roleitems'],'0'); ?>><?PHP _e('Level 0','wowraid'); ?> (<?PHP _e('Subscriber'); ?>)</option>
			<option value="1"<?PHP selected($cfg['roleitems'],'1'); ?>><?PHP _e('Level 1','wowraid'); ?> (<?PHP _e('Contributor'); ?>)</option>
			<option value="2"<?PHP selected($cfg['roleitems'],'2'); ?>><?PHP _e('Level 2','wowraid'); ?> (<?PHP _e('Author'); ?>)</option>
			<option value="3"<?PHP selected($cfg['roleitems'],'3'); ?>><?PHP _e('Level 3','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="4"<?PHP selected($cfg['roleitems'],'4'); ?>><?PHP _e('Level 4','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="5"<?PHP selected($cfg['roleitems'],'5'); ?>><?PHP _e('Level 5','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="6"<?PHP selected($cfg['roleitems'],'6'); ?>><?PHP _e('Level 6','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="7"<?PHP selected($cfg['roleitems'],'7'); ?>><?PHP _e('Level 7','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="8"<?PHP selected($cfg['roleitems'],'8'); ?>><?PHP _e('Level 8','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="9"<?PHP selected($cfg['roleitems'],'9'); ?>><?PHP _e('Level 9','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="10"<?PHP selected($cfg['roleitems'],'10'); ?>><?PHP _e('Level 10','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			</select>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="roleplaner"><?php _e('Manage Raids'); ?></label></th>
		<td><select name="roleplaner" id="roleplaner">
			<option value="0"<?PHP selected($cfg['roleplaner'],'0'); ?>><?PHP _e('Level 0','wowraid'); ?> (<?PHP _e('Subscriber'); ?>)</option>
			<option value="1"<?PHP selected($cfg['roleplaner'],'1'); ?>><?PHP _e('Level 1','wowraid'); ?> (<?PHP _e('Contributor'); ?>)</option>
			<option value="2"<?PHP selected($cfg['roleplaner'],'2'); ?>><?PHP _e('Level 2','wowraid'); ?> (<?PHP _e('Author'); ?>)</option>
			<option value="3"<?PHP selected($cfg['roleplaner'],'3'); ?>><?PHP _e('Level 3','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="4"<?PHP selected($cfg['roleplaner'],'4'); ?>><?PHP _e('Level 4','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="5"<?PHP selected($cfg['roleplaner'],'5'); ?>><?PHP _e('Level 5','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="6"<?PHP selected($cfg['roleplaner'],'6'); ?>><?PHP _e('Level 6','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="7"<?PHP selected($cfg['roleplaner'],'7'); ?>><?PHP _e('Level 7','wowraid'); ?> (<?PHP _e('Editor'); ?>)</option>
			<option value="8"<?PHP selected($cfg['roleplaner'],'8'); ?>><?PHP _e('Level 8','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="9"<?PHP selected($cfg['roleplaner'],'9'); ?>><?PHP _e('Level 9','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			<option value="10"<?PHP selected($cfg['roleplaner'],'10'); ?>><?PHP _e('Level 10','wowraid'); ?> (<?PHP _e('Admin'); ?>)</option>
			</select>
		</td>
	</tr>
	
</table> 


<p class="submit"> 
<input type="submit" name="Submit" class="button-primary" value="<?PHP _e('Save Changes'); ?>" /> 
</p> 
</form> 
 
</div> 
 