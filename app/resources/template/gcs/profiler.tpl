<gc:include file="gcs/include/function" />
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>GCsystem Profiler</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="{{def:IMG_PATH}}gcs/logo.png" />
</head>
<body>
<style>
    <gc:minify>
    <gc:call template="gcsystemHtmlDefault"/>
    #body{
        width: 100%;
        overflow: auto;
        padding-top: 10px;;
    }

    header .content{
        width: 100%;
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
		margin: 0px 10px 10px 0;
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
		margin-left: 10px;
		margin-bottom: 7px;
		padding: 8px;
		outline:none;
	}
    </gc:minify>
</style>
<header>
    <div class="content">
        <h1 style="float:left">
            Profiler [{data['url']}]
        </h1>
    </div>
</header>
<div id="body">
    <div class="content">
		<form method="post" action="">
			<input type="text" name="id" placeholder="{{lang:gc_profiler_id}}"/>
			<input type="submit" value="{{lang:gc_profiler_send}}"/>
		</form>
		<br />
        <section>
            <div class="title">
                <h3>{{lang:gc_profiler_exec_time}} : {data['timeExec']}</h3>
            </div>
            <div class="content-section">
                <ul>
                    <gc:foreach var="$data['timeExecUser']" as="$key => $value">
                        <gc:if cond="isset($value[1])">
                            <li><strong>{key}</strong> : {{php: echo $value[1]-$value[0]}}</li>
                        </gc:if>
                    </gc:foreach>
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>{{lang:gc_profiler_sql}}</h3>
            </div>
            <div class="content-section">
                <gc:foreach var="$data['sql']" as="$key => $value">
                    <div><pre>{{php: echo preg_replace('#([\t]+)#isU', '', $value['query-executed']);}}</pre></div>
                </gc:foreach>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>{{lang:gc_profiler_templates}}</h3>
            </div>
            <div class="content-section">
                <ul>
                    <gc:foreach var="$data['template']" as="$key => $value">
                        <li>{value}</li>
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
                    {{php: $this->printArray($data['get']); }}
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>POST</h3>
            </div>
            <div class="content-section">
                {{php: $this->printArray($data['post']); }}
            </div>
        </section>
        <section>
            <div class="title">
                <h3>SESSION</h3>
            </div>
            <div class="content-section">
                <ul>
                    {{php: $this->printArray($data['session']); }}
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>COOKIE</h3>
            </div>
            <div class="content-section">
                <ul>
                    {{php: $this->printArray($data['cookie']); }}
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>FILES</h3>
            </div>
            <div class="content-section">
                <ul>
                    {{php: $this->printArray($data['files']); }}
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>SERVER</h3>
            </div>
            <div class="content-section">
                <ul>
                    {{php: $this->printArray($data['server']); }}
                </ul>
            </div>
        </section>
        <section>
            <div class="title">
                <h3>{{lang:gc_profiler_files}}</h3>
            </div>
            <div class="content-section">
                <ul>
                    <gc:foreach var="$data['controller']" as="$key => $value">
                        <li>{value}</li>
                    </gc:foreach>
                </ul>
            </div>
        </section>
    </div>
    <script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
    <script type="text/javascript" defer>
        $(document).ready(function(e){
            updateHeight();
            $(window).resize(function() {
                updateHeight();
            });

            function updateHeight(){
                $('#body').height( $(window).outerHeight()-$("header").outerHeight()-$("footer").outerHeight());
            }
        });

        function save(){
            $('#form').submit();
        }
    </script>
</body>
</html>