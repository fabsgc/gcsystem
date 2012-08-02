<style>
	.pagination_first, .pagination_before, .pagination_list, .pagination_after, .pagination_last, .pagination_total{
		display : inline-block;
	}
</style>
<div class="pagination">
	<?php if($paginationFirstLast == true && $paginationFirstBefore == true) { ?>
		<div class="pagination_first"><a href="<?php echo ($urlfirst); ?>"><?php echo "first page"; ?></a></div>
	<?php } ?>
	<?php if($paginationBeforeAfter == true && $paginationFirstBefore == true) { ?>
		<div class="pagination_before"><a href="<?php echo ($urlbefore); ?>"><?php echo "previous page"; ?></a></div>
	<?php } ?>
	
	<div class="pagination_list">
		<?php if(!empty($pagination)) { foreach($pagination as $cle => $val) { ?>
			<?php if($val != false) { ?>
				<span class="link_active"><a href="<?php echo ($val); ?>"><?php echo ($cle); ?></a></span>
			<?php }else{ ?>
				<span class="link_disabled"><?php echo ($cle); ?></span>
			<?php } ?>
		<?php }} ?>
	</div>
	<?php if($paginationBeforeAfter == true && $paginationLastAfter == true) { ?>
		<div class="pagination_after"><a href="<?php echo ($urlafter); ?>"><?php echo "next page"; ?></a></div>
	<?php } ?>
	<?php if($paginationFirstLast == true && $paginationLastAfter == true) { ?>
		<div class="pagination_last"><a href="<?php echo ($urllast); ?>"><?php echo "last page"; ?></a></div>
	<?php } ?>
	<?php if($totalpage == true) { ?>
		<?php if($pageActuel != $nbrpage) { ?>
			<div class="pagination_total"><a href="<?php echo ($urllast); ?>">(<?php echo ($nbrpage); ?>)</a></div>
		<?php } ?>
	<?php } ?>
</div>