<?php get_header(); ?>

<main class="page">
    <?php while(have_posts()): the_post(); ?>
    <div class="content container">
        <?php the_content(); ?>
    </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>