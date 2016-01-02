<gc:extends file="main"/>
<form method="post" action="">
	<input type="text" id="page" name="id" placeholder="{{lang:gcs.profiler.page-id}}"/>
	<input type="submit" id="submit" value="{{lang:gcs.profiler.page-send}}"/>
</form>
<br />
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.time}} : {$data['time']} ms</h3>
</div>
<div class="content-section">
	<ul>
		<gc:foreach var="$data['timeUser']" as="$key => $value">
			<li><strong>{$key}</strong> : {$value}</li>
		</gc:foreach>
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.request}}</h3>
</div>
<div class="content-section">
	{{php: $request = unserialize($data['request'])}}
	<ul>
		<li><strong>name</strong> : {$request->name}</li>
		<li><strong>src</strong> : {$request->src}</li>
		<li><strong>controller</strong> : {$request->controller}</li>
		<li><strong>action</strong> : {$request->action}</li>
		<li><strong>cache</strong> : {$request->cache}</li>
		<li><strong>logged</strong> : {$request->logged}</li>
		<li><strong>access</strong> : {$request->access}</li>
		<li><strong>method</strong> : {$request->method}</li>
		<li><strong>lang</strong> : {$request->lang}</li>
		<li><strong>header method</strong> : {$request->data->method}</li>
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.response}}</h3>
</div>
<div class="content-section">
	{{php: $response = unserialize($data['response']); }}
	<ul>
		<li><strong>{{lang:gcs.profiler.response-status}}</strong> : {$response->status()}</li>
		<li><strong>Content Type : {$response->contentType()}</li>
		<li><strong>Headers :</strong>
			{{php: printArray($response->header()); }}
		</li>
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.sql}}</h3>
</div>
<div class="content-section">
	<gc:foreach var="$data['sql']" as="$key => $value">
		<div class="query-header">{$key} ({$value['time']} ms)</div>
		<div>
			<pre>{{php:
				$sql = preg_replace('#([\t]+)#isU', '', $value['query']);
				echo join("\n", array_map("trim", explode("\n", trim($sql))));
				}}
			</pre>
		</div>
	</gc:foreach>
</div>
</section>
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.template}}</h3>
</div>
<div class="content-section">
	<ul>
		<gc:foreach var="$data['template']" as="$key => $value">
			<li>{$key} ({$value['time']} ms)</li>
		</gc:foreach>
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>GET</h3>
</div>
<div class="content-section">
	<ul>
		{{php: printArray($data['get']); }}
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>POST</h3>
</div>
<div class="content-section">
	{{php: printArray($data['post']); }}
</div>
</section>
<section>
<div class="title">
	<h3>SESSION</h3>
</div>
<div class="content-section">
	<ul>
		{{php: printArray($data['session']); }}
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>COOKIE</h3>
</div>
<div class="content-section">
	<ul>
		{{php: printArray($data['cookie']); }}
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>FILES</h3>
</div>
<div class="content-section">
	<ul>
		{{php: printArray($data['files']); }}
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>SERVER</h3>
</div>
<div class="content-section">
	<ul>
		{{php: printArray($data['server']); }}
	</ul>
</div>
</section>
<section>
<div class="title">
	<h3>{{lang:gcs.profiler.file}}</h3>
</div>
<div class="content-section">
	<ul>
		<gc:foreach var="$data['controller']" as="$key => $value">
			<li>{$value}</li>
		</gc:foreach>
	</ul>
</div>
</section>