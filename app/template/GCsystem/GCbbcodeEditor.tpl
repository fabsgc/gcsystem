<style>
	.gc_bbcode{
		width : {width};
		background-color : {bgcolor};
		border-radius : 4px;
		padding: 1px;
		padding-right: 3px;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
	}
	textarea#{id}{
		border: none;
		width: {width};
		height: {height};
		margin-bottom: -2px;
		margin-top: 1px;
		padding: 1px;
	}
	.gc_bbcode_preview_zone{
		width: {width};
		padding: 0 1px 0 1px;
		background-color: rgb(245,245,245);
		border-bottom-left-radius : 4px;
		border-bottom-right-radius : 4px;
		max-height: 250px;
		overflow: auto;
		overflow-x:hidden;
		word-wrap: break-word;
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

	/* red */
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
	.button.goldenish, .option.goldenish {
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
		background-color: #{color[0]};
		background-image: -webkit-linear-gradient(top,#{color[0]}, #{color[1]});
		background-image: -moz-linear-gradient(top,#{color[0]}, #{color[1]});
		background-image: -ms-linear-gradient(top,#{color[0]}, #{color[1]});
		background-image: -o-linear-gradient(top,#{color[0]}, #{color[1]});
		background-image: linear-gradient(top,#{color[0]}, #{color[1]});

		border: 1px solid #{color[0]};
	}
	.button.personnalize:hover {
		border: 1px solid #{color[0]};
		
		background-color: #{color[0]};
		background-image: -webkit-linear-gradient(top,#{color[0]},#{color[1]});
		background-image: -moz-linear-gradient(top,#{color[0]},#{color[1]});
		background-image: -ms-linear-gradient(top,#{color[0]},#{color[1]});
		background-image: -o-linear-gradient(top,#{color[0]},#{color[1]});
		background-image: linear-gradient(top,#{color[0]},#{color[1]});
		
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
	function bbCodeGcInsertTag_{id}(startTag, endTag, textareaId, tagType) {
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
		
		bbCodeGcPreview_{id}('{id}');
	}
	
	function bbCodeGcPreview_{id}(id){
		field = bbCodeGcNl2brJs(document.getElementById('{id}').value);

		<gc:foreach var="$bbcode" as="$val">
			{{php: $val[0] = preg_replace('#"#isU', '&quot;', $val[0]);}}
			field = field.replace(/\[{val[0]}\]([\s\S]*?)\[\/{<gc:function name="html_entity_decode" string="$val[1]" />}\]/g, '<{<gc:function name="html_entity_decode" string="$val[2]" />}>{val[4]}</{<gc:function name="html_entity_decode" string="$val[3]" />}>');
		</gc:foreach>
		<gc:foreach var="$smiley" as="$val">
			{{php: $val = preg_replace('#"#isU', '&quot;', $val);}}
			field = field.replace(/{<gc:function name="preg_quote" string="$val[1]"/>} /g, '<img src="{imgpath}bbcode/{val[0]}" alt="{val[1]}" /> ');
			field = field.replace(/{<gc:function name="preg_quote" string="$val[1]"/>}&lt;br \/&gt;/g, '<img src="{imgpath}bbcode/{val[0]}" alt="{val[1]}" /><br />');
		</gc:foreach>
		<gc:foreach var="$bbCodeS" as="$val">
			{{php: $val[0] = preg_replace('#"#isU', '&quot;', $val[0]);}}
			field = field.replace(/\[{<gc:function name="html_entity_decode" string="$val[0]" />}\]([\s\S]*?)\[\/{<gc:function name="html_entity_decode" string="$val[0]" />}\]/g, '<{<gc:function name="html_entity_decode" string="$val[0]" />}>$1</{<gc:function name="html_entity_decode" string="$val[0]" />}>');
		</gc:foreach>

		field = field.replace(/\[code type=&quot;([\s\S]*?)&quot;\]([\s\S]*?)\[\/code\]/g,
			'<pre type="syntaxhighlighter" class="brush: $1; auto-links: false">$2<\/pre>');

		while(field.match(/\[video\]([^\[]+)\[\/video\]/gi)){
			field = bbCodeGc_preg_replace_callback("/\\[video\]([^\[]+)\\[\\/video\]/gi",function(matches){return bbCodeGcVideo(matches[1]);}, field, 1);
		}

		document.getElementById('zone_{id}').innerHTML = field;
		document.getElementById('zone_{id}').scrollTop = 1000;
		SyntaxHighlighter.config.bloggerMode = true;
		SyntaxHighlighter.highlight();
		document.getElementById('zone_{id}').innerHTML = bbCodeGcUrlCliquable(document.getElementById('zone_{id}').innerHTML);
		document.getElementById('zone_{id}').scrollTop = 1000;
	}
	
	function bbCodeGcPreviewAjax_{id}(){
		alert("{{lang:alertpreviewbbocde}}");
	}

	function bbCodeGcVideo(video){
		video = bbCodeGcHtmlspecialchars_decode(video);
		video = video.replace(/&feature=related/gi, '');

		if(video.match(/youtube/gi)){
			if(video.match(/www/gi)){	
				if(!video.match(/gl=/gi)){
					resultat = video.replace(/https?:\/\/www.youtube.com\/watch\?v=(.+)/gi,'$1');
				}
				else{
					resultat = video.replace(/https?:\/\/www.youtube.com\/watch\?gl=(.+)&v=(.+)/gi,'$2');
				}	
			}
			else{
				if(!video.match(/gl=/g)){
					resultat = video.replace(/https?:\/\/youtube.com\/watch\?v=(.+)/gi,'$1');
				}
				else{
					resultat = video.replace(/https?:\/\/youtube.com\/watch\?gl=(.+)&v=(.+)/gi,'$2');
				}
			}

			return '<object width="500" height="375" type="application/x-shockwave-flash" data="http://www.youtube.com/v/'+ resultat +'"><param name="movie" value="http://www.youtube.com/v/'+ resultat +'"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"></object>';
		}
		else if(video.match(/dailymotion/g)){
			if(video.match(/www/g)){
				resultat = video.replace(/https?:\/\/www.dailymotion.com\/video\/(.+)/gi,'http://www.dailymotion.com/swf/video/$1');
			}
			else{
				resultat = video.replace(/https?:\/\/dailymotion.com\/video\/(.+)/gi,'http://www.dailymotion.com/swf/video/$1');
			}

			return '<object width="500" height="375" type="application/x-shockwave-flash" data="'+ resultat +'"><param name="movie" value="'+ resultat +'"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"></object>';
		}
		else if(video.match(/vimeo/g)){
			if(video.match(/www/g)){
				resultat = video.replace(/https?:\/\/www.vimeo.com\/(.+)/gi,'http://www.vimeo.com/moogaloop.swf?clip_id=$1&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1');
			}
			else{
				resultat = video.replace(/https?:\/\/vimeo.com\/(.+)/gi,'http://vimeo.com/moogaloop.swf?clip_id=$1&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1');
			}	

			return '<object width="500" height="375" type="application/x-shockwave-flash" data="'+ resultat +'"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'+ resultat +'" /><param name="wmode" value="transparent" /></object>';
		}
		else{
			return '<video controls="" width="500" src="'+ video +'" controls="controls"></video>';
		}
	}
	
	function bbCodeGcNl2brJs(myString) {
		var regX = /\n/gi ;
		s = new String(myString);
		s = bbCodeGcHtmlspecialchars(s, 'ENT_QUOTES');
		//s = s.replace(regX2, "\n");
		s = s.replace(regX, " <br />");
		
		return s;
	}

	function bbCodeGcHtmlspecialchars_decode (string, quote_style) {
	  var optTemp = 0,
	    i = 0,
	    noquotes = false;
	  if (typeof quote_style === 'undefined') {
	    quote_style = 2;
	  }
	  string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
	  var OPTS = {
	    'ENT_NOQUOTES': 0,
	    'ENT_HTML_QUOTE_SINGLE': 1,
	    'ENT_HTML_QUOTE_DOUBLE': 2,
	    'ENT_COMPAT': 2,
	    'ENT_QUOTES': 3,
	    'ENT_IGNORE': 4
	  };
	  if (quote_style === 0) {
	    noquotes = true;
	  }
	  if (typeof quote_style !== 'number') {
	    quote_style = [].concat(quote_style);
	    for (i = 0; i < quote_style.length; i++) {
	      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
	      if (OPTS[quote_style[i]] === 0) {
	        noquotes = true;
	      } else if (OPTS[quote_style[i]]) {
	        optTemp = optTemp | OPTS[quote_style[i]];
	      }
	    }
	    quote_style = optTemp;
	  }
	  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
	    string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
	    // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
	  }
	  if (!noquotes) {
	    string = string.replace(/&quot;/g, '"');
	  }
	  // Put this in last place to avoid escape being double-decoded
	  string = string.replace(/&amp;/g, '&');

	  return string;
	}

	function bbCodeGcHtmlspecialchars (string, quote_style, charset, double_encode) {
	  var optTemp = 0,
	    i = 0,
	    noquotes = false;
	  if (typeof quote_style === 'undefined' || quote_style === null) {
	    quote_style = 2;
	  }
	  string = string.toString();
	  if (double_encode !== false) { // Put this first to avoid double-encoding
	    string = string.replace(/&/g, '&amp;');
	  }
	  string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');

	  var OPTS = {
	    'ENT_NOQUOTES': 0,
	    'ENT_HTML_QUOTE_SINGLE': 1,
	    'ENT_HTML_QUOTE_DOUBLE': 2,
	    'ENT_COMPAT': 2,
	    'ENT_QUOTES': 3,
	    'ENT_IGNORE': 4
	  };
	  if (quote_style === 0) {
	    noquotes = true;
	  }
	  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
	    quote_style = [].concat(quote_style);
	    for (i = 0; i < quote_style.length; i++) {
	      // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
	      if (OPTS[quote_style[i]] === 0) {
	        noquotes = true;
	      }
	      else if (OPTS[quote_style[i]]) {
	        optTemp = optTemp | OPTS[quote_style[i]];
	      }
	    }
	    quote_style = optTemp;
	  }
	  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
	    string = string.replace(/'/g, '&#039;');
	  }
	  if (!noquotes) {
	    string = string.replace(/"/g, '&quot;');
	  }

	  return string;
	}

	function bbCodeGcUrlCliquable(url) { 
		var reg=new RegExp("[^\"]+((http://)[a-zA-Z0-9:/.]+)+","gi");
  		return url.replace(reg,"<a href='$1'>$1</a>"); 
	}

	function bbCodeGc_preg_replace_callback(pattern, callback, subject, limit){
		// Perform a regular expression search and replace using a callback
		// 
		// discuss at: http://geekfg.net/
		// +   original by: Francois-Guillaume Ribreau (http://fgribreau)
		// *     example 1: preg_replace_callback("/(\\@[^\\s,\\.]*)/ig",function(matches){return matches[0].toLowerCase();},'#FollowFriday @FGRibreau @GeekFG',1);
		// *     returns 1: "#FollowFriday @fgribreau @GeekFG"
		// *     example 2: preg_replace_callback("/(\\@[^\\s,\\.]*)/ig",function(matches){return matches[0].toLowerCase();},'#FollowFriday @FGRibreau @GeekFG');
		// *     returns 2: "#FollowFriday @fgribreau @geekfg"

		limit = !limit?-1:limit;

		var _flag = pattern.substr(pattern.lastIndexOf(pattern[0])+1),
			_pattern = pattern.substr(1,pattern.lastIndexOf(pattern[0])-1),
			reg = new RegExp(_pattern,_flag),
			rs = null,
			res = [],
			x = 0,
			ret = subject;

		if(limit === -1){
			var tmp = [];

			do{
				tmp = reg.exec(subject);
				if(tmp !== null){
					res.push(tmp);
				}
			}while(tmp !== null && _flag.indexOf('g') !== -1)
		}
		else{
			res.push(reg.exec(subject));
		}

		for(x = res.length-1; x > -1; x--){//explore match
			ret = ret.replace(res[x][0],callback(res[x]));
		}
		return ret;
	}
</script>
<div class="gc_bbcode">
	<div class="gc_bbcode_option option {theme}">
		<gc:foreach var="$bbCodeEditor" as="$val">
			<img src="{imgpath}bbcode/{val[2]}" alt="{val[2]}" onclick="bbCodeGcInsertTag_{id}('[{<gc:function name="preg_quote" string="$val[0]"/>}]', '[/{<gc:function name="preg_quote" string="$val[1]"/>}]', '{id}'); " />
		</gc:foreach>
		<br />
		<gc:foreach var="$smiley" as="$val">
			<img src="{imgpath}bbcode/{val[0]}" alt="{val[1]}" onclick="bbCodeGcInsertTag_{id}('{<gc:function name="preg_quote" string="$val[1]"/>} ', '', '{id}'); " />
		</gc:foreach>
	</div>
	<textarea id="{id}" name="{name}"<gc:if cond="$preview == true && $instantane == true"> onKeyUp="bbCodeGcPreview_{id}('{id}');" </gc:if> >{message}</textarea>
	<gc:if cond="$preview == true && $previewAjax == true">
		<div class="gc_bbcode_preview_button button {theme}" onClick="bbCodeGcPreviewAjax_{id}();">
			{{lang:bbcodepreview}}
		</div>
	</gc:if>
	<gc:if cond="$preview == true && $previewAjax == false && $instantane == false">
		<div class="gc_bbcode_preview_button button {theme}" onClick="bbCodeGcPreview_{id}('{id}');">
			{{lang:bbcodepreview}}
		</div>
	</gc:if>
	<div class="gc_bbcode_preview_zone" id="zone_{id}">
	</div>
</div>
<script type="text/javascript">
	if(document.getElementById('{id}').value != ""){
		bbCodeGcPreview_{id}('{id}');
	}
</script>