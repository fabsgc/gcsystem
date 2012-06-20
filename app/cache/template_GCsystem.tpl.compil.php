<style>
	body{
		background-color: #EFEFEF;
		font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
		font-size: 0.95em;
	}
		
	#GCsystem{
		width: 810px;
		height: 610px;
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
		margin-top:-350px;
	}
	
	#GCsystem_left{
		float: left;
		width: 200px;
		height: 610px;
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
		<img src="asset/image/logo.png" alt="logo"/>
		
	</div>
	<div id="GCsystem_right">
		<h1><?php echo "welkom !"; ?></h1>
		<p><?php echo "Welkom op u nieuw project GC-systeem, dank u voor hebben gekozen onze framework."; ?></p>
		<ul>
			<li><a href=""><?php echo "lezen de documentatie"; ?></a></li>
			<li><a href=""><?php echo "lezen de inleidende les"; ?></a></li>
		</ul>
	</div>
</div>