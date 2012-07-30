<div id="block_info">
	<div id="block_info_header">
		<?php echo ($title); ?>
	</div>
	<div id="block_info_content">
		<?php echo ($content); ?>
	</div>
	<div id="block_info_footer">
		<a href="<?php echo ($redirect); ?>">
			<div id="window_info_bouton">
				<?php echo "Retour"; ?>
			</div>
		</a>
	</div>
	<?php if($time > 0) { ?>
		<meta http-equiv='Refresh' content='<?php echo ($time); ?>; URL=<?php echo ($redirect); ?>'>";
	<?php } ?>
</div>