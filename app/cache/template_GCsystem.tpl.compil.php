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
		<img src="asset/image/GCsystem/logo.png" alt="logo"/>
		 <?php echo 'http://gravatar.com/avatar/'.md5("salutsalut").'?s=500&default=http://'.$_SERVER['HTTP_HOST'].''.IMG_PATH.'GCsystem/empty_avatar.png'; ?> 
	</div>
	<div id="GCsystem_right">
		<h1><?php echo "Bienvenue !"; ?></h1>
		<p><?php echo "Bienvenue dans votre nouveau projet GCsystem, merci d'avoir choisi notre framework pour développer votre application."; ?></p>
		<ul>
			<li><a href=""><?php echo "lire la documentation"; ?></a></li>
			<li><a href=""><?php echo "lire le cours d'introduction"; ?></a></li>
			<li><a href="<?php echo $this->getUrl('terminal', array()); ?>">terminal</a>
		</ul>
	</div>
 <?php echo (FilterTitle("SALUT5")); ?>
	projet-<?php echo (FilterTitle("SALUT1")); ?>.html -------- projet <?php echo (strtolower("SALUT1")); ?> 
	<?php echo (strtolower("sdfFFFFFFFF")); ?>
	<?php $mavar="1"; ?>
	<?php $mavara=45; ?>
	<?php $truc=strtolower('Machin'); ?>
	<br /><?php echo $this->getUrl('index3', array($truc,$sdfjkh)); ?>
	<?php  
	echo 'salusdfsdjfsdkjfht'; ?>
</div>

<?php echo ($fs); ?> <?php echo ($qsd); ?>

<gc:include file="GCsystem/GCspam">
</gc:include>