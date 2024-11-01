<?php
/**
 * Load an item from database for creation/edit views
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

	$action = ( isset( $_REQUEST["act"] ) ) ? sanitize_text_field( $_REQUEST["act"] ) : '';

	$reqid = ( isset( $_REQUEST["id"] ) ) ? sanitize_text_field( $_REQUEST["id"] ) : '';

	if( !empty( $reqid )){
		$result = $this->db->loadById($table, $reqid);
	}

	if ($action == "edit") {
		if ($result){
			$id = $result->id;
			$form_id = $id;
		}
	} else if ($action == "clone") {
		if ($result){
			$id = "";
			$form_id = $this->db->nextAssignatbleId($table);
		}
	} else {
    	$id = "";
    	$action = "new";
		$form_id = $this->db->nextAssignatbleId($table);
	}

?>
