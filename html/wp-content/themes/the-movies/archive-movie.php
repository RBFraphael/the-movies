<?php get_header(); ?>

<main class="archive-movie">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <form action="<?php echo get_bloginfo('url'); ?>" method="get">
                    <input type="hidden" name="post_type" value="movie">
                    <div class="input-group">
                        <input type="text" name="s" class="form-control rounded-0" placeholder="Search for a movie" aria-label="Search for a movie" aria-describedby="button-search">
                        <button class="btn btn-light rounded-0" type="submit" id="button-search">Find</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-12 col-lg-3">
                <?php get_sidebar(); ?>
            </div>
            <div class="col-12 col-lg-9">
                <h4 class="text-light">Movies</h4>
                <div class="d-flex flex-column gap-4">
                    <?php while (have_posts()): the_post(); ?>
                        <?php get_template_part('resources/views/partials/content', 'movie'); ?>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
                <?php
                // TODO: Customize
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('« Previous', 'the-movies'),
                    'next_text' => __('Next »', 'the-movies'),
                ));
                ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>