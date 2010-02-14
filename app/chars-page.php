<?PHP
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//get GET data
if (isset($_GET['paged'])) $paged=$_GET['paged'];
if (isset($_GET['order_by'])) $order_by=$_GET['order_by'];
if (isset($_GET['guildfilter'])) $guildfilter=$_GET['guildfilter'];
if (isset($_GET['charstatus'])) $charstatus=$_GET['charstatus'];
if (isset($_GET['ClassID'])) $ClassID=$_GET['ClassID'];
if (isset($_GET['s'])) $s=$_GET['s'];


//Odering
switch ($order_by) {
	case 'order_user' :
		$sqlorderby = 'ORDER BY userID';
		break;
	case 'order_class' :
		$sqlorderby = 'ORDER BY ClassID';
		break;
	case 'order_level' :
		$sqlorderby = 'ORDER BY Level';
		break;
	case 'order_points' :
		$sqlorderby = 'ORDER BY Points DESC';
		break;
	case 'order_race' :
		$sqlorderby = 'ORDER BY RaceID';
		break;
	case 'order_guild' :
		$sqlorderby = 'ORDER BY Guild';
		break;
	case 'order_update' :
		$sqlorderby = 'ORDER BY last_update';
		break;
	case 'order_faction' :
		$sqlorderby = 'ORDER BY FactionID';
		break;
	case 'order_name' :
	default :
		$sqlorderby = 'ORDER BY Level DESC,Name';
		$order_by = 'order_name';
		break;
}

//Guild Filter
if ( !isset($guildfilter) )
	$guildfilter = 'all';
	
//Sql where query
	$sqlwhere="";
	if (!empty($ClassID)) 
		$sqlwhere.="ClassID=".$_GET['ClassID']." ";
	if (!empty($s)) {
		if (!empty($sqlwhere))
			$sqlwhere.="AND ";
		$sqlwhere.="(Name LIKE '%".$s."%' or Guild LIKE '%".$s."%' or ProfessionName1 LIKE '%".$s."%' or ProfessionName2 LIKE '%".$s."%') ";
	}
	if ($guildfilter!='all') {
		if (!empty($sqlwhere))
			$sqlwhere.="AND ";
		$sqlwhere.="Guild='".$guildfilter."' ";
	}
	if ($charstatus=='raider') {
		if (!empty($sqlwhere))
			$sqlwhere.="AND ";
		$sqlwhere.="Raider=1 ";	
	}
	if ($charstatus=='twink') {
		if (!empty($sqlwhere))
			$sqlwhere.="AND ";
		$sqlwhere.="Twink=1 ";	
	}
	if (!empty($sqlwhere))
		$sqlwhere="WHERE ".$sqlwhere;

		

//Page links
$per_page = 'wowraid_chars_per_page';
$per_page = (int) get_user_option( $per_page );

if ( empty( $per_page ) || $per_page < 1 )
	$per_page = 20;

$pagenum = isset( $paged ) ? absint( $paged ) : 0;
if ( empty($pagenum) )
	$pagenum = 1;

	
$wpdb->query("SELECT Name FROM $wpdb->wowraid_chars ".$sqlwhere);
$num_chars = $wpdb->num_rows;
$num_pages = ceil($num_chars / $per_page);

$page_links = paginate_links( array(
	'base' => add_query_arg('paged', '%#%'),
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => $num_pages,
	'current' => $pagenum,
	'add_args' =>  array('ClassID'=> $ClassID,'order_by'=>$order_by,'s'=>$s,'guildfilter'=>$guildfilter,'charstatus'=>$charstatus)
));

		
?>	

<div class="wrap">
<div id="icon-wowraid" class="icon32"><br /></div>
<h2><?php _e('WoW Raid Chars','wowraid') ?> <a href="<?PHP echo wp_nonce_url("admin.php?action=edit&amp;page=WoWRaidChars", 'WoWRaidCharsEdit') ?>" class="button add-new-h2"><?php echo esc_html_x('Add New Char','wowraid'); ?></a> <?php
if ( isset($s) && $s )
	printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( stripslashes($s) ) ); ?>
</h2>

<form id="char-filter" action="" method="get">
<input type="hidden" name="page" value="WoWRaidChars">
<input type="hidden" name="ClassID" value="<?PHP echo $ClassID; ?>">
<?php wp_nonce_field('WoWRaidChars'); ?>
<ul class="subsubsub">
<?php

$sqlwheresub='';
if (!empty($s))
	$sqlwheresub.=" AND (Name LIKE '%".$s."%' or Guild LIKE '%".$s."%' or ProfessionName1 LIKE '%".$s."%' or ProfessionName2 LIKE '%".$s."%')";
if ($guildfilter!='all')
	$sqlwheresub.=" AND Guild='".$guildfilter."'";
if ($charstatus=='raider')	
	$sqlwheresub.=" AND Raider=1";
if ($charstatus=='twink')	
	$sqlwheresub.=" AND Twink=1";

$wpdb->query("SELECT Name FROM $wpdb->wowraid_chars WHERE ClassID!=-1".$sqlwheresub);
$classcss = empty($classcss) && empty($ClassID) ? ' class="current"' : '';
$status_links[] = "<li><a href='admin.php?page=WoWRaidChars&amp;guildfilter=$guildfilter&amp;order_by=$order_by&amp;s=$s&amp;charstatus=$charstatus'$classcss>" . sprintf( __( 'All <span class="count">(%s)</span>', 'wowraid' ), number_format_i18n( $wpdb->num_rows ) ) . '</a>';

foreach ( wowraid_ids_to_name(-1, 'class') as $class => $classname ) {
	$classcss = '';

	if ( isset($ClassID) && $class == $ClassID )
		$classcss = ' class="current"';

	$wpdb->query("SELECT Name FROM $wpdb->wowraid_chars WHERE ClassID=".$class.$sqlwheresub);
	if ($wpdb->num_rows>0)
		$status_links[] = "<li><a href='admin.php?page=WoWRaidChars&amp;ClassID=$class&amp;guildfilter=$guildfilter&amp;order_by=$order_by&amp;s=$s&amp;charstatus=$charstatus'$classcss>" . sprintf( $classname.__( ' <span class="count">(%s)</span>', 'wowraid' ), number_format_i18n( $wpdb->num_rows ) ) . '</a>';
}
echo implode( " |</li>\n", $status_links ) . '</li>';
unset( $status_links );
?>
</ul>

<p class="search-box">
	<label class="screen-reader-text" for="cahr-search-input"><?php _e( 'Search Chars', 'wowraid' ); ?>:</label>
	<input type="text" id="cahr-search-input" name="s" value="<?php echo esc_html( stripslashes($s) ); ?>" />
	<input type="submit" value="<?php esc_attr_e( 'Search Chars', 'wowraid' ); ?>" class="button" />
</p>

<div class="tablenav">
<div class="alignleft actions">
<?php if (current_user_can($cfg['rolechars'])) { ?>
<select name="action">
<option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
<option value="update"><?php _e('Update'); ?></option>
<option value="delete"><?php _e('Delete'); ?></option>
</select>
<input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction" id="doaction" class="button-secondary action" />
<?php
}

$select_status = "<select name=\"charstatus\">\n";
$select_status .= '<option value="all"'  . (($charstatus == 'all') ? " selected='selected'" : '') . '>' . __('View all Status') . "</option>\n";
$select_status .= '<option value="raider"'  . (($charstatus == 'raider') ? " selected='selected'" : '') . '>' . __('Raider') . "</option>\n";
$select_status .= '<option value="twink"'  . (($charstatus == 'twink') ? " selected='selected'" : '') . '>' . __('Twinks') . "</option>\n";
$select_status .= "</select>\n";

$guilds = $wpdb->get_results("SELECT Guild FROM $wpdb->wowraid_chars WHERE Guild != '' GROUP BY Guild ORDER BY Guild",'ARRAY_A');
$select_guild = "<select name=\"guildfilter\">\n";
$select_guild .= '<option value="all"'  . (($guildfilter == 'all') ? " selected='selected'" : '') . '>' . __('View all Guilds') . "</option>\n";
$select_guild .= '<option value=""'  . (($guildfilter == '') ? " selected='selected'" : '') . '>' . __('Without a Guild') . "</option>\n";
foreach ((array) $guilds as $guild)
	$select_guild .= '<option value="' . $guild['Guild'] . '"' . (($guild['Guild'] == $guildfilter) ? " selected='selected'" : '') . '>' . $guild['Guild'] . "</option>\n";
$select_guild .= "</select>\n";

$select_order = "<select name=\"order_by\">\n";
$select_order .= '<option value="order_name"' . (($order_by == 'order_name') ? " selected='selected'" : '') . '>' .  __('Order by Name') . "</option>\n";
$select_order .= '<option value="order_user"' . (($order_by == 'order_user') ? " selected='selected'" : '') . '>' .  __('Order by User') . "</option>\n";
$select_order .= '<option value="order_race"' . (($order_by == 'order_race') ? " selected='selected'" : '') . '>' .  __('Order by Race') . "</option>\n";
$select_order .= '<option value="order_faction"' . (($order_by == 'order_faction') ? " selected='selected'" : '') . '>' .  __('Order by Faction') . "</option>\n";
$select_order .= '<option value="order_class"' . (($order_by == 'order_class') ? " selected='selected'" : '') . '>' .  __('Order by Class') . "</option>\n";
$select_order .= '<option value="order_level"' . (($order_by == 'order_level') ? " selected='selected'" : '') . '>' .  __('Order by Level') . "</option>\n";
$select_order .= '<option value="order_guild"' . (($order_by == 'order_guild') ? " selected='selected'" : '') . '>' .  __('Order by Guild') . "</option>\n";
$select_order .= '<option value="order_points"' . (($order_by == 'order_points') ? " selected='selected'" : '') . '>' .  __('Order by Achievement Points') . "</option>\n";
$select_order .= '<option value="order_update"' . (($order_by == 'order_update') ? " selected='selected'" : '') . '>' .  __('Order by Last Update') . "</option>\n";
$select_order .= "</select>\n";

echo $select_status;
echo $select_guild;
echo $select_order;

?>
<input type="submit" id="post-query-submit" value="<?php esc_attr_e('Filter'); ?>" class="button-secondary" />

</div>

<?php if ( $page_links ) { ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
	number_format_i18n( ( $pagenum - 1 ) * $per_page + 1 ),
	number_format_i18n( min( $pagenum * $per_page, $num_chars ) ),
	number_format_i18n( $num_chars ),
	$page_links
); echo $page_links_text; ?></div>
<?php } ?>
<div class="clear"></div>
</div>

<div class="clear"></div>


<table class="widefat" cellspacing="0">
	<thead>
	<tr>
<?php print_column_headers($current_screen); ?>
	</tr>
	</thead>
	
	<tfoot>
	<tr>
<?php print_column_headers($current_screen, false); ?>
	</tr>
	</tfoot>

	<tbody>

<?PHP
	$chars_columns = get_column_headers($current_screen);
	$hidden = get_hidden_columns($current_screen);
	
	$chars = $wpdb->get_results("SELECT * FROM $wpdb->wowraid_chars $sqlwhere $sqlorderby LIMIT ".($pagenum*$per_page-$per_page).",".$per_page,'ARRAY_A');
	foreach($chars as $char) {
		?><tr id="char-<?php echo $char['ID']; ?>" valign="middle"><?php
		foreach($chars_columns as $column_name=>$column_display_name) {
			$class = "class=\"column-$column_name\"";

			$style = '';
			if ( in_array($column_name, $hidden) )
				$style = ' style="display:none;"';

			$attributes = "$class$style";
			
			switch($column_name) {
				case 'cb':
					echo '<th scope="row" class="check-column"><input type="checkbox" name="charcheck[]" value="'. esc_attr($char['ID']) .'" /></th>';
					break;
				case 'name':
					echo "<td $attributes><strong>".wowraid_char_display($char['Name'],false,false)."</strong>";
					if (current_user_can($cfg['rolechars']) or $user_ID==$char['userID']) {
						$actions = array();
						$actions['edit'] = "<a href=\"" . wp_nonce_url("admin.php?action=edit&amp;page=WoWRaidChars&amp;char_id=$char[ID]", 'WoWRaidCharsEdit') . "\">" . __('Edit') . "</a>";
						$actions['update'] = "<a href=\"" . wp_nonce_url("admin.php?action=update&amp;page=WoWRaidChars&amp;char_id=$char[ID]", 'update-char_' . $char['ID']) . "\">" . __('Update','wowraid') . "</a>";
						$actions['delete'] = "<a class=\"submitdelete\" href=\"" . wp_nonce_url("admin.php?action=delete&amp;page=WoWRaidChars&amp;char_id=$char[ID]", 'delete-char_' . $char['ID']) . "\" onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this Char '%s'\n  'Cancel' to stop, 'OK' to delete."), $char['Name'] )) . "') ) { return true;}return false;\">" . __('Delete') . "</a>";
						$action_count = count($actions);
						$i = 0;
						echo '<br /><div class="row-actions">';
						foreach ( $actions as $action => $linkaction ) {
							++$i;
							( $i == $action_count ) ? $sep = '' : $sep = ' | ';
							echo "<span class='$action'>$linkaction$sep</span>";
						}
						echo '</div>';
					}
					echo '</td>';
					break;
				case 'user':
					$user_info =get_userdata($char['userID']);
					echo "<td $attributes>$user_info->display_name</td>";
					break;
				case 'status':
					echo "<td $attributes>";
					$time_diff = time() - $char['last_update'];
					echo "<span title=\"".__('Last Update','wowraid')."\">";
					if ( $time_diff > 0 && $time_diff < 24*60*60 )
						echo sprintf( __('%s ago'), human_time_diff( $char['last_update'] ) );
					else
						echo date_i18n(__('Y/m/d'),$char['last_update']);
					echo "</span><br />";
					
					if ($char['Twink']) {
						_e('Twink','wowraid');
					}
					if ($char['Raider']) {
						_e('Raider','wowraid');
					}

					echo "</td>";
					break;					
				case 'level':
					echo "<td $attributes>$char[Level]</td>";
					break;
				case 'type':
					echo "<td $attributes>";
					echo "<img src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/faction/".$char['FactionID'].".gif\" title=\"".wowraid_ids_to_name($char['FactionID'],'FactionID')."\" height=\"18\" width=\"18\" /> ";
					echo "<img src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/race/".$char['RaceID']."-".$char['GenderID'].".gif\" title=\"".wowraid_ids_to_name($char['RaceID'],'RaceID')."\" height=\"18\" width=\"18\" /> ";
					echo "<img src=\"".WP_PLUGIN_URL."/".WOWRAID_PLUGIN_DIR."/images/class/".$char['ClassID'].".gif\" title=\"".wowraid_ids_to_name($char['ClassID'],'ClassID')."\" height=\"18\" width=\"18\" /> ";
					echo "</td>";
					break;
				case 'points':
					echo "<td $attributes>$char[Points]</td>";
					break;
				case 'professions':
					echo "<td $attributes>";
					if (!empty($char['ProfessionLevel1']))
						echo "<span title=\"".__('Skill:','wowraid')." ".$char['ProfessionLevel1']."\">".$char['ProfessionName1']."</span><br />";
					if (!empty($char['ProfessionLevel2']))
						echo "<span title=\"".__('Skill:','wowraid')." ".$char['ProfessionLevel2']."\">".$char['ProfessionName2']."</span>";
					echo "</td>";
					break;
				case 'talent':
					echo "<td $attributes>";
					if (!empty($char['TalentSpec1'])) {
						$spec=wowraid_talent_spec($char['ClassID'],$char['TalentSpec1']); 
						echo "<span title=\"".$char['TalentSpec1']."\">".$spec['name']."</span><br />";
					}
					if (!empty($char['TalentSpec2'])) {
						$spec=wowraid_talent_spec($char['ClassID'],$char['TalentSpec2']); 
						echo "<span title=\"".$char['TalentSpec2']."\">".$spec['name']."</span>";
					}
					echo "</td>";
					break;				
			}
		}
		echo "\n    </tr>\n";
	} 
	?>
	</tbody>
</table>



<div class="tablenav">
<?php
if ( $page_links )
	echo "<div class='tablenav-pages'>$page_links_text</div>";
?>
</div>
</form>
<br class="clear" />
</div> 