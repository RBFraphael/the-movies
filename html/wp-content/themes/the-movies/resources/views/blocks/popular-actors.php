<div class="block block__popular-actors">
    <div class="row">
        <div class="col-12 block__popular-actors__title">
            <h2 class="block__popular-actors__title__text">
                <?php echo $args['title']; ?>
            </h2>
        </div>
    </div>

    <div class="row block__popular-actors__actors g-5">
        <?php foreach($args['actors'] as $actor): ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="block__popular-actors__actors__actor">
                    <img src="<?php echo get_the_post_thumbnail_url($actor->ID, 'full'); ?>">
                    <div>
                        <h3 class="block__popular-actors__actors__actor__name">
                            <?php echo $actor->post_title; ?>
                        </h3>
                        <p class="block__popular-actors__actors__actor__bio">
                            <?php echo get_post_meta($actor->ID, 'tmdb_popularity', true); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>