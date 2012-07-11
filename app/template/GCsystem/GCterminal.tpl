<style>
	html, body{
		padding: 0;
		margin: 0;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
		background-color: black;
	}
	
	#gc_terminal{
		-webkit-box-shadow: rgba(0, 0, 0, 0.5) 0 0 8px;
		box-shadow: rgba(0, 0, 0, 0.5) 0 0 6px;
		width: 100%;
		height: 100%;
		z-index: 1000;
		font-size: 0.95em;
		color: white;
		background-color: black;
		margin-bottom : {moins2}px;
	}

	#gc_terminal_top{
		height: 25px;
		width: 100%;
		font-size: 1.15em;
		position: fixed;
		top: 0;
		border-top:1px #b0b0b0 solid;
		background-color: #444444;
		background-image:-webkit-linear-gradient(top, #E5E5E5, #AEAEAE);
		background-image:-moz-linear-gradient(top, #E5E5E5, #AEAEAE);
		background-image:-ms-linear-gradient(top, #E5E5E5, #AEAEAE);
		background-image:-o-linear-gradient(top, #E5E5E5, #AEAEAE);
		background-image:linear-gradient(to bottom, #E5E5E5, #AEAEAE);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorStr='#E5E5E5', EndColorStr='#AEAEAE');
		font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
		margin:0;
		display:block;
		text-align:center;
		color:#464646;
		text-shadow:0 1px 0 rgba(255,255,255,.75);
		text-indent: 4px;
		box-shadow: rgba(0, 0, 0, 0.25) 0 0 8px;
	}

	#gc_terminal_top_titre{
		
	}

	#gc_terminal #terminal, #gc_terminal input[type=text]{
		border: none;
		background-color: black;
		color: white;
		border: none;
	}
	
	*:focus{
	  outline:none;
	}
	
	#gc_terminal #terminal{
		height: 100%;
		overflow: none;
		word-wrap: break-word;
		font-size: 0.98em;
		font-family: Consolas;
		padding: 1px;
		line-height: 0.95em;
	}
	
	#gc_terminal #terminal_input_write{
		opacity: 1;
		font-size: 1.0em;
		font-family: Consolas;
		float: left;
		position: relative;
		background-color: rgba(0, 0, 0, 0);	
		top: -6px;
	}
	
	#gc_terminal #terminal_input_write_left{
		width: 140px;
		opacity: 1;
		font-size: 1.0em;
		font-family: Consolas;
		float: left;
		text-shadow : white 0px 0px 5px;
		position: relative;
		background-color: rgba(0, 0, 0, 0);	
		top: -6px;
	}
	
	
	#terminal_read, gc_terminal_top_content{
		margin-top: 28px;
	}
	
	#terminal_read{
	}
</style>
<script>	
	function pageScroll() {
    	window.scrollBy(0,1000); // horizontal and vertical scroll increments
    	scrolldelay = setTimeout('pageScroll()',0); // scrolls every 100 milliseconds
		stopScroll();
	}
	
	function stopScroll() {
    	clearTimeout(scrolldelay);
	}
	
	function terminal(evenement){
		//if (window.focus){ window.location.href="#terminal_input_write"; }
		var touche = window.event ? evenement.keyCode : evenement.which;
		pageScroll();
		if(touche==13){
			window.location.href="#terminal_input_write";
			var field  = document.getElementById('terminal_input_write');
			var commande  = document.getElementById('terminal');
			if(document.getElementById('terminal_input_write').value=="clear"){
				document.getElementById('terminal').innerHTML="";
				document.getElementById('terminal_input_write').value="";
			}
			
			if(field.value!=""){
				var sVar1 = encodeURIComponent(field.value);	
				
				$(function(){
					var message = $('#terminal_input_write').val();
					$.post("terminal-terminal.html",
					{
						message: message
					},
					function(data){
						var val = $('#terminal').html();
						if(val==""){ var val2="" } else { var val2="<br />"; }
						$('#terminal').html(val+val2+data);
					});
				});

				field.value = "";
				field.focus();
				window.setTimeout(function (){ document.getElementById(field).focus(); }, 0);				
			}
			else{
				field.value = "";
				field.focus();								
			}
			if (window.focus){ window.location.href="#terminal_input_write"; }
		}
	}
	
	function terminal_empty(){
		//if (window.focus){ window.location.href="#terminal_input_write"; }
	}

	
</script>
<div id="gc_terminal">
	<div id="gc_terminal_top">
		<div id="gc_terminal_top_titre">Terminal - GCsystem</div>
	</div>
	<div id="gc_terminal_top_content">
		<div id="terminal_read">
			<div id="terminal" readonly="readonly"></div>
			<div id="terminal_write">
				<input type="text" value="> app/console &#8594;" id="terminal_input_write_left"/>
				<input type="text" value="" id="terminal_input_write" onkeyPress="terminal(event);" onkeyUp="terminal_empty();"/>
			</div>
		</div>
	</div>
</div>
<script>
	if (document.body){
		document.getElementById('terminal_input_write').style.width = (document.body.clientWidth-180) + "px";
		document.getElementById('terminal_input_write').focus();
	}
</script>