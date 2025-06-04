<a href="<?php echo get_the_permalink(); ?>" class="content content__actor">
    <!-- <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>"> -->
    <?php $image = get_post_meta(get_the_ID(), 'tmdb_profile_path', true); ?>
    <img src="https://image.tmdb.org/t/p/original<?php echo $image; ?>">
    <div>
        <h3 class="content__actor__name">
            <?php echo get_the_title(); ?>
        </h3>
    </div>
</a>