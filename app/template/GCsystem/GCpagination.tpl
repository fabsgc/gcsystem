<style>
	.pagination_first, .pagination_before, .pagination_list, .pagination_after, .pagination_last, .pagination_total{
		display : inline-block;
	}
</style>
<div class="pagination">
	<gc:if cond="$paginationFirstLast == true && $paginationFirstBefore == true && $nbrpage > 1">
		<div class="pagination_first"><a href="{urlfirst}">{{lang:pagination_first}}</a></div>
	</gc:if>
	<gc:if cond="$paginationBeforeAfter == true && $paginationFirstBefore == true && $pageActuel != 0">
		<div class="pagination_before"><a href="{urlbefore}">{{lang:pagination_before}}</a></div>
	</gc:if>
	
	<div class="pagination_list">
		<gc:foreach var="$pagination" as="$cle => $val">
			<gc:if cond="$val != false">
				<span class="link_active"><a href="{val}">{cle}</a></span>
			<gc:else />
				<span class="link_disabled">{cle}</span>
			</gc:if>
		</gc:foreach>
	</div>
	<gc:if cond="$paginationBeforeAfter == true && $paginationLastAfter == true">
		<div class="pagination_after"><a href="{urlafter}">{{lang:pagination_after}}</a></div>
	</gc:if>
	<gc:if cond="$paginationFirstLast == true && $paginationLastAfter == true">
		<div class="pagination_last"><a href="{urllast}">{{lang:pagination_last}}</a></div>
	</gc:if>
	<gc:if cond="$totalpage == true">
		<gc:if cond="$pageActuel != $nbrpage">
			<div class="pagination_total"><a href="{urllast}">({nbrpage})</a></div>
		</gc:if>
	</gc:if>
</div>