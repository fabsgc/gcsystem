<goto from="a" />
spdfkspdfokdpfokspdofsipodifpsodfispodfipo
<goto to="a" />
<table>
<tr>
	<td>{val1}</td>
	<td>{val2}</td>
</tr>
</table>
<i>{val3}</i>
{$$_SESSION['id']}
<include file="2" />
<include file="3" />

<if cond="$age >= 18">C'est bon tu peux y aller. 
<elseif cond="$age >= 16" />va te faire foutre
<else />
</if>

<switch cond="$age">
	<case="18">tu peux</case>
	<case="17">tu peux pas</case>
	<default>tu peux paslkjfsldkfj</default>
</switch>

<function name="date" string="d/m/Y" int="16547684" />
<function name="htmlspecialchars" var="$chaine" />

<ul>
	<foreach var="$list" as="$k=>$x">
		<li>{k}<br />
			Valeur {x['val']} (<function name="date" string="d/m/Y h:i:s" var="$x['timestamp']" />)
		</li>
		<foreachelse />flut
	</foreach>
</ul>

<for var="$age" boucle="1-10-2">
	salut salut<br />
</for>dffqsdffffsdfkllS

<variable varme="sqkdqdsl" />
{varme}

<variable varme2=$varme />
{varme2}

_(test_2)_
_(test_1)_
_(test_1)_
_(test_1)_
_(test_1)_