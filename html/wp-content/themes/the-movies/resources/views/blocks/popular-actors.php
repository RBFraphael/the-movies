<div class="block block__popular-actors">
    <div class="row">
        <div class="col-12 block__popular-actors__title">
            <h2 class="block__popular-actors__title__text">
                <?php echo $args['title']; ?>
            </h2>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 r-cols-lg-4 row-cols-xl-5 block__popular-actors__actors g-5">
        <?php foreach ($args['actors'] as $actor): ?>
            <div class="col">
                <a href="<?php echo get_the_permalink($actor->ID); ?>" class="block__popular-actors__actors__actor">
                    <!-- <img src="<?php echo get_the_post_thumbnail_url($actor->ID, 'full'); ?>"> -->
                    <?php $image = get_post_meta($actor->ID, 'tmdb_profile_path', true); ?>
                    <img src="https://image.tmdb.org/t/p/original<?php echo $image; ?>">
                    <div>
                        <h3 class="block__popular-actors__actors__actor__name">
                            <?php echo $actor->post_title; ?>
                        </h3>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>