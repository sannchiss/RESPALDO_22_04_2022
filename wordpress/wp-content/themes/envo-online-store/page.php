<?php get_header(); ?>

<!-- start content container -->
<div class="row">
    <article class="col-md-<?php envo_online_store_main_content_width_columns(); ?>">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>                          
                <div <?php post_class(); ?>>                   
                    <div class="main-content-page single-content">                            
                        <div class="single-entry-summary">                              
                            <?php do_action('envo_online_store_before_content'); ?>
                            <?php the_content(); ?>
                            <?php do_action('envo_online_store_after_content'); ?>
                        </div>                               
                        <?php wp_link_pages(); ?>                                                                                     
                        <?php comments_template(); ?>
                    </div>
                </div>        
            <?php endwhile; ?>        
        <?php else : ?>            
            <?php get_template_part('content', 'none'); ?>        
        <?php endif; ?>    
    </article>       
    <?php get_sidebar('right'); ?>
</div>
<!-- end content container -->

<?php get_footer(); ?>
