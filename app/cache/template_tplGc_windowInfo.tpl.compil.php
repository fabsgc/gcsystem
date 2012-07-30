<div id="window_info">
	<div id="window_info_header">
		<?php echo ($title); ?>
	</div>
	<div id="window_info_content">
		<?php echo ($content); ?>
	</div>
	<div id="window_info_footer">
		<a href="<?php echo ($redirect); ?>">
			<div id="window_info_bouton">
				<?php echo "Retour"; ?>
			</div>
		</a>
	</div>
	<?php if($time > 0) { ?>
		<meta http-equiv='Refresh' content='<?php echo ($time); ?>; URL="<?php echo ($redirect); ?>"'>";
	<?php } ?>
</div>