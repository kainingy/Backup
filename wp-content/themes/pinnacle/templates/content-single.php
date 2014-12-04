<?php get_template_part('templates/blog', 'post-header'); ?>

<?php global $post, $pinnacle;
if(kadence_display_sidebar()) {$slide_sidebar = 848;} else {$slide_sidebar = 1170;}
            if ( has_post_format( 'video' )) {
              // Get Post Head settings
              $headcontent = get_post_meta( $post->ID, '_kad_video_blog_head', true );
              if(empty($headcontent) || $headcontent == 'default') {
                  if(!empty($pinnacle['video_post_blog_default'])) {
                            $headcontent = $pinnacle['video_post_blog_default'];
                    } else {
                              $headcontent = 'video';
                    }
              }
              $swidth = get_post_meta( $post->ID, '_kad_video_posthead_width', true ); if (!empty($swidth)) $slidewidth = $swidth; else $slidewidth = $slide_sidebar;
              
            } else if (has_post_format( 'gallery' )) {
              $headcontent = get_post_meta( $post->ID, '_kad_gallery_blog_head', true );
              if(empty($headcontent) || $headcontent == 'default') {
                  if(!empty($pinnacle['gallery_post_blog_default'])) {
                          $headcontent = $pinnacle['gallery_post_blog_default'];
                    } else {
                          $headcontent = 'flex';
                    }
              }
              $height = get_post_meta( $post->ID, '_kad_gallery_posthead_height', true ); if (!empty($height)) $slideheight = $height; else $slideheight = 400; 
              $swidth = get_post_meta( $post->ID, '_kad_gallery_posthead_width', true ); if (!empty($swidth)) $slidewidth = $swidth; else $slidewidth = $slide_sidebar; 

            } elseif (has_post_format( 'image' )) {
               $headcontent = get_post_meta( $post->ID, '_kad_image_blog_head', true );
              if(empty($headcontent) || $headcontent == 'default') {
                  if(!empty($pinnacle['image_post_blog_default'])) {
                          $headcontent = $pinnacle['image_post_blog_default'];
                    } else {
                          $headcontent = 'image';
                    }
              }
              $swidth = get_post_meta( $post->ID, '_kad_posthead_width', true ); if (!empty($swidth)) $slidewidth = $swidth; else $slidewidth = $slide_sidebar; 

            } else {
                  $headcontent = 'none';
            } ?>
<div id="content" class="container">
    <div class="row single-article">
      <div class="main <?php echo kadence_main_class(); ?>" role="main">
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('postclass'); ?>>
          <?php if ($headcontent == 'flex') { ?>
              <section class="postfeat">
                <div class="flexslider kad-light-gallery" style="max-width:<?php echo $slidewidth;?>px;">
                <ul class="slides">
                  <?php
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                              foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                  if(empty($image)) {$image = $attachment_url;}
                                echo '<li><a href="'.$attachment_url.'" rel="lightbox"><img src="'.$image.'"/></a></li>';
                              }
                            }
                          }?>                            
                  </ul>
                </div> <!--Flex Slides-->
                <script type="text/javascript">
                  jQuery(window).load(function () {
                      jQuery('.flexslider').flexslider({
                          animation: "fade",
                          animationSpeed: 400,
                          slideshow: true,
                          slideshowSpeed: 7000,

                          before: function(slider) {
                            slider.removeClass('loading');
                          }  
                        });
                      });
              </script>
              </section>
        <?php } else if ($headcontent == 'carouselslider') { ?>
        <section class="postfeat">
          <div id="imageslider" class="loading">
            <div class="carousel_slider_outer fredcarousel fadein-carousel" style="overflow:hidden; max-width:<?php echo $slidewidth;?>px; height: <?php echo $slideheight;?>px; margin-left: auto; margin-right:auto;">
                <div class="carousel_slider">
                    <?php $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                              $attachments = array_filter( explode( ',', $image_gallery ) );
                                if ($attachments) {
                                  foreach ($attachments as $attachment) {
                                        $attachment_url = wp_get_attachment_url($attachment , 'full');
                                        $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                    if(empty($image)) {$image = array($attachment_url,$slidewidth,$slideheight);} 
                                    echo '<div class="carousel_gallery_item" style="float:left; display: table; position: relative; text-align: center; margin: 0; width:auto; height:'.$image[2].'px;">';
                                    echo '<div class="carousel_gallery_item_inner" style="vertical-align: middle; display: table-cell;">';
                                    echo '<img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" />';
                                      ?>
                                    </div>
                                  </div>
                          <?php } } }?>
                    </div>
                    <div class="clearfix"></div>
                      <a id="prevport_carouselslider" class="prev_carousel icon-angle-left" href="#"></a>
                      <a id="nextport_carouselslider" class="next_carousel icon-angle-right" href="#"></a>
                  </div> 
          </div>
           <script type="text/javascript">
                jQuery( window ).load(function () {
                    var $wcontainer = jQuery('.carousel_slider_outer');
                    var $container = jQuery('.carousel_slider_outer .carousel_slider');
                      var align = 'center';
                      var carheight = <?php echo $slideheight; ?>;
                          function init_post_Carousel_slider() {
                          $container.carouFredSel({
                              width: '100%',
                              height: carheight,
                              align: align,
                              auto: {play: true, timeoutDuration: 9000},
                              scroll: {items : 1,easing: 'quadratic'},
                              items: {visible: 1,width: 'variable'},
                              prev: '.carousel_slider_outer .prev_carousel',
                              next: '.carousel_slider_outer .next_carousel',
                              swipe: {onMouse: false,onTouch: true},
                            });
                        }
                          setWidths();
                          init_post_Carousel_slider();
                            jQuery(window).on("debouncedresize", function( event ) {
                              $container.trigger("destroy");
                            setWidths();
                            init_post_Carousel_slider();
                            });
                          $wcontainer.animate({'opacity' : 1});
                          $wcontainer.css({ height: 'auto' });
                          $wcontainer.parent().removeClass('loading');
                          // set all the widths to the elements
                          function setWidths() {
                            var unitWidth = $container.width();
                            $container.children().css({ width: unitWidth });
                            if(jQuery(window).width() <= 768) {
                            carheight = null;
                            $container.children().css({ height: 'auto' });
                          }
                        }

                });
              </script>    
        </section>
        <?php } else if ($headcontent == 'video') { ?>
            <section class="postfeat">
                <div class="videofit" style="max-width: <?php echo $slidewidth;?>px; margin-left: auto; margin-right: auto;">
                    <?php $video = get_post_meta( $post->ID, '_kad_post_video', true ); echo $video; ?>
                </div>
            </section>
        <?php } else if ($headcontent == 'image') {           
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url( $thumb,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
                $image = aq_resize( $img_url, $slidewidth, false ); //resize & crop the image
                    if(empty($image)) { $image = $img_url; }
                      if($image) : ?>
                      <section class="postfeat">
                        <div class="imghoverclass post-single-img"><a href="<?php echo $img_url ?>" rel="lightbox" class=""><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a></div>
                      </section>
                      <?php endif; ?>
        <?php } ?>
        <?php if(isset($pinnacle['hide_author_img']) && $pinnacle['hide_author_img'] == '1') { get_template_part('templates/entry', 'meta-author'); } ?>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry', 'meta-subhead'); ?>
    </header>
    <div class="entry-content clearfix">
      <?php the_content(); ?>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'pinnacle'), 'after' => '</p></nav>')); ?>
    </div>
    <footer class="single-footer clearfix">
      <?php get_template_part('templates/entry', 'meta-footer'); ?>
    </footer>
  </article>
  <?php if(isset($pinnacle['show_postlinks']) &&  $pinnacle['show_postlinks'] == 1) {get_template_part('templates/entry', 'post-links'); }?>
   <?php $authorbox = get_post_meta( $post->ID, '_kad_blog_author', true );
      if(empty($authorbox) || $authorbox == 'default') { if(isset($pinnacle['post_author_default']) && ($pinnacle['post_author_default'] == 'yes')) { pinnacle_author_box(); }}
        else if($authorbox == 'yes'){ pinnacle_author_box(); } ?>
      <?php $blog_carousel_recent = get_post_meta( $post->ID, '_kad_blog_carousel_similar', true ); 
      if(empty($blog_carousel_recent) || $blog_carousel_recent == 'default' ) { 
        if(isset($pinnacle['post_carousel_default'])) {$blog_carousel_recent = $pinnacle['post_carousel_default']; } }
      if ($blog_carousel_recent == 'similar') { get_template_part('templates/similarblog', 'carousel'); 
      } else if( $blog_carousel_recent == 'recent') {get_template_part('templates/recentblog', 'carousel');} ?>

   <?php comments_template('/templates/comments.php'); ?>
<?php endwhile; ?>
</div>

