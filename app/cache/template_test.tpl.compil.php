<?php 
$var = array(0,1,2,3,4,5,6,7,8,9,10);
$var2 = $_GET['rubrique'];

echo $var2;
 ?>

<?php function myblock(){ ?> 
  <p>salut salut salut <?php echo ($var2); ?></p>
 <?php } ?>

<?php function mytemplate($string1, $string2, $string3){ ?> 
  <?php echo ($string1); ?>
  <?php echo ($string2); ?>
  <?php echo ($string3); ?>
 <?php } ?>
smdsdlfksdlfslksdlfksdjfslkfjslkfjslkfjsdlkfsd

<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>
<?php myblock(); ?>

<?php (mytemplate("1", $var2, 1)); ?>

<?php if(1==1) { ?>

<?php }else{ ?>

<?php } ?>

<?php (trim("   test    ")); ?>

<?php if(!empty($var)) { foreach($var as $data) { ?>

<?php }} ?>