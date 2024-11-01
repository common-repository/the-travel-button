<?php
/**
 * Travel Buttons view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */
	$table = $this->buttonsTable;

	$page_number = ( isset( $_REQUEST['paged'] ) ) ? sanitize_text_field( $_REQUEST['paged'] ) : 1;

	$per_page = $this->preferences['per_page'];

	// filter comes from search POST
	$filterValue = ! empty( $_POST['s'] ) ? urldecode( trim( sanitize_text_field( $_POST['s'] ) ) ) : null;

	if (!$filterValue || empty( $filterValue )){
		// filter come from REQUEST
		$filterValue = ! empty( $_REQUEST['s'] ) ? urldecode( trim( sanitize_text_field( $_REQUEST['s'] ) ) ) : null;
	}

	$paramFilterValue = ! empty($filterValue) ? '&s=' . urlencode($filterValue) : '';

	$filterField = is_numeric($filterValue) ? 'id' : 'name';

	include_once ( 'load-db-records.php' );

	$pagination = array(
                  				'prev'     => 'admin.php?page=' . $this->plugin_slug . '&tab=buttons&paged=' . max($page_number-1,1) . $paramFilterValue,
                  			    'next'    => 'admin.php?page=' . $this->plugin_slug . '&tab=buttons&paged=' . min($page_number+1, $total_pages) . $paramFilterValue,
                  			);


	$data = array();

	if ( $results ) {
		foreach ( $results as $key => $value ) {
			$name = !empty( $value->name ) ? $value->name : __('Untitled', 'the-travel-button');
			$atk = null;
			$atkConfiguredOnButton = false;
			if($this->preferences['use_default_atk'] == 'on' || empty($value->atk)){
				$atk = $this->preferences['atk'];
			} else {
				$atk = $value->atk;
				$atkConfiguredOnButton = true;
			}
			$data[] = array(
				'id'        => $value->id,
				'name'      => $name,
				'desc'      => $value->desc,
				'code'      => "[" . $this->shortcode . " id='" . $value->id . "'/]",
				'atk'       => $atk,
				'style'     => $value->style,
				'config'    => $value->config,
				'atkConfiguredOnButton' => $atkConfiguredOnButton,
				'edit'      => 'admin.php?page=' . $this->plugin_slug . '&tab=buttons&modal=new_button&act=edit&id=' . $value->id . '&paged=' . $page_number . $paramFilterValue,
				'clone'     => 'admin.php?page=' . $this->plugin_slug . '&tab=buttons&modal=new_button&act=clone&id=' . $value->id,
			    'delete'    => 'admin.php?page=' . $this->plugin_slug . '&tab=buttons&info=delete&id=' . $value->id,
			);
		}
	}
?>
<div class="wrap hwt-scoped">
	<form method="post" action="admin.php?page=<?php echo $this->plugin_slug;?>&tab=buttons">
		<input type="hidden" id="paged" name="paged" value="<?php echo $page_number; ?>" />
		<span class="hwt-label hwt-label-success hwt-align-left"><?php _e( 'buttons', 'the-travel-button' ); ?>: <?php echo $total_items; ?> </span>
		<span class="hwt-label hwt-label-success hwt-align-left"><?php printf( esc_html__( 'Page %1$d of %2$d', 'the-travel-button' ), $page_number, $total_pages ); ?></span>
		<div class="hwt-search hwt-search-default hwt-align-right hwt-width-1-2">
			<span hwt-search-icon></span>
			<input class="hwt-search-input" id="s" name="s" type="search" placeholder="<?php _e( 'search by id or name', 'the-travel-button' ); ?>" value="<?php echo $filterValue; ?>" oninput="jQuery('#paged').val('1');">
		</div>
		<div class="wth-tb-scrollable-table-wrapper" hwt-overflow-auto>
		<table class="hwt-table hwt-table-justify hwt-table-divider hwt-table-middle hwt-table-striped">
			<thead class="wth-tb-scrollable-table-sticky-header">
				<tr>
					<th><?php _e( 'Id', 'the-travel-button' ); ?></th>
					<th><?php _e( 'Name', 'the-travel-button' ); ?></th>
					<th><?php _e( 'shortcode', 'the-travel-button' ); ?></th>
					<th style="text-align:center"><?php _e( 'Tracking code', 'the-travel-button' ); ?></th>
					<th style="text-align:center"><?php _e( 'Preview', 'the-travel-button' ); ?></th>
					<?php if ($this->userCan('create_buttons') || $this->userCan('delete_buttons')) :?>
					<th style="text-align:center"><?php _e( 'Actions', 'the-travel-button' ); ?></th>
					<?php endif;?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($data as $row) :?>
				<tr>
					<td><?php echo $row['id']; ?></td>
					<td><a href="<?php echo $row['edit']; ?>"><?php echo $row['name']; ?></a></td>
					<td><code><?php echo $row['code']; ?></code></td>
					<?php if (empty($row['atk'])) :?>
						<td style="text-align:center"><span class="hwt-label hwt-label-danger"><?php _e( 'Not configured', 'the-travel-button' ); ?></span></td>
					<?php endif;?>
					<?php if (!empty($row['atk'])) :?>
						<?php if (!$row['atkConfiguredOnButton']) :?>
						<td style="text-align:center"><span class="hwt-label hwt-label-success"><?php _e( 'Default', 'the-travel-button' ); ?></span></td>
						<?php endif;?>
						<?php if ($row['atkConfiguredOnButton']) :?>
						<td style="text-align:center"><span class="hwt-label hwt-label-warning"><?php _e( 'On button', 'the-travel-button' ); ?></span></td>
						<?php endif;?>
					<?php endif;?>
					<td><?php echo $this->renderer->renderButtonPreview($row['config'], $row['style']); ?></td>
					<?php if ($this->userCan('create_buttons') || $this->userCan('delete_buttons')) :?>
					<td>
						<ul class="hwt-iconnav hwt-flex-center">
							<?php if ($this->userCan('create_buttons')) :?>
							<li><a href="<?php echo $row['clone']; ?>" hwt-icon="icon: plus" hwt-tooltip="<?php _e( 'Clone', 'the-travel-button' ); ?>"></a></li>
							<li><a href="<?php echo $row['edit']; ?>" hwt-icon="icon: file-edit" hwt-tooltip="<?php _e( 'Edit', 'the-travel-button' ); ?>"></a></li>
							<?php endif;?>
							<?php if ($this->userCan('delete_buttons')) :?>
							<li><a id="modal-confirm-delete" href="<?php echo $row['delete']; ?>" hwt-icon="icon: trash" hwt-tooltip="<?php _e( 'Delete', 'the-travel-button' ); ?>"></a></li>
							<?php endif;?>
						</ul>
					</td>
					<?php endif;?>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		</div>
		<ul class="hwt-pagination" hwt-margin>
			<?php if ($page_number > 1) :?>
            	<li><a href="<?php echo $pagination['prev']; ?>"><span class="hwt-margin-small-right" hwt-pagination-previous></span> <?php _e( 'Previous', 'the-travel-button' ); ?></a></li>
            <?php endif;?>
			<?php if ($page_number <= 1) :?>
				<li class="hwt-disabled"><span></span></li>
			<?php endif;?>
            <?php if ($page_number < $total_pages) :?>
            	<li class="hwt-margin-auto-left"><a href="<?php echo $pagination['next']; ?>"><?php _e( 'Next', 'the-travel-button' ); ?> <span class="hwt-margin-small-left" hwt-pagination-next></span></a></li>
             <?php endif;?>
			 <?php if ($page_number >= $total_pages) :?>
				<li class="hwt-margin-auto-left hwt-disabled"><span></span></li>
			  <?php endif;?>
        </ul>
	</form>
</div>
<?php if ($this->userCan('delete_buttons')) :?>
<script>
hwtUIkit.util.on('#modal-confirm-delete', 'click', function (e) {
   e.preventDefault();
   e.target.blur();
   var href = this.href;
   var config = { labels : {
   					ok: "<?php _e( 'Delete', 'the-travel-button' ); ?>",
                    cancel: "<?php _e( 'Cancel', 'the-travel-button' ); ?>"
                    }
                 };
   var message = '<h2 class="hwt-modal-title"><span class="hwt-text-danger" hwt-icon="icon: warning; ratio: 2"></span> <?php _e( "Warning!", "the-travel-button" ); ?></h2><p><?php _e( "This operation cannot be undone. Are you sure you want to delete this item?", "the-travel-button" ); ?></p>';
   hwtUIkit.modal.confirm(message, config ).then(function () {
   	   // confirm deletion
	   window.location.href = href;
   }, function () {
	 // reject deletion
   });
});
</script>
<?php endif;?>
