{{php:

$var = array(0,1,2,3,4,5,6,7,8,9,10);
}}

<gc:block name="myblock">salut</gc:block>

<gc:template name="mytemplate" vars="$string1, $string2, $string3">
  {string1}
  {string2}
  {string3}
</gc:template>

<gc:call block="myblock" />

<gc:call template="mytemplate" />

<gc:if  cond="1==1">

</gc:if>