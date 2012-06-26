<style>
	.gc_bbcode{
		width : {width};
		background-color : {bgcolor};
		border-radius : 4px;
		padding: 1px;
		padding-right: 4px;
	}
	
	textarea#{id}{
		border: none;
		width: {width};
		height: {height};
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
			<textarea id={id} name={name} >{message}</textarea />
		</div>
	</div>
	<if cond="$preview == true">
		<div class="gc_bbcode_preview_button">
			_(bbcodepreview)_
		</div>
		<div class="gc_bbcode_preview_zone">
		</div>
	</if>
</div>