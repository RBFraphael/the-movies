<div class="block block__upcoming-movies">
    <div class="row">
        <div class="col-12 block__upcoming-movies__title">
            <h2 class="block__upcoming-movies__title__text">
                <?php echo $args['title']; ?>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="block__upcoming-movies__movies glide">
                <div class="glide__track" data-glide-el="track">
                    <ul class="glide__slides">
                        <?php foreach ($args['movies'] as $movie) : ?>
                            <div class="block__upcoming-movies__movies__movie glide__slide" data-movie-id="<?php echo $movie->ID; ?>">
                                <div class="block__upcoming-movies__movies__movie__details">
                                    <h1><?php echo get_the_title($movie->ID); ?></h1>
                                    <p><?php echo get_post_meta($movie->ID, 'tmdb_release_date', true); ?></p>
                                </div>
                                <div class="block__upcoming-movies__movies__movie__poster">
                                    <img src="<?php echo get_the_post_thumbnail_url($movie->ID, 'full') ?>" alt="<?php echo get_the_title($movie) ?>" class="img-fluid">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>