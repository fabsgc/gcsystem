<gc:block name="gcsHtmlDefault()">
*{
	box-sizing: border-box;
}
html, body{
	background-color: #E1E1E1;
	font-family: Calibri, sans-serif;
	padding: 0;
	margin: 0;
}
header{

	margin: auto;
	height: 80px;
	border-bottom: 10px solid #E74C3C;
	background-color: white;
}
header .content{
	width: 750px;
	margin: auto;
	padding: 5px 5px 5px 5px;
}
header h1{
	line-height: 60px;
	font-size: 30px;
	color: #E74C3C;
	padding-left: 65px;
	background: url('{HTML_WEB_PATH}gcs/image/logo60.png') top left no-repeat;
	margin: 0;
}
#main{
	width: 750px;
	min-height: 200px;
	margin: auto;
	background-color: white;
}
#main .content{
	padding: 5px 12px 5px 12px;
}
#main h1{
	color: #E74C3C;
	margin-top: 0;
}
#main a{
	color: #E74C3C;
}
footer{
	width: 750px;
	margin: auto;
	color: white;
	padding: 5px;
	background-color: #E74C3C;
}
textarea.large{
  	width: 32%;
  	height: 75px;
  		margin-left:10px;
  		margin-bottom:7px;
  		padding: 5px;
}
a.button{
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
	text-decoration:none;
}
</gc:block>