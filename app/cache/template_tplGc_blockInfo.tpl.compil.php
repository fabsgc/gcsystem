<div id="block_info">
	<div id="block_info_header">
		<?php echo htmlentities($title); ?>
	</div>
	<div id="block_info_content">
		<?php echo htmlentities($content); ?>
	</div>
	<div id="block_info_footer">
		<a href="<?php echo htmlentities($redirect); ?>">
			<div id="window_info_bouton">
				<?php echo "Retour"; ?>
			</div>
		</a>
	</div>
	<?php if($time > 0) { ?>
		<meta http-equiv='Refresh' content='<?php echo htmlentities($time); ?>; URL=<?php echo htmlentities($redirect); ?>'>";
	<?php } ?>
</div>