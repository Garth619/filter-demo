<?php if ( ! have_posts() ) : ?>
	
	
	<div id="post-0" class="post error404 not-found">
		
		<h2>Not Found</h2>
		
		<div class="entry-content">
			<p>Apologies, but no posts have been created</p>
			
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->


<?php endif; ?>


<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


	<div id="box">

	
		<h2><?php the_title();?></h2>
		
		<?php the_content();?>
			
		<?php edit_post_link( __( 'Edit'), '', '' ); ?>
		
		
	</div><!-- post -->
	
	
	<?php endwhile; // end of loop ?>

<?php endif; ?>