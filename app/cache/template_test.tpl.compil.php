<?php 

$var = array(0,1,2,3,4,5,6,7,8,9,10);
 ?>



<gc:template name="mytemplate" vars="$string1, $string2, $string3">
  <?php echo ($string1); ?>
  <?php echo ($string2); ?>
  <?php echo ($string3); ?>
</gc:template>



<gc:call template="mytemplate" />

<?php if(1==1) { ?>

<?php } ?>