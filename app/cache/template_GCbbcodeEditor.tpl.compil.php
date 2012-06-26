<style>
	.gc_bbcode{
		width : <?php echo htmlentities($width); ?>;
		background-color : <?php echo htmlentities($bgcolor); ?>;
		border-radius : 4px;
		padding: 1px;
		padding-right: 4px;
	}
	
	textarea#<?php echo htmlentities($id); ?>{
		border: none;
		width: <?php echo htmlentities($width); ?>;
		height: <?php echo htmlentities($height); ?>;
	}
</style>
<script>
	function insertTag(startTag, endTag, textareaId, tagType) {
		var field  = document.getElementById(textareaId); 
		var scroll = field.scrollTop;
		field.focus();
        
		if (window.ActiveXObject) { // C'est IE
			var textRange = document.selection.createRange();            
			var currentSelection = textRange.text;
             
			textRange.text = startTag + currentSelection + endTag;
			textRange.moveStart("character", -endTag.length - currentSelection.length);
			textRange.moveEnd("character", -endTag.length);
			textRange.select();     
		} 
		else { // Ce n'est pas IE
			var startSelection   = field.value.substring(0, field.selectionStart);
			var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
			var endSelection     = field.value.substring(field.selectionEnd);
              
			field.value = startSelection + startTag + currentSelection + endTag + endSelection;
			field.focus();
			field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
		} 

		field.scrollTop = scroll; // et on redéfinit le scroll.
	}
</script>
<div class="gc_bbcode">
	<div class="gc_bbcode_code">
		<div class="gc_bbcode_code_option">
		</div>
		<div class="gc_bbcode_code_zone">
			<textarea id=<?php echo htmlentities($id); ?> name=<?php echo htmlentities($name); ?> ><?php echo htmlentities($message); ?></textarea />
		</div>
	</div>
	<?php if($preview == true) { ?>
		<div class="gc_bbcode_preview_button">
			<?php echo "bekijken"; ?>
		</div>
		<div class="gc_bbcode_preview_zone">
		</div>
	<?php } ?>
</div>