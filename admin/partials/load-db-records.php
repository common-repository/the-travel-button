<?php
/**
 * Load data from database for views
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */

 	if (!$table || empty($table)){
 		exit;
 	}

	$info = ( isset( $_REQUEST['info'] ) ) ? sanitize_text_field( $_REQUEST['info'] ) : '';

	if ( $info == 'create' ) {
		echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: check\'></span> ' . __( 'created', 'the-travel-button' ) . '", {status:\'success\', pos: \'top-center\'});</script>';
	} elseif ( $info == 'update' ) {
		echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: check\'></span> ' . __( 'updated', 'the-travel-button' ) . '", {status:\'success\', pos: \'top-center\'});</script>';
	} elseif ( $info == 'delete' ) {
		if ($this->userCan('delete_' . $current_tab) && isset( $_REQUEST['id'] )){
			$id = sanitize_text_field( $_REQUEST["id"] );
			$this->db->deleteById( $table, $id );
			if ($this->db->count( $table, 'id', $id) != 0){
				echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: warning\'></span> ' . __( 'this item cannot be deleted', 'the-travel-button' ) . '", {status:\'danger\', pos: \'top-center\'});</script>';
			} else {
				echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: check\'></span> ' . __( 'deleted', 'the-travel-button' ) . '", {status:\'success\', pos: \'top-center\'});</script>';
			}
		}
	}

	$results = $this->db->data($table, $filterField, $filterValue, $page_number, $per_page);
	$total_items = $this->db->count($table, $filterField, $filterValue);
	$total_pages = ceil( $total_items / $per_page );
	$total = $this->db->count($table, null, null);
?>
