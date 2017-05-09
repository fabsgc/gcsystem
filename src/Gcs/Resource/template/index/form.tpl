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

  input[type="file"]{
    margin-bottom: 8px;
    margin-left: -1px;
  }
</style>
<form action="{{url:get}}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="request-put"/>
	<input type="hidden" name="form-request"/>
	<input type="text" name="text" placeholder="texte" /><br />
	<input type="text" name="captcha" placeholder="captcha" /><br />
	<input type="file" name="form[]" multiple="multiple" /><br />
	<select name="list">
		<option value="">default</option>
		<option value="1">option 1</option>
		<option value="2">option 2</option>
	</select>
	<input type="checkbox" name="check[]" value="1"/>
	<input type="checkbox" name="check[]" value="2"/><br/>
	<input type="submit" id="submit" value="envoyer"/><br />
</form>
<p>
	{gc:if condition="isset($request)"}
		{gc:if condition="!$request->valid()"}
			<ul>
				{gc:foreach var="$request->errors()" as="$errors"}
					<li><strong>{$errors['field']}</strong> : {$errors['message']}</li>
				{/gc:foreach}
			</ul>
			<pre>{{php: var_dump($request->errors()) }}</pre>
		{gc:else/}
			<strong>Validé !</strong>
		{/gc:if}
	{/gc:if>
</p>