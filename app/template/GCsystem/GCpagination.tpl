<style>
</style>
<div class="pagination">
	<if cond="$paginationFirstLast == true && $paginationFirstBefore == true">
		<div class="pagination_first"><a href="{urlfirst}">_(pagination_first)_</a></div>
	</if>
	<if cond="$paginationBeforeAfter == true && $paginationFirstBefore == true">
		<div class="pagination_before"><a href="{urlbefore}">_(pagination_before)_</a></div>
	</if>
	
	<div class="pagination_list">
		<foreach var="$pagination" as="$cle => $val">
			<if cond="$val != false">
				<span class="link_active"><a href="{val}">{cle}</a></span>
			<else />
				<span class="link_disabled">{cle}</span>
			</if>
		</foreach>
	</div>
	<if cond="$paginationBeforeAfter == true && $paginationLastAfter == true">
		<div class="pagination_before"><a href="{urlafter}">_(pagination_after)_</a></div>
	</if>
	<if cond="$paginationFirstLast == true && $paginationLastAfter == true">
		<div class="pagination_last"><a href="{urllast}">_(pagination_last)_</a></div>
	</if>
</div>