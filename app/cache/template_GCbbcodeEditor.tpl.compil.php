<style>
	.gc_bbcode{
		width : <?php echo htmlentities($width); ?>;
		background-color : <?php echo htmlentities($bgcolor); ?>;
		border-radius : 4px;
		padding: 1px;
		padding-right: 3px;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
	}
	
	textarea#<?php echo htmlentities($id); ?>{
		border: none;
		width: <?php echo htmlentities($width); ?>;
		height: <?php echo htmlentities($height); ?>;
		margin-bottom: -2px;
		margin-top: 1px;
		padding: 1px;
	}
	
	.gc_bbcode_preview_zone{
		width: <?php echo htmlentities($width); ?>;
		padding: 0 1px 0 1px;
		background-color: rgb(245,245,245);
		border-bottom-left-radius : 4px;
		border-bottom-right-radius : 4px;
		max-height: 250px;
		overflow: auto;
		overflow-x:hidden;
		word-wrap: break-word;
	}
	
	div .button {

	}

	.button, .option {
		display: inline-block;
		width: 100%;
		background-color: #f5f5f5;
		background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
		background-image: -moz-linear-gradient(top,#f5f5f5,#f1f1f1);
		background-image: -ms-linear-gradient(top,#f5f5f5,#f1f1f1);
		background-image: -o-linear-gradient(top,#f5f5f5,#f1f1f1);
		background-image: linear-gradient(top,#f5f5f5,#f1f1f1);
		color: #444;
		border: 1px solid #dcdcdc;
		cursor: pointer;
		font-size: 16px;
		font-weight: bold;
		text-align: center;
		height: 27px;
		line-height: 27px;
		min-width: 54px;
		text-decoration: none;
	}
	
	.option{
		cursor: default;
		min-height: 50px;
	}

	.button:hover {
		background-color: #F8F8F8;
		background-image: -webkit-linear-gradient(top,#f8f8f8,#f1f1f1);
		background-image: -moz-linear-gradient(top,#f8f8f8,#f1f1f1);
		background-image: -ms-linear-gradient(top,#f8f8f8,#f1f1f1);
		background-image: -o-linear-gradient(top,#f8f8f8,#f1f1f1);
		background-image: linear-gradient(top,#f8f8f8,#f1f1f1);
		border: 1px solid #C6C6C6;
		color: #333;
		-webkit-box-shadow: 0px 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0px 1px 1px rgba(0,0,0,.1);
		box-shadow: 0px 1px 1px rgba(0,0,0,.1);
	}

	/* blue */

	.button.blue, .option.blue {
		background-color: #4D90FE;
		background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
		background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
		background-image: -ms-linear-gradient(top,#4d90fe,#4787ed);
		background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
		background-image: linear-gradient(top,#4d90fe,#4787ed);
		border: 1px solid #3079ED;
		color: white;
	}

	.button.blue:hover {
		border: 1px solid #2F5BB7;
		background-color: #357AE8;
		background-image: -webkit-linear-gradient(top,#4d90fe,#357ae8);
		background-image: -moz-linear-gradient(top,#4d90fe,#357ae8);
		background-image: -ms-linear-gradient(top,#4d90fe,#357ae8);
		background-image: -o-linear-gradient(top,#4d90fe,#357ae8);
		background-image: linear-gradient(top,#4d90fe,#357ae8);
		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}
	
	.button.red,.option.red {
		background-color: #D14836;
		background-image: -webkit-linear-gradient(top,#dd4b39,#d14836);
		background-image: -moz-linear-gradient(top,#dd4b39,#d14836);
		background-image: -ms-linear-gradient(top,#dd4b39,#d14836);
		background-image: -o-linear-gradient(top,#dd4b39,#d14836);
		background-image: linear-gradient(top,#dd4b39,#d14836);
		border: 1px solid transparent;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.red:hover {
		background-color: #C53727;
		background-image: -webkit-linear-gradient(top,#dd4b39,#c53727);
		background-image: -moz-linear-gradient(top,#dd4b39,#c53727);
		background-image: -ms-linear-gradient(top,#dd4b39,#c53727);
		background-image: -o-linear-gradient(top,#dd4b39,#c53727);
		background-image: linear-gradient(top,#dd4b39,#c53727);	
	}

	/* green */

	.button.green, .option.green {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#3d9400,#398a00);
		background-image: -moz-linear-gradient(top,#3d9400,#398a00);
		background-image: -ms-linear-gradient(top,#3d9400,#398a00);
		background-image: -o-linear-gradient(top,#3d9400,#398a00);
		background-image: linear-gradient(top,#3d9400,#398a00);
		border: 1px solid #29691D;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.green:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#3d9400,#368200);
		background-image: -moz-linear-gradient(top,#3d9400,#368200);
		background-image: -ms-linear-gradient(top,#3d9400,#368200);
		background-image: -o-linear-gradient(top,#3d9400,#368200);
		background-image: linear-gradient(top,#3d9400,#368200);
		border: 1px solid #2D6200;
		text-shadow: 0 1px rgba(0, 0, 0, 0.3);
	}

	/* brownish */

	.button.brownish, .option.brownich {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#674850,#50393f);
		background-image: -moz-linear-gradient(top,#674850,#50393f);
		background-image: -ms-linear-gradient(top,#674850,#50393f);
		background-image: -o-linear-gradient(top,#674850,#50393f);
		background-image: linear-gradient(top,#674850,#50393f);
		border: 1px solid #463237;
		color: white;
	}

	.button.brownish:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#674850,#463237);
		background-image: -moz-linear-gradient(top,#674850,#463237);
		background-image: -ms-linear-gradient(top,#674850,#463237);
		background-image: -o-linear-gradient(top,#674850,#463237);
		background-image: linear-gradient(top,#674850,#463237);
		border: 1px solid #412e33;
	}

	/* maroonish */

	.button.maroonish, .option.marronish {
		background-color: #a55474;
		background-image: -webkit-linear-gradient(top,#a55474,#8e4964);
		background-image: -moz-linear-gradient(top,#a55474,#8e4964);
		background-image: -ms-linear-gradient(top,#a55474,#8e4964);
		background-image: -o-linear-gradient(top,#a55474,#8e4964);
		background-image: linear-gradient(top,#a55474,#8e4964);
		border: 1px solid #83445d;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.maroonish:hover {
		background-color: #a55474;
		background-image: -webkit-linear-gradient(top,#a55474,#83445d);
		background-image: -moz-linear-gradient(top,#a55474,#83445d);
		background-image: -ms-linear-gradient(top,#a55474,#83445d);
		background-image: -o-linear-gradient(top,#a55474,#83445d);
		background-image: linear-gradient(top,#a55474,#83445d);
		border: 1px solid #793e55;
	}

	/* pinkish */

	.button.pinkish, .option.pinkish {
		background-color: #7c7461;
		background-image: -webkit-linear-gradient(top,#dfa7ca,#cd97b9);
		background-image: -moz-linear-gradient(top,#dfa7ca,#cd97b9);
		background-image: -ms-linear-gradient(top,#dfa7ca,#cd97b9);
		background-image: -o-linear-gradient(top,#dfa7ca,#cd97b9);
		background-image: linear-gradient(top,#dfa7ca,#cd97b9);
		border: 1px solid #c38fb0;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.pinkish:hover {
		background-color: #7c7461;
		background-image: -webkit-linear-gradient(top,#dfa7ca,#c38fb0);
		background-image: -moz-linear-gradient(top,#dfa7ca,#c38fb0);
		background-image: -ms-linear-gradient(top,#dfa7ca,#c38fb0);
		background-image: -o-linear-gradient(top,#dfa7ca,#c38fb0);
		background-image: linear-gradient(top,#dfa7ca,#c38fb0);
		border: 1px solid #ba88a7;
	}

	/* golden */

	.button.golden, .option.golden {
		background-color: #dee362;
		background-image: -webkit-linear-gradient(top,#c1b758,#aea54e);
		background-image: -moz-linear-gradient(top,#c1b758,#aea54e);
		background-image: -ms-linear-gradient(top,#c1b758,#aea54e);
		background-image: -o-linear-gradient(top,#c1b758,#aea54e);
		background-image: linear-gradient(top,#c1b758,#aea54e);
		color: white;
		border: 1px solid #a29948;
	}

	.button.golden:hover {
		background-color: #c0c455;
		background-image: -webkit-linear-gradient(top,#c1b758,#a29948);
		background-image: -moz-linear-gradient(top,#c1b758,#a29948);
		background-image: -ms-linear-gradient(top,#c1b758,#a29948);
		background-image: -o-linear-gradient(top,#c1b758,#a29948);
		background-image: linear-gradient(top,#c1b758,#a29948);
		border: 1px solid #989043;
	}

	/* goldenish */

	.button.goldenish, .option.goldenfish {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#777726,#62621e);
		background-image: -moz-linear-gradient(top,#777726,#62621e);
		background-image: -ms-linear-gradient(top,#777726,#62621e);
		background-image: -o-linear-gradient(top,#777726,#62621e);
		background-image: linear-gradient(top,#777726,#62621e);
		border: 1px solid #2b6700;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.goldenish:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#777726,#525219);
		background-image: -moz-linear-gradient(top,#777726,#525219);
		background-image: -ms-linear-gradient(top,#777726,#525219);
		background-image: -o-linear-gradient(top,#777726,#525219);
		background-image: linear-gradient(top,#777726,#525219);
		border: 1px solid #245600;
	}

	/* skinish */

	.button.skinish, .option.skinish {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#eab447,#cfa03f);
		background-image: -moz-linear-gradient(top,#eab447,#cfa03f);
		background-image: -ms-linear-gradient(top,#eab447,#cfa03f);
		background-image: -o-linear-gradient(top,#eab447,#cfa03f);
		background-image: linear-gradient(top,#eab447,#cfa03f);
		border: 1px solid #b68d37;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.skinish:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#eab447,#c0943a);
		background-image: -moz-linear-gradient(top,#eab447,#c0943a);
		background-image: -ms-linear-gradient(top,#eab447,#c0943a);
		background-image: -o-linear-gradient(top,#eab447,#c0943a);
		background-image: linear-gradient(top,#eab447,#c0943a);
		border: 1px solid #a17c31;
	}

	/* graysish */

	.button.grayish, .option.grayish {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#7c7461,#615b4c);
		background-image: -moz-linear-gradient(top,#7c7461,#615b4c);
		background-image: -ms-linear-gradient(top,#7c7461,#615b4c);
		background-image: -o-linear-gradient(top,#7c7461,#615b4c);
		background-image: linear-gradient(top,#7c7461,#615b4c);
		border: 1px solid #504b3e;
		color: white;
		text-shadow: 0 1px rgba(0, 0, 0, 0.1);
	}

	.button.grayish:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#7c7461,#504b3e);
		background-image: -moz-linear-gradient(top,#7c7461,#504b3e);
		background-image: -ms-linear-gradient(top,#7c7461,#504b3e);
		background-image: -o-linear-gradient(top,#7c7461,#504b3e);
		background-image: linear-gradient(top,#7c7461,#504b3e);
		border: 1px solid #474337;
	}

	/* yellowish */

	.button.yellowish, .option.yellowish {
		background-color: #3D9400;
		background-image: -webkit-linear-gradient(top,#dee362,#c0c455);
		background-image: -moz-linear-gradient(top,#dee362,#c0c455);
		background-image: -ms-linear-gradient(top,#dee362,#c0c455);
		background-image: -o-linear-gradient(top,#dee362,#c0c455);
		background-image: linear-gradient(top,#dee362,#c0c455);
		border: 1px solid #b3b74e;
	}

	.button.yellowish:hover {
		background-color: #368200;
		background-image: -webkit-linear-gradient(top,#dee362,#b3b74e);
		background-image: -moz-linear-gradient(top,#dee362,#b3b74e);
		background-image: -ms-linear-gradient(top,#dee362,#b3b74e);
		background-image: -o-linear-gradient(top,#dee362,#b3b74e);
		background-image: linear-gradient(top,#dee362,#b3b74e);
		border: 1px solid #abaf4b;
	}

	/* Pink */
	.button.pink, .option.pink {
		background-color: #ed47e6;
		background-image: -webkit-linear-gradient(top,#fe4dee,#ed47e6);
		background-image: -moz-linear-gradient(top,#fe4dee,#ed47e6);
		background-image: -ms-linear-gradient(top,#fe4dee,#ed47e6);
		background-image: -o-linear-gradient(top,#fe4dee,#ed47e6);
		background-image: linear-gradient(top,#fe4dee,#ed47e6);

		border: 1px solid #ed30e6;
		color: white;
	}

	.button.pink:hover {
		background-color: #e835de;
		background-image: -webkit-linear-gradient(top,#fe4df6,#e835de);
		background-image: -moz-linear-gradient(top,#fe4df6,#e835de);
		background-image: -ms-linear-gradient(top,#fe4df6,#e835de);
		background-image: -o-linear-gradient(top,#fe4df6,#e835de);
		background-image: linear-gradient(top,#fe4df6,#e835de);

		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}

	/* Violet */

	.button.violet, .option.violet {
		background-color: #aC47eD;
		background-image: -webkit-linear-gradient(top,#bA4dfe,#ac47ed);
		background-image: -moz-linear-gradient(top,#bA4dfe,#ac47ed);
		background-image: -ms-linear-gradient(top,#bA4dfe,#ac47ed);
		background-image: -o-linear-gradient(top,#bA4dfe,#ac47ed);
		background-image: linear-gradient(top,#bA4dfe,#ac47ed);

		border: 1px solid #a030ed;
		color: white;
	}

	.button.violet:hover {
		background-color: #a435e8;
		background-image: -webkit-linear-gradient(top,#c14Dfe,#a435e8);
		background-image: -moz-linear-gradient(top,#c14Dfe,#a435e8);
		background-image: -ms-linear-gradient(top,#c14Dfe,#a435e8);
		background-image: -o-linear-gradient(top,#c14Dfe,#a435e8);
		background-image: linear-gradient(top,#c14Dfe,#a435e8);

		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}

	/* Orange */

	.button.orange, .option.orange {
		background-color: #fe7d4d;
		background-image: -webkit-linear-gradient(top,#fe7d4d,#ed7247);
		background-image: -moz-linear-gradient(top,#fe7d4d,#ed7247);
		background-image: -ms-linear-gradient(top,#fe7d4d,#ed7247);
		background-image: -o-linear-gradient(top,#fe7d4d,#ed7247);
		background-image: linear-gradient(top,#fe7d4d,#ed7247);

		border: 1px solid #ed5f30;
		color: white;
	}

	.button.orange:hover {
		border: 1px solid #b7492f;

		background-color: #e85a35;
		background-image: -webkit-linear-gradient(top,#fe754d,#e85a35);
		background-image: -moz-linear-gradient(top,#fe754d,#e85a35);
		background-image: -ms-linear-gradient(top,#fe754d,#e85a35);
		background-image: -o-linear-gradient(top,#fe754d,#e85a35);
		background-image: linear-gradient(top,#fe754d,#e85a35);

		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}

	/* Sea Green */
	.button.seagreen, .option.seagreen {
		background-color: #4dfedf;
		background-image: -webkit-linear-gradient(top,#4dfedf,#47edd3);
		background-image: -moz-linear-gradient(top,#4dfedf,#47edd3);
		background-image: -ms-linear-gradient(top,#4dfedf,#47edd3);
		background-image: -o-linear-gradient(top,#4dfedf,#47edd3);
		background-image: linear-gradient(top,#4dfedf,#47edd3);

		border: 1px solid #30edd0;
	}

	.button.seagreen:hover {
		border: 1px solid #2fb7a2;

		background-color: #35e8d0;
		background-image: -webkit-linear-gradient(top,#4dfee5,#35e8d0);
		background-image: -moz-linear-gradient(top,#4dfee5,#35e8d0);
		background-image: -ms-linear-gradient(top,#4dfee5,#35e8d0);
		background-image: -o-linear-gradient(top,#4dfee5,#35e8d0);
		background-image: linear-gradient(top,#4dfee5,#35e8d0);

		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}
	
	/* Personnalize */
	.button.personnalize, .option.personnalize {
		background-color: #<?php echo ($color[0]); ?>;
		background-image: -webkit-linear-gradient(top,#<?php echo ($color[0]); ?>, #<?php echo ($color[1]); ?>);
		background-image: -moz-linear-gradient(top,#<?php echo ($color[0]); ?>, #<?php echo ($color[1]); ?>);
		background-image: -ms-linear-gradient(top,#<?php echo ($color[0]); ?>, #<?php echo ($color[1]); ?>);
		background-image: -o-linear-gradient(top,#<?php echo ($color[0]); ?>, #<?php echo ($color[1]); ?>);
		background-image: linear-gradient(top,#<?php echo ($color[0]); ?>, #<?php echo ($color[1]); ?>);

		border: 1px solid #<?php echo ($color[0]); ?>;
	}
	
	.button.personnalize:hover {
		border: 1px solid #<?php echo ($color[0]); ?>;
		
		background-color: #<?php echo ($color[0]); ?>;
		background-image: -webkit-linear-gradient(top,#<?php echo ($color[0]); ?>,#<?php echo ($color[1]); ?>);
		background-image: -moz-linear-gradient(top,#<?php echo ($color[0]); ?>,#<?php echo ($color[1]); ?>);
		background-image: -ms-linear-gradient(top,#<?php echo ($color[0]); ?>,#<?php echo ($color[1]); ?>);
		background-image: -o-linear-gradient(top,#<?php echo ($color[0]); ?>,#<?php echo ($color[1]); ?>);
		background-image: linear-gradient(top,#<?php echo ($color[0]); ?>,#<?php echo ($color[1]); ?>);
		
		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,.1);
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}

	/* defaults */

	.button.default:active {
		-webkit-box-shadow: inset 0px 1px 2px rgba(0,0,0,.1);
		-moz-box-shadow: inset 0px 1px 2px rgba(0,0,0,.1);
		box-shadow: inset 0px 1px 2px rgba(0,0,0,.1);
		color: black;
	}
	.button.blue:active, .button.red:active, .button.green:active,
	.button.pinkish:active, .button.maroonish:active,
	.button.golden:active, .button.brownish:active,
	.button.grayish:active, .button.skinish:active,
	.button.yellowish:active, .button.goldenish:active,
	.button.pink:active, .button.violet:active, .button.orange:active,
	.button.seagreen:active, .button.personnalize:active {
		-webkit-box-shadow: inset 0px 1px 2px rgba(0,0,0,.3);
		-moz-box-shadow: inset 0px 1px 2px rgba(0,0,0,.3);
		box-shadow: inset 0px 1px 2px rgba(0,0,0,.3);
	}
</style>
<script>
	function insertTag_<?php echo htmlentities($id); ?>(startTag, endTag, textareaId, tagType) {
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
		
		preview_<?php echo htmlentities($id); ?>('<?php echo htmlentities($id); ?>');
	}
	
	function preview_<?php echo htmlentities($id); ?>(id){
		field = nl2br_js(document.getElementById('<?php echo htmlentities($id); ?>').value);

		<?php if(!empty($bbcode)) { foreach($bbcode as $val) { ?>
			field = field.replace(/\[<?php echo (html_entity_decode("$val[0]")); ?>\]([\s\S]*?)\[\/<?php echo (html_entity_decode("$val[1]")); ?>\]/g, '<<?php echo (html_entity_decode("$val[2]")); ?>><?php echo ($val[4]); ?></<?php echo (html_entity_decode("$val[3]")); ?>>');
		<?php }} ?>
		<?php if(!empty($smiley)) { foreach($smiley as $val) { ?>
			field = field.replace(/<?php echo (preg_quote("$val[1]")); ?> /g, '<img src="<?php echo htmlentities($imgpath); ?>bbcode/<?php echo ($val[0]); ?>" alt="<?php echo ($val[1]); ?>" /> ');
			field = field.replace(/<?php echo (preg_quote("$val[1]")); ?>&lt;br \/&gt;/g, '<img src="<?php echo htmlentities($imgpath); ?>bbcode/<?php echo ($val[0]); ?>" alt="<?php echo ($val[1]); ?>" /><br />');
		<?php }} ?>
		<?php if(!empty($bbCodeS)) { foreach($bbCodeS as $val) { ?>
			field = field.replace(/\[<?php echo (html_entity_decode("$val[0]")); ?>\]([\s\S]*?)\[\/<?php echo (html_entity_decode("$val[0]")); ?>\]/g, '<<?php echo (html_entity_decode("$val[0]")); ?>>$1</<?php echo (html_entity_decode("$val[0]")); ?>>');
		<?php }} ?>

		field = field.replace('&gt;&lt;br \/&gt;', '>');

		document.getElementById('zone_<?php echo htmlentities($id); ?>').innerHTML = field;
		document.getElementById('zone_<?php echo htmlentities($id); ?>').innerHTML = field;
		document.getElementById('zone_<?php echo htmlentities($id); ?>').scrollTop = 1000;
	}
	
	function previewAjax_<?php echo htmlentities($id); ?>(id){
		alert('<?php echo "vous devez écrire la fonction pour l\'utiliser"; ?>');
	}
	
	function nl2br_js(myString) {
		var regX = /\n/gi ;

		s = new String(myString);
		s = s.replace(regX, "<br /> \n");
		return s;
	}
</script>
<div class="gc_bbcode">
	<div class="gc_bbcode_option option <?php echo htmlentities($theme); ?>">
		<?php if(!empty($bbCodeEditor)) { foreach($bbCodeEditor as $val) { ?>
			<img src="<?php echo htmlentities($imgpath); ?>bbcode/<?php echo ($val[2]); ?>" alt="<?php echo ($val[2]); ?>" onclick="insertTag_<?php echo htmlentities($id); ?>('[<?php echo (preg_quote("$val[0]")); ?>]', '[/<?php echo (preg_quote("$val[1]")); ?>]', '<?php echo htmlentities($id); ?>'); " />
		<?php }} ?>
		<br />
		<?php if(!empty($smiley)) { foreach($smiley as $val) { ?>
			<img src="<?php echo htmlentities($imgpath); ?>bbcode/<?php echo ($val[0]); ?>" alt="<?php echo ($val[1]); ?>" onclick="insertTag_<?php echo htmlentities($id); ?>('<?php echo (preg_quote("$val[1]")); ?> ', '', '<?php echo htmlentities($id); ?>'); " />
		<?php }} ?>
	</div>
	<textarea id="<?php echo htmlentities($id); ?>" name="<?php echo htmlentities($name); ?>"<?php if($preview == true && $instantane == true) { ?> onKeyUp="preview_<?php echo htmlentities($id); ?>('<?php echo htmlentities($id); ?>');" <?php } ?> ><?php echo htmlentities($message); ?></textarea>
	<?php if($preview == true) { ?>
		<div class="gc_bbcode_preview_button button <?php echo htmlentities($theme); ?>" onClick="previewAjax_<?php echo htmlentities($id); ?>('<?php echo htmlentities($id); ?>');">
			<?php echo "prévisualiser"; ?>
		</div>
	<?php } ?>
	<div class="gc_bbcode_preview_zone" id="zone_<?php echo htmlentities($id); ?>">
	</div>
</div>