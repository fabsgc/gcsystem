<gc:cache id="block-1" time="100">
	<!DOCTYPE html>
	<html lang="fr">
		<head>
			<title>GCsystem Version 2.3</title>
			<meta charset="utf-8" />
			<link rel="icon" type="image/png" href="{{def:IMG_PATH}}gcsystem/logo.png" />
		</head>
		<body>
			<style>
				*{
					box-sizing: border-box;
				}
				html, body{
					background-color: #E1E1E1;
					font-family: Calibri, sans-serif;
					padding: 0;
					margin: 0;
				}
				header{
					width: 100%;
					margin: auto;
					height: 70px;
					border-bottom: 10px solid #E74C3C;
					background-color: white;
				}
				header .content{
					width: 750px;
					margin: auto;
				}
				header h1{
					line-height: 60px;
					font-size: 30px;
					color: #E74C3C;
					padding-left: 65px;
					background: url('{{def:IMG_PATH}}GCsystem/logo60.png') top left no-repeat;
					margin: 0;
				}
				#body{
					width: 750px;
					min-height: 200px;
					margin: auto;
					background-color: white;
				}
				#body .content{
					padding: 5px 12px 5px 12px;
				}
				#body h1{
					color: #E74C3C;
					margin-top: 0;
				}
				#body a{
					color: #E74C3C;
				}
				footer{
					width: 750px;
					margin: auto;
					color: white;
					padding: 5px;
					background-color: #E74C3C;
				}
			</style>
			<header>
				<div class="content">
					<h1>GCsystem Version 2.3</h1>
				</div>
			</header>
			<div id="body">
				<div class="content">
					<h1>{{lang:bienvenue}}</h1>
					<p>{{lang:content}}</p>
					<ul>
						<li><a href="http://www.gcs-framework.dzv.me/fr/">{{lang:read_official}}</a></li>
						<li><a href="http://www.gcs-framework.dzv.me/fr/documentation">{{lang:read_documentation}}</a></li>
						<li><a href="http://www.gcs-framework.dzv.me/fr/tutorial">{{lang:read_tutorial}}</a></li>
						<li><a href="{{url:terminal}}">terminal</a>
					</ul>
				</div>
			</div>
			<footer>Gcsystem Version 2.3</footer>
			<script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
			<script type="text/javascript">
				$(document).ready(function(e){
					updateHeight();
					$(window).resize(function() {
						updateHeight();
					});

					function updateHeight(){
						$('#body').height( $(window).outerHeight()-$("header").outerHeight()-$("footer").outerHeight());
					}
				});
			</script>
		</body>
	</html>
</gc:cache>