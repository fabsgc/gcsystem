<div id="block_info">
	<div id="block_info_header">
		{title}
	</div>
	<div id="block_info_content">
		{content}
	</div>
	<div id="block_info_footer">
		<a href="{redirect}">
			<div id="window_info_bouton">
				_(blockinfo_Back)_
			</div>
		</a>
	</div>
	<if cond="$time > 0">
		<meta http-equiv='Refresh' content='{time}; URL={redirect}'>";
	</if>
</div>
{time}