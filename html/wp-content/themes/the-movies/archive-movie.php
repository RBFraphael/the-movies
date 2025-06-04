<?php get_header(); ?>

<main class="archive-movie">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <form action="<?php echo get_bloginfo('url'); ?>" method="get">
                    <input type="hidden" name="post_type" value="movie">
                    <div class="input-group">
                        <input type="text" name="s" class="form-control" placeholder="Search for a movie" aria-label="Search for a movie" aria-describedby="button-search">
                        <button class="btn btn-primary" type="submit" id="button-search">Find</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex flex-column gap-5">
                <?php while(have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/content', 'movie'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>