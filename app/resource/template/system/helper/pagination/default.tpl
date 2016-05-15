<div class="pagination-default">
	{gc:if condition="$paginationFirst == true && $paginationFirstBefore == true && $nbrPage > 1"}
		<div class="pagination-first"><a href="{$urlFirst}">{{lang:.app.helper.pagination.first}}</a></div>
	{/gc:if}
	{gc:if condition="$paginationBefore == true && $paginationFirstBefore == true && $currentPage != 0"}
		<div class="pagination-before"><a href="{$urlBefore}">«</a></div>
	{/gc:if}

	<div class="pagination-list">
		{gc:foreach var="$pagination" as="$key => $value"}
			{gc:if condition="$value != false"}
				<span class="link-active"><a href="{$value}">{$key}</a></span>
			{gc:else/}
				<span class="link-disabled">{$key}</span>
			{/gc:if}
		{/gc:foreach}
	</div>
	{gc:if condition="$paginationAfter == true && $paginationLastAfter == true"}
		<div class="pagination-after"><a href="{$urlAfter}">»</a></div>
	{/gc:if}
	{gc:if condition="$paginationLast == true && $paginationLastAfter == true"}
		<div class="pagination-last"><a href="{$urlLast}">{{lang:.app.helper.pagination.last}}</a></div>
	{/gc:if}
	{gc:if condition="$totalPage == true"}
		{gc:if condition="$currentPage != $nbrPage"}
			<div class="pagination-total"><a href="{$urlLast}">({$nbrpage})</a></div>
		{/gc:if}
	{/gc:if}
	<div class="clear"></div>
</div>