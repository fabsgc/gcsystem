<gc:extends file="main"/>
<form action="{{url:get}}" method="post">
	<input type="hidden" name="request-put"/>
	<input type="text" id="page" name="text" placeholder="entrez une valeur" />
	<input type="submit" value="envoyer"/>
</form>
<p>
	<gc:if condition="isset($request)">
		<gc:if condition="$request->valid()">
			<?php var_dump('aucune erreur en '.$request->data->method); ?>
			<gc:else/>
			<?php var_dump('des erreurs en '.$request->data->method); ?>
		</gc:if>
	</gc:if>
</p>