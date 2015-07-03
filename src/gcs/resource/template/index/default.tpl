<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GCsystem V{VERSION}</title>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="/{{path:IMAGE}}logo.png" />
		<gc:asset type="css" files="web/gcs/css/default.css,web/gcs/css/index.css" cache="3600"/>
	</head>
	<body id="body">
		<header id="header">
			<div class="content">
				<h1>GCsystem V{VERSION}</h1>
			</div>
		</header>
		<div id="main">
			<div class="content">
				<h1>{{lang:gcs.default.welcome}}</h1>
				<p>{{lang:gcs.default.content}}</p>
				<ul>
					<li><a href="http://www.gcs-framework.dzv.me/fr/">{{lang:gcs.default.website}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/documentation">{{lang:gcs.default.website-documentation}}</a></li>
					<li><a href="http://www.gcs-framework.dzv.me/fr/tutorial">{{lang:gcs.default.website-tutorial}}</a></li>
				</ul>

				<ul>
					<gc:foreach var="$data" as="$value">
						<li>
							<h4>Cours "{$value->name}"</h4>
							<p>{$value->content}</p>
							<ul>
								<gc:foreach var="$value->students" as="$students">
									<li>{$students->name}</li>
								</gc:foreach>
							</ul>
						</li>
					</gc:foreach>
				</ul>
			</div>
		</div>
		<footer id="footer">Gcsystem V{VERSION}</footer>
		<script type="text/javascript">
			updateHeight();

			window.onresize = function(event) {
				updateHeight();
			};

			function updateHeight(){
				console.log(document.body.offsetHeight);
				document.getElementById('main').style.height = window.innerHeight - document.getElementById('header').offsetHeight - document.getElementById('footer').offsetHeight + "px";
			}
		</script>
	</body>
</html>