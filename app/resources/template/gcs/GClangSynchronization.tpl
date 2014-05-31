<gc:include file="gcs/include/function" />
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GCsystem {{lang:gc_lang_synchronization_title}}</title>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="{{def:IMG_PATH}}gcs/logo.png" />
	</head>
	<body>
		<style>
			<gc:call template="gcsystemHtmlDefault"/>

			#body{
				width: 100%;
				overflow: auto;
			}
		</style>
		<header>
			<div class="content">
				<h1 style="float:left">
					{{lang:gc_lang_synchronization_title}}
					<gc:if cond="$_GET['lang']">
						- {_GET['lang']}
					</gc:if>
				</h1>
				<gc:if cond="$_GET['lang']">
					<a class="button" onClick="save()" style="float:right; margin-top: 13px;">{{lang:gc_lang_synchronization_save}}</a>
				</gc:if>
			</div>
		</header>
		<div id="body">
			<div class="content">
				<p>
					<gc:if cond="!$_GET['lang']">
						{{lang:gc_lang_synchronization_choose}}
						<gc:foreach var="$data" as="$value">
							<a href="?lang={value[0]}">{value[0]}</a> 
						</gc:foreach>
					<gc:else />
						<gc:foreach var="$data" as="$key => $value">
							<gc:if cond="$value[0] == $_GET['lang']">
								{{php: $lang = $key; $count = count($data); }}
							</gc:if>
						</gc:foreach>
						<form id="form" action="?lang={_GET['lang']}" method="POST">
							<input type="hidden" name="save" />
							<gc:foreach var="$data[$lang][1]" as="$key => $value">
								<label>{key}</label><br /><br />
								<gc:for var="$i" cond="<" boucle="0-$count-1">
									<gc:if cond="$i == $lang">
										<textarea class="large" disabled="disabled" name="{data[$i][0]}_{key}">{<gc:function name="htmlspecialchars" var="$data[$i][1][$key]" />}</textarea>
									<gc:else />	
										<textarea class="large" name="{data[$i][0]}_{key}">{<gc:function name="htmlspecialchars" var="$data[$i][1][$key]" />}</textarea>
									</gc:if>
								</gc:for>
								<br />
							</gc:foreach>
						</form>
					</gc:if>
				</p>
		</div>
		<script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
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

			function save(){
				$('#form').submit();
			}
		</script>
	</body>
</html>