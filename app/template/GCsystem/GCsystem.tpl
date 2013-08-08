<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>GCsystem - Terminal</title>
    <meta charset="utf-8" />
    <meta name="robots" content="index,follow" />
    <!--[if lt IE 9]>
    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="icon" type="image/png" href="{{def:IMG_PATH}}/GCsystem/logo.png" />
    <script type="text/javascript" src="{{def:JS_PATH}}/jquery/jquery.min.js" ></script>
    </script>
  </head>
  <body>
<style>
	body{
		background-color: #EFEFEF;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
		font-size: 0.95em;
	}
		
	#GCsystem{
		width: 810px;
		height: 400px;
		background-color: white;
		border: 1px solid #DFDFDF;
		-moz-border-radius: 16px;
		-webkit-border-radius: 16px;
		border-radius: 16px;
		margin-bottom: 20px;
		word-wrap: break-word;
		position:absolute; 
		top:50%; 
		left:50%; 
		margin-left:-400px; 
		margin-top:-200px;
	}
	
	#GCsystem_left{
		float: left;
		width: 200px;
		height: 400px;
		background-color: rgb(230,230,230);
		border-top-left-radius: 16px;
		border-bottom-left-radius: 16px;
	}
	
	#GCsystem_right{
		padding: 5px;
		padding-left: 210px;
	}
	
	#GCsystem_right h1{
		font-size: 2em;
		color: #ff7800;
		text-align: center;
		margin: 0;
	}
	
	#GCsystem_right p{
		text-indent: 10px;
		text-align: justify;
	}
</style>
<div id="GCsystem">
	<div id="GCsystem_left">
		<img src="asset/image/GCsystem/logo.png" alt="logo"/>
	</div>
	<div id="GCsystem_right">
		<h1>{{lang:bienvenue}}</h1>
		<p>{{lang:content}}</p>
		<ul>
			<li><a href="">{{lang:liredoc}}</a></li>
			<li><a href="">{{lang:lirecours}}</a></li>
			<li><a href="{{url:terminal:}}">terminal</a>
		</ul>
	</div>
</div>
</body>
</html>