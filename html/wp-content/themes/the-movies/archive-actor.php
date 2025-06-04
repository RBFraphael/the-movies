<?php get_header(); ?>

<main class="archive-movie">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <form action="<?php echo get_bloginfo('url'); ?>" method="get">
                    <input type="hidden" name="post_type" value="actor">
                    <div class="input-group">
                        <input type="text" name="s" class="form-control rounded-0" placeholder="Search for an actor" aria-label="Search for an actor" aria-describedby="button-search">
                        <button class="btn btn-light rounded-0" type="submit" id="button-search">Find</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-2 text-secondary">Actors</h1>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-5 mb-5">
            <?php while (have_posts()): the_post(); ?>
                <div class="col">
                    <?php get_template_part('resources/views/partials/content', 'actor'); ?>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
        <div class="row">
            <div class="col-12">
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