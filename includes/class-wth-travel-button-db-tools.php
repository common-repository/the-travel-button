<?php
/**
 * Database functions
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button_DB {

	function __construct() {
	}

	/**
	* Get the table row
	*
	* @return get_row
	*/
	function loadById( $tblname, $id) {
		global $wpdb;
		if ( is_numeric( $id )){
			return $wpdb->get_row("SELECT * FROM " . $tblname . " WHERE id = " . absint($id));
		} else{
			return $wpdb->get_row("SELECT * FROM " . $tblname . " WHERE id = '" . esc_sql($id) . "'");
		}
	}

	/**
	* Delete by id
	*/
	public function deleteById($tblname, $id)
	{
		global $wpdb;

		if ( is_numeric( $id )){
			$wpdb->query( "DELETE FROM " . $tblname . " WHERE id = " .  absint($id));
		} else{
			$wpdb->query( "DELETE FROM " . $tblname . " WHERE id = '" . esc_sql($id) . "'");
		}

	}

	/**
	* Delete table data
	*/
	public function delete($tblname)
	{
		global $wpdb;

		$wpdb->query( "DELETE FROM " . $tblname );

	}

	/**
	* Volatile next assignable id
	*/
	function nextAssignatbleId( $tblname ) {
		global $wpdb;
		$last  = $wpdb->get_col( "SELECT id FROM $tblname ORDER BY id DESC LIMIT 1" );
		return !empty($last) ?  max( $last ) + 1 : 1;
	}

	/**
	* Upsert table record
	*/
	function upsertRecord( $tblname, $record, $idField) {
		global $wpdb;
		$id = $record[$idField];
		// $fields = $wpdb->get_col( "DESC " . $tblname, 0); NOT SUPPPORTED BY SQLITE WP PLAYGROUND
		$fields = array_keys($record);
		$data = array();
		foreach ( $fields as $key ) {
			if ( is_array( $record[$key] ) == true ){
				$data[$key] = serialize( $record[$key] );
			}
			else {
				if ($idField != $key || ($idField == $key && !empty($id) && isset($id))){
					$data[$key] = $record[$key];
				}
			}
		}
		if (!$id || empty($id) || !isset($id)){
			$wpdb->insert( $tblname, $data );
		} else {
			$wpdb->update( $tblname, $data, array( $idField => $id ), $format = null, $WHERE_format = null );
		}
		// is new
		return !$id || empty($id) || !isset($id);
	}

	/**
	* Get the table data
	*
	* @return get_results
	*/
	public function data($tblname, $filterField, $filterValue, $page_number, $per_page)
	{
		global $wpdb;

		$limits =  !$per_page || empty( $per_page ) ? "": " LIMIT " . absint($per_page) ;

		$limits =  !$per_page || empty( $per_page ) || !$page_number || empty( $page_number ) ? $limits : $limits . " OFFSET " . ($per_page * ( absint($page_number) - 1 )) ;

		if( !$filterField || empty( $filterField ) || !$filterValue || empty( $filterValue )) {
			return $wpdb->get_results( "SELECT * FROM " . $tblname . " ORDER BY id DESC " . $limits );
		}
		elseif( is_numeric( $filterValue ) ) {
				return $wpdb->get_results( "SELECT * FROM " . $tblname . " WHERE " . $filterField . "=" . esc_sql($filterValue) . " ORDER BY id DESC " . $limits);
			} else {
				return $wpdb->get_results( "SELECT * FROM " . $tblname . " WHERE " . $filterField . " LIKE '%" . esc_sql($filterValue) . "%' ORDER BY id DESC " . $limits );
		}
	}

	/**
	* Count table elements
	*/
	public function count($tblname, $filterField, $filterValue ) {
		global $wpdb;

		if( !$filterField || empty( $filterField ) || !$filterValue || empty( $filterValue )) {
			return absint(max( $wpdb->get_col( "SELECT COUNT(*) FROM " . $tblname ) ));
		}
		if( is_numeric( $filterValue ) ) {
			return absint(max( $wpdb->get_col( "SELECT COUNT(*) FROM " . $tblname . " WHERE " . $filterField . "=" . esc_sql($filterValue) ) ));
		} else {
			return absint(max( $wpdb->get_col( "SELECT COUNT(*) FROM " . $tblname . " WHERE " . $filterField . " LIKE '%" . esc_sql($filterValue) . "%'" ) ));
		}
	}

}
