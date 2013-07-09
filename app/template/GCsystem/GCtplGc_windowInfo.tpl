<link href="{css}" rel="stylesheet" type="text/css" media="screen, print, handheld" />
<div id="window_info">
	<div id="window_info_header">
		{title}
	</div>
	<div id="window_info_content">
		{content}
	</div>
	<div id="window_info_footer">
		<a href="{redirect}">
			<div id="window_info_bouton">
				{{lang:windowinfo_Back}}
			</div>
		</a>
	</div>
	<gc:if cond="$time > 0">
		<meta http-equiv='Refresh' content='{time}; URL="{redirect}"'>";
	</gc:if>
</div>