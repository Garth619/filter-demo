
<?php if ( ! have_posts() ) : ?>
	
	
	<div id="post-0" class="post error404 not-found">
		
		<h2>Not Found</h2>
		
		<div class="entry-content">
			<p>Apologies, but no results were found</p>
			
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->


<?php endif; ?>


<?php while ( have_posts() ) : the_post(); ?>


		<?php the_content();?>
			
		<?php edit_post_link( __( 'Edit', 'twentyten' ), '', '' ); ?>
			
		
<?php endwhile; // end of loop ?> 



<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	
	<div id="nav-below" class="navigation">
		
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
			
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
	
	</div><!-- #nav-below -->

<?php endif; ?>

