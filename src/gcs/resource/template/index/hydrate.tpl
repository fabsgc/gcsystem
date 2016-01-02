<gc:extends file="main"/>
<style>
  label{
    display:inline-block;
    width: 200px;
  }

  select  {
    width: 500px;
    padding: 8px;
    outline:none;
    margin-bottom: 8px;
  }
</style>
<form action="{{url:hydrate}}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="request-post"/>
	<!--<label>Identifiant </label>
	<input type="text" name="post.id" value=""/><br />-->
	<label>Post content </label>
	<input type="text" name="post.content" value="contenu"/><br />
	<br />
	<label>Article nom</label>
	<input type="text" name="post.article.title" value="titre"/><br />
	<label>Article content</label>
    <input type="text" name="post.article.content" value="contenu"/><br />
	<label>Fichier </label><input type="file" name="post.file" /><br /><br />
	<input type="submit" id="submit" name="form-post" value="envoyer"/><br />
</form>
<p>
<gc:if condition="isset($post)">
    <?php var_dump($post->errors()); ?>
</gc:if>
<gc:if condition="isset($post)">
	<!--<pre><?php print_r($post->fields()['file']); ?></pre>-->
</gc:if>
</p>