<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GCsystem - Terminal</title>
		<link rel="icon" type="image/png" href="{{def:IMG_PATH}}/GCsystem/logo.png" />
		<script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
	</head>
	 <body>
		<style>
			html,body{
				height: 100%;
				background-color: #080808;
				font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif!important;
				color: white;
				padding:0;
				margin: 0;
			}
			#main-body{
				background-color: #080808!important;
			}
			#terminal-read{
				font-size: 14px;
				padding: 5px!important;
			}
			#terminal-input-begin,
			#terminal-input-write{
				display: inline-block;
				height: 20px;
				overflow: hidden;
			}
			#terminal-input-write{
				background-color: transparent;
				color: white;
				border: none;
				position: relative;
				top: -6px;
				outline: none;
				left: 3px;
			}
			#terminal{
				width: 100%;
				overflow: hidden;
				word-wrap: break-word;
			}
		</style>
		<script>
			function terminal(keyEvent){
				var key = window.event ? keyEvent.keyCode : keyEvent.which;
				
				if(key==13){
					var command = $('#terminal-input-write').val();
					adminTerminalLastCommand = command;

					if(command.match(/^clear$/g)){
						$('#terminal').html('');
					}
					else if(command != ''){
						$( document ).ready(function() {
							$.ajax({
								type: "POST",
								url: '{{url:gcsystem_terminal_parse}}',
								data: {
									command : command
								}
							}).done(function(data) {
								$('#terminal').append('<div class="terminal-data">'+data+'</div>');
								$(document).scrollTop($('html')[0].scrollHeight);
								$('#terminal-input-write').val('');
							});
						});
					}

					$('#terminal-input-write').val('')
				}
			}

			function terminalUp(keyEvent){
				$(document).scrollTop($(document).scrollHeight);
				var key = window.event ? keyEvent.keyCode : keyEvent.which;

				if(key == 38){
					$( document ).ready(function() {
						$('#terminal-input-write').val(adminTerminalLastCommand);
						document.getElementById('terminal-input-write').focus();
					});
				}
			}

			function terminalUpdateHeight(){
				$('#terminal-input-write').css('width', ($('#terminal-write').width() - $('#terminal-input-begin').width() - 5)+'px');
			}
		</script>
		<div id="main-body">
			<div id="terminal-read">
				<div id="terminal" readonly="readonly"></div>
				<div id="terminal-write">
					<div id="terminal-input-begin">terminal@gcsystem~$</div>
					<input id="terminal-input-write" spellcheck="false" onKeyUp="terminalUp(event)" onkeyPress="terminal(event);" />
				</div>
			</div>
		</div>
		<script>
			$( document ).ready(function() {
				document.getElementById('terminal-input-write').focus();
			});

			terminalUpdateHeight();

			$(window).resize(function() {
				terminalUpdateHeight();
			});
		</script>
	</body>
</html>