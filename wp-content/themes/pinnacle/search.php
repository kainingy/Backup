
      <?php get_template_part('templates/page', 'header');  ?>
  
    <div id="content" class="container">
      <div class="row">
      <div class="main <?php echo kadence_main_class(); ?>  postlist" role="main">

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'pinnacle'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>
<?php global $pinnacle, $postcolumn; if(isset($pinnacle['search_post_grid_columns'])) {$blog_grid_column = $pinnacle['search_post_grid_columns'];} else {$blog_grid_column = '3';}
        if ($blog_grid_column == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $postcolumn = '2';} 
        else if ($blog_grid_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '3';} 
        else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '4';} ?>
    <div id="kad-blog-grid" class="rowtight">
        <?php while (have_posts()) : the_post(); ?>
          <div class="<?php echo $itemsize;?> search_item">
          <?php get_template_part('templates/content', 'searchresults'); ?>
          </div>
        <?php endwhile; ?>
    </div> <!-- Blog Grid -->
<?php if ($wp_query->max_num_pages > 1) : ?>
        <?php if(function_exists('kad_wp_pagenavi')) { ?>
              <?php kad_wp_pagenavi(); ?>   
            <?php } else { ?>      
              <nav class="post-nav">
                <ul class="pager">
                  <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'pinnacle')); ?></li>
                  <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'pinnacle')); ?></li>
                </ul>
              </nav>
            <?php } ?> 
        <?php endif; ?>
          <script type="text/javascript">jQuery( window ).load(function () {var $container = jQuery('#kad-blog-grid');$container.masonry({itemSelector: '.search_item'});});</script>
</div><!-- /.main -->
