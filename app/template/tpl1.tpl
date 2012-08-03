<h1>template 1 {var2} { var3 }</h1><br />
<gc:include          file="tpl2" />
 <gc:include file="tpl3" />
 <gc:include file="tpl1" />

{$ $_SESSION['GC_terminalMdp'] } salut

{<gc:function name="htmlspecialchars" string="éééééééééééééé" />}
<gc:function name="print_r" var="$array" />
<gc:variable name = 1111111111111111111111/>

<gc:variable name2 = <gc:function name="htmlspecialchars" string="éééééééééééééé" /> />


{<gc:function name="htmlspecialchars" string="bonjour $name" />}

{<gc:function name=" htmlspecialchars" string="éééééééééééééé" />}

{name}

{\var}

{{def:FOLDER}}

{{fileGc::NOREAD}}

<gc:variable id = 4 />

{id}

<gc:for var="$id" cond="<" boucle="0-15-1">
</gc:for>

<gc:while cond="$id<15">
</gc:while>