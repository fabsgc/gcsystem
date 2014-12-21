<gc:include file="gcs/include/function" />
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GCsystem V{VERSION}</title>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="{{def:IMG_PATH}}gcs/logo.png" />
	</head>
	<body>
		<style>
			<gc:call block="gcsHtmlDefault()"/>
		</style>
		<header>
			<div class="content">
				<h1>GCsystem V{VERSION}</h1>
			</div>
		</header>
		<div id="body">
			<div class="content">
				<h1>{{lang:gcs.default.welcome}}</h1>
				<p>{{lang:gcs.default.content}}</p>
				<ul>
					<li><a href="http://www.gcs-framework.dzv.me/fr/">{{lang:gcs.default.website}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/documentation">{{lang:gcs.default.website-documentation}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/tutorial">{{lang:gcs.default.website-tutorial}}</a></li>
				</ul>
			</div>
		</div>
		<footer>Gcsystem V{VERSION}</footer>
		<script type="text/javascript" src="{HTML_WEB_PATH}gcs/js/jquery.min.js" ></script>
		<script type="text/javascript" defer>
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