<gc:include file="gcs/include/function" />
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Profiler [{$data['url']}]</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="{{path:IMAGE}}logo.png" />
</head>
<body>
<style>
    <gc:call block="gcsHtmlDefault()"/>
    #main{
        width: 100%;
        overflow: auto;
        padding-top: 10px;;
    }
    pre{
        width: 100%;
        overflow-x: auto;
        padding: 5px;
        background-color: #d4dfd5;
        margin-top: 0;
    }
    ul{
        word-wrap: break-word;
        margin: 0;
        padding: 0 0 0 20px;
    }
    section{
        background-color: #f4f4f4;
        margin-bottom: 15px;
    }
    section .title{
        background-color: #E74C3C;
        padding: 5px;
    }
    section .title h3{
        color: white;
        margin: 0;
        padding: 0;
    }
    section .content-section{
        padding: 10px;
    }
	input[type='submit'] {
		display: inline-block;
		line-height: 35px;
		margin: 0;
		background-color: #e74c3c;
		cursor: pointer;
		border-radius: 2px;
		color: white;
		font-size: 17px;
		color: white !important;
		padding: 0 10px;
		-webkit-transition-duration: 0.15s;
		-moz-transition-duration: 0.15s;
		-ms-transition-duration: 0.15s;
		-o-transition-duration: 0.15s;
		transition-duration: 0.15s;
		box-shadow: rgba(0, 0, 0, 0.0980392) 0px -1px 0px inset, rgba(0, 0, 0, 0.0980392) 0px 2px 0px;
		text-decoration: none;
		border: none;
		outline:none;
	}
	input[type='text']  {
		width: 500px;
		padding: 8px;
		outline:none;
	}
    .query-header{
        width: 100%;
        background-color: #C3CBC4;
        color: black;
        padding: 5px;
    }
	header .content{
		width: 100%;
		margin: auto;
		padding: 5px 5px 5px 5px;
	}
</style>
<header id="header">
    <div class="content">
        <h1 style="float:left">
            Profiler [{$data['url']}]
        </h1>
    </div>
</header>
<div id="main">
    <div class="content">
		<form method="post" action="">
			<input type="text" id="page" name="id" placeholder="{{lang:gcs.profiler.page-id}}"/>
			<input type="submit" value="{{lang:gcs.profiler.page-send}}"/>
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
                    <div><pre>{{php: echo preg_replace('#([\t]+)#isU', '', $value['query']);}}</pre></div>
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
    </div>
    <script type="text/javascript" defer>
        updateHeight();

        window.onresize = function(event) {
            updateHeight();
        };

        function updateHeight(){
            document.getElementById('main').style.height = window.innerHeight - document.getElementById('header').offsetHeight + "px";
            document.getElementById('page').style.width = window.innerWidth - 165 + "px";
        }

        function save(){
            document.getElementById('form').submit();
        }
    </script>
</body>
</html>