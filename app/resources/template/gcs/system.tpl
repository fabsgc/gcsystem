<gc:include file="gcs/include/function"/>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GCsystem Version {{def:VERSION}}</title>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="{{def:IMG_PATH}}gcs/logo.png" />
	</head>
	<body>
		<style>
			<gc:call template="gcsystemHtmlDefault"/>
		</style>
		<header>
			<div class="content">
				<h1>GCsystem Version {{def:VERSION}}</h1>
			</div>
		</header>
		<div id="body">
			<div class="content">
				<h1>{{lang:gc_bienvenue}}</h1>
				<p>{{lang:gc_content}}</p>
				<ul>
					<li><a href="http://www.gcs-framework.dzv.me/fr/">{{lang:gc_read_official}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/documentation">{{lang:gc_read_documentation}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/tutorial">{{lang:gc_read_tutorial}}</a></li>
					<li><a href="{{url:gcs.terminal}}">terminal</a>
					{{php:
						$_SESSION['test'] = 'test';
					}}
						{{url:profil:$_SESSION['test'],'test2'}}
				</ul>
			</div>
		</div>
		<footer>Gcsystem Version {{def:VERSION}}</footer>
		<script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
		<script type="text/javascript" defer>
			<gc:minify>
			$(document).ready(function(e){
				updateHeight();
				$(window).resize(function() {
					updateHeight();
				});

				function updateHeight(){
					$('#body').height( $(window).outerHeight()-$("header").outerHeight()-$("footer").outerHeight());
				}
			});
			</gc:minify>
		</script>
	</body>
</html>