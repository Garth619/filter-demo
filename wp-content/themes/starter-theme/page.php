<?php get_header(); ?>

<?php // list_cpt_list();?>


<div class="container">

	<?php get_template_part( 'loop', 'index' );?>

</div><!-- container -->

<?php if(get_option('demo-radio') == 2) {
	
echo "test";


}?>


<?php //use get_template_part( 'loop', 'page' ) if needed ?>

	

<?php // get_sidebar(); ?>

<?php get_footer(); ?>
