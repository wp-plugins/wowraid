<?PHP
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//get GET data
if (isset($_GET['paged'])) $paged=$_GET['paged'];
if (isset($_GET['order_by'])) $order_by=$_GET['order_by'];
if (isset($_GET['QualityID'])) $QualityID=$_GET['QualityID'];
if (isset($_GET['s'])) $s=$_GET['s'];


//Odering
switch ($order_by) {
	case 'order_id' :
		$sqlorderby = 'ORDER BY ID DESC';
		break;
	case 'order_quality' :
		$sqlorderby = 'ORDER BY Quality DESC';
		break;
	case 'order_dkp' :
		$sqlorderby = 'ORDER BY DKP DESC';
		break;
	case 'order_name' :
	default :
		$sqlorderby = 'ORDER BY Name';
		$order_by = 'order_name';
		break;
}

//Sql where query
	$sqlwhere="";
	if (!empty($QualityID)) 
		$sqlwhere.="Quality=".$_GET['QualityID']." ";
	if (!empty($s)) {
		if (!empty($sqlwhere))
			$sqlwhere.="AND ";
		$sqlwhere.="(Name LIKE '%".$s."%' or ID LIKE '%".$s."%' or DKP LIKE '%".$s."%') ";
	}
	if (!empty($sqlwhere))
		$sqlwhere="WHERE ".$sqlwhere;

		

//Page links
$pagenum = isset( $paged ) ? absint( $paged ) : 0;
if ( empty($pagenum) )
	$pagenum = 1;
if ( empty( $per_page ) || $per_page < 1 )
	$per_page = 30;
	

$wpdb->query("SELECT ID FROM $wpdb->wowraid_items ".$sqlwhere);
$num_chars = $wpdb->num_rows;
$num_pages = ceil($num_chars / $per_page);

$page_links = paginate_links( array(
	'base' => add_query_arg('paged', '%#%'),
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => $num_pages,
	'current' => $pagenum,
	'add_args' =>  array('QualityID'=> $QualityID,'order_by'=>$order_by,'s'=>$s)
));

		
?>	

<div class="wrap">
<div id="icon-wowraid" class="icon32"><br /></div>
<h2><?php _e('WoW Raid Items','wowraid') ?>
<?PHP if (current_user_can($cfg['roleitems'])) { ?>
<a href="<?PHP echo wp_nonce_url("admin.php?action=edit&amp;page=WoWRaidItems", 'WoWRaidItemsEdit') ?>" class="button add-new-h2"><?php echo esc_html_x('Add New Item','wowraid'); ?></a> 
<?PHP }
if ( isset($s) && $s )
	printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( stripslashes($s) ) ); ?>
</h2>

<form id="char-filter" action="" method="get">
<input type="hidden" name="page" value="WoWRaidItems">
<input type="hidden" name="QualityID" value="<?PHP echo $QualityID; ?>">
<?php wp_nonce_field('WoWRaidItems'); ?>
<ul class="subsubsub">
<?php

$sqlwheresub='';
if (!empty($s))
	$sqlwheresub.=" AND (Name LIKE '%".$s."%' or ID LIKE '%".$s."%' or DKP LIKE '%".$s."%')";

$wpdb->query("SELECT Name FROM $wpdb->wowraid_items WHERE Quality!=-1".$sqlwheresub);
$classcss = empty($classcss) && !isset($QualityID) ? ' class="current"' : '';
$status_links[] = "<li><a href='admin.php?page=WoWRaidItems&amp;order_by=$order_by&amp;s=$s'$classcss>" . sprintf( __( 'All <span class="count">(%s)</span>', 'wowraid' ), number_format_i18n( $wpdb->num_rows ) ) . '</a>';

foreach ( wowraid_ids_to_name(-1, 'quality') as $quality => $qualityname ) {
	$classcss = '';

	if ( isset($QualityID) && $quality == $QualityID )
		$classcss = ' class="current"';

	$wpdb->query("SELECT Name FROM $wpdb->wowraid_items WHERE Quality=".$quality.$sqlwheresub);
	if ($wpdb->num_rows>0)
		$status_links[] = "<li><a href='admin.php?page=WoWRaidItems&amp;QualityID=$quality&amp;order_by=$order_by&amp;s=$s'$classcss>" . sprintf( $qualityname.__( ' <span class="count">(%s)</span>', 'wowraid' ), number_format_i18n( $wpdb->num_rows ) ) . '</a>';
}
echo implode( " |</li>\n", $status_links ) . '</li>';
unset( $status_links );
?>
</ul>

<p class="search-box">
	<label class="screen-reader-text" for="cahr-search-input"><?php _e( 'Search Chars', 'wowraid' ); ?>:</label>
	<input type="text" id="cahr-search-input" name="s" value="<?php echo esc_html( stripslashes($s) ); ?>" />
	<input type="submit" value="<?php esc_attr_e( 'Search Items', 'wowraid' ); ?>" class="button" />
</p>

<div class="tablenav">
<div class="alignleft actions">
<?PHP if (current_user_can($cfg['roleitems'])) {?>
<select name="action">
<option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
<option value="update"><?php _e('Update'); ?></option>
<option value="delete"><?php _e('Delete'); ?></option>
</select>
<input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction" id="doaction" class="button-secondary action" />
<?php
}

$select_order = "<select name=\"order_by\">\n";
$select_order .= '<option value="order_name"' . (($order_by == 'order_name') ? " selected='selected'" : '') . '>' .  __('Order by Name') . "</option>\n";
$select_order .= '<option value="order_quality"' . (($order_by == 'order_quality') ? " selected='selected'" : '') . '>' .  __('Order by Quality') . "</option>\n";
$select_order .= '<option value="order_id"' . (($order_by == 'order_id') ? " selected='selected'" : '') . '>' .  __('Order by ID') . "</option>\n";
$select_order .= '<option value="order_dkp"' . (($order_by == 'order_dkp') ? " selected='selected'" : '') . '>' .  __('Order by DKP') . "</option>\n";
$select_order .= "</select>\n";
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
	$item_columns = get_column_headers($current_screen);
	$hidden = get_hidden_columns($current_screen);
	
	$items = $wpdb->get_results("SELECT * FROM $wpdb->wowraid_items $sqlwhere $sqlorderby LIMIT ".($pagenum*$per_page-$per_page).",".$per_page,'ARRAY_A');
	if ($wpdb->num_rows>0) {
	foreach($items as $item) {
		?><tr id="item-<?php echo $item['ID']; ?>" valign="middle"><?php
		foreach($item_columns as $column_name=>$column_display_name) {
			$class = "class=\"column-$column_name\"";

			$style = '';
			if ( in_array($column_name, $hidden) )
				$style = ' style="display:none;"';

			$attributes = "$class$style";
			
			switch($column_name) {
				case 'cb':
					echo '<th scope="row" class="check-column"><input type="checkbox" name="itemcheck[]" value="'. esc_attr($item['ID']) .'" /></th>';
					break;
				case 'name':
					echo "<td $attributes><strong>".wowraid_item_link_tip($item['ID'])."</strong>";
					if (current_user_can($cfg['roleitems'])) {
						$actions = array();
						$actions['edit'] = "<a href=\"" . wp_nonce_url("admin.php?action=edit&amp;page=WoWRaidItems&amp;item_id=$item[ID]", 'WoWRaidItemsEdit') . "\">" . __('Edit') . "</a>";
						$actions['update'] = "<a href=\"" . wp_nonce_url("admin.php?action=update&amp;page=WoWRaidItems&amp;item_id=$item[ID]", 'update-item_' . $item['ID']) . "\">" . __('Update','wowraid') . "</a>";
						$actions['delete'] = "<a class=\"submitdelete\" href=\"" . wp_nonce_url("admin.php?action=delete&amp;page=WoWRaidItems&amp;item_id=$item[ID]", 'delete-item_' . $item['ID']) . "\" onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this Item '%s'\n  'Cancel' to stop, 'OK' to delete."), $item['Name'] )) . "') ) { return true;}return false;\">" . __('Delete') . "</a>";
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
				case 'winner':
					echo "<td $attributes> </td>";
					break;	
				case 'dkp':
					echo "<td $attributes>$item[DKP]</td>";
					break;
				case 'id':
					echo "<td $attributes>$item[ID]</td>";
					break;		
			}
		}
		echo "\n    </tr>\n";
	}
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