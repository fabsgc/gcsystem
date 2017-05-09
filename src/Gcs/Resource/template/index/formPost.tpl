{gc:extends file="main"/}
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
<form action="{{url:get}}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="request-post"/>
	<label>Identifiant </label>
	<input type="text" name="post.id" value=""/><br />
	<label>Contenu </label>
	<input type="text" name="post.content" value="contenu"/><br />
	<label>Article </label>
	<select name="post.article">
		{gc:foreach var="$articles" as="$article"}
			<option value="{$article->id}">{$article->title}</option>
		{/gc:foreach}
	</select><br />
	<label>Fichier </label><input type="file" name="post.file" /><br /><br />
	<input type="submit" id="submit" value="envoyer"/><br />
</form>