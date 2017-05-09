<!DOCTYPE html>
<html lang="fr">
<head>
    <title>{$title}</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/{{path:IMAGE}}logo.png" />
    {gc:if condition="self::Request()->controller == 'index' and self::Request()->action == 'default'"}
		{gc:asset type="css" files="web/gcs/css/default.css,web/gcs/css/index.css" cache="5"/}
	{gc:else/}
		{gc:asset type="css" files="web/gcs/css/default.css,web/gcs/css/profiler.css" cache="5"/}
	{/gc:if}
</head>
<body id="body">
    <header id="header">
        <div class="content">
            <h1>{$title}</h1>
        </div>
    </header>
    <div id="main">
        <div class="content">
            {gc:child/}
        </div>
    </div>
    <footer id="footer">Gcsystem V{VERSION}</footer>
    <script type="text/javascript">
        height();

        window.onresize = function(event) {
            height();
        };

        function height(){
            document.getElementById('main').style.height = window.innerHeight - document.getElementById('header').offsetHeight - document.getElementById('footer').offsetHeight + "px";
            if(document.getElementById('page'))
                document.getElementById('page').style.width = window.innerWidth - document.getElementById('submit').offsetWidth - 50 + "px";
            if(document.getElementById('form'))
                document.getElementById('form').style.width = window.innerWidth - document.getElementById('submit').offsetWidth - 30 + "px";
        }
    </script>
</body>
</html>