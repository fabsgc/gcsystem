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
				_(windowinfo_Back)_
			</div>
		</a>
	</div>
	<if cond="$time > 0">
		<meta http-equiv='Refresh' content='{time}; URL="{redirect}"'>";
	</if>
</div>