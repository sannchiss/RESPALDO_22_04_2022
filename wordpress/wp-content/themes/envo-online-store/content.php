<article class="col-md-12">
    <div <?php post_class(); ?>>                    
        <div class="news-item row">
            <?php envo_online_store_thumb_img('envo-online-store-med', 'col-md-6'); ?>
            <div class="news-text-wrap <?php echo esc_attr(has_post_thumbnail() ? 'col-md-6' : 'col-md-12' ) ?>">
                <?php envo_online_store_author_meta(); ?>
                <div class="content-date-comments">
                    <?php envo_online_store_widget_date(); ?>
                    <?php envo_online_store_widget_comments(); ?>
                </div>
                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                <div class="post-excerpt">
                    <?php the_excerpt(); ?>
                </div><!-- .post-excerpt -->
            </div><!-- .news-text-wrap -->
        </div><!-- .news-item -->
    </div>
</article>
