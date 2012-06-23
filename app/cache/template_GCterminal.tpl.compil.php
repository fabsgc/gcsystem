<style>
	html, body{
		padding: 0;
		margin: 0;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
		height: 100%;
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
	}

	#gc_terminal_top{
		height: 25px;
		width: 100%;
		font-size: 1.15em;
		background-color: rgb(40,40,40);
		text-indent: 4px;
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
	
	#gc_terminal_top_content{
	}

	#gc_terminal #terminal{
		height: 100%;
		overflow: none;
		word-wrap: break-word;
		font-size: 0.90em;
		font-family: Consolas;
		padding: 1px;
		line-height: 0.95em;
		padding-bottom: <?php echo ($moins2); ?>px;
	}

	#gc_terminal input[type=text]{
		width: 100%;
		opacity: 1;
		height: 26px;
		font-size: 1.0em;
		background-color: rgb(30,30,30);
		font-family: Consolas;
		position: fixed;
		bottom: <?php echo ($moins); ?>px;
	}
</style>
<script>
	function terminal_empty(evenement){
		document.getElementById('terminal').scrollTop = document.getElementById('terminal').scrollHeight;
	}

	function terminal(evenement){
		var touche = window.event ? evenement.keyCode : evenement.which;
		
		if(touche==13){
			var field  = document.getElementById('terminal_input_write');
			var commande  = document.getElementById('terminal');
			if(document.getElementById('terminal_input_write').value=="app/console clear"){
				document.getElementById('terminal').innerHTML="";
				document.getElementById('terminal_input_write').value="";
			}
			
			if(field.value!=""){
				var sVar1 = encodeURIComponent(field.value);	
				
				$(function(){
					// lorsque l'on clique sur Prévisualiser

						var message = $('#terminal_input_write').val();
						$.post("index.php?rubrique=terminal&action=terminal",
						{
							message: message
						},
						function(data){
							var val = $('#terminal').html();
							$('#terminal').html(val+'<br />'+data);
						});

				});

				field.value = "app/console ";
				field.focus();								
			}
			else{
				field.value = "app/console ";
				field.focus();								
			}
			document.getElementById('terminal').scrollTop = document.getElementById('terminal').scrollHeight;	
		}
	}
</script>
<div id="gc_terminal">
	<div id="gc_terminal_top">
		<div id="gc_terminal_top_titre">Terminal - GCsystem</div>
	</div>
	<div id="gc_terminal_top_content">
		<div id="terminal_read">
			<div id="terminal" readonly="readonly">
				> hello
			</div>
		</div>
		<div id="terminal_write">
			<input type="text" value="app/console " id="terminal_input_write" onkeyPress="terminal(event);" onkeyUp="terminal_empty(event);" onkeyDown="terminal_empty(event);" />
		</div>
	</div>
</div>