{{php:
	//print_r($test);

		$var = 'fabienbeaudimi@hotmail.fr';
}}

{$test2}

<gc:if condition="$test2 == 'salut'">
	ça veut dire salut
</gc:if>

{{url[absolute]:gcs.profiler}}

{{lang:system.http.title:'code' => '404', 'description' => 'fuck you'}}
{{lang[template]:system.http.title:'code' => '404', 'description' => 'fuck you'}}

{{gravatar:$var:100}}

<gc:include file="gcs/include/function" />
<gc:include file="gcs/include/function" compile="false" />

<gc:block name="mytest">
	test
</gc:block>