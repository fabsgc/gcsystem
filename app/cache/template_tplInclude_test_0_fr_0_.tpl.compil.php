<?php 
$var = array(0,1,2,3,4,5,6,7,8,9,10);
$var2 = $_GET['rubrique'];

echo $var2;
 ?>

<gc:block name="myblock">
  <p>salut salut salut <?php echo ($var2); ?></p>
</gc:block>

<gc:template name="mytemplate" vars= "$string1, $string2, $string3" >
  <?php echo ($string1); ?>
  <?php echo ($string2); ?>
  <?php echo ($string3); ?>
</gc:template>
smdsdlfksdlfslksdlfksdjfslkfjslkfjslkfjsdlkfsd