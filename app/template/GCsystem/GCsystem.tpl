<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>GCsystem</title>
    <meta charset="utf-8" />
    <meta name="robots" content="index,follow" />
    <link rel="icon" type="image/png" href="{{def:IMG_PATH}}GCsystem/logo.png" />
    <script type="text/javascript" src="{{def:JS_PATH}}jquery/jquery.min.js" ></script>
    </script>
  </head>
  <body>
    <style>
      body{
        background-color: #EFEFEF;
        font-family: "Lucida Sans Unicode", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
        font-size: 0.95em;
      }
      #GCsystem{
        width: 810px;
        height: 205px;
        background-color: white;
        border: 1px solid #DFDFDF;
        -moz-border-radius: 16px;
        -webkit-border-radius: 16px;
        border-radius: 3px;
        margin-bottom: 20px;
        word-wrap: break-word;
        position:absolute; 
        top:50px; 
        left:50%; 
        margin-left:-400px; 
      }
      #GCsystem_left{
        float: left;
        width: 200px;
        height: 205px;
        background-color: rgb(230,230,230);
        border-top-left-radius: 2px;
        border-bottom-left-radius: 2px;
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
        <img src="{{def:IMG_PATH}}GCsystem/logo.png" alt="logo"/>
      </div>
      <div id="GCsystem_right">
        <h1>{{lang:bienvenue}}</h1>
        <p>{{lang:content}}</p>
        <ul>
          <li><a href="http://www.gcs-framework.dzv.me/fr/documentation">{{lang:liredoc}}</a></li>
          <li><a href="http://www.gcs-framework.dzv.me/fr/tutorial">{{lang:lirecours}}</a></li>
          <li><a href="{{url:terminal}}">terminal</a>
        </ul>
      </div>
    </div>
  </body>
</html>