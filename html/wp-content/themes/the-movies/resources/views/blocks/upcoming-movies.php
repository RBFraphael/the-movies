<div class="block block__upcoming-movies">
    <div class="row">
        <div class="col-12 block__upcoming-movies__title">
            <h2 class="block__upcoming-movies__title__text">
                <?php echo $args['fields']['title']; ?>
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
                                    <h1 class="display-2"><?php echo get_the_title($movie->ID); ?></h1>
                                    <p class="mb-0"><?php echo date('F j, Y', strtotime(get_post_meta($movie->ID, 'tmdb_release_date', true))); ?></p>
                                    <?php
                                    $genres = get_the_terms($movie->ID, "genre");
                                    $genres = is_array($genres) ? array_map(function (WP_Term $genre) {
                                        return $genre->name;
                                    }, $genres) : ['No genres'];
                                    ?>
                                    <p><?php echo implode(", ", $genres); ?></p>
                                    <a href="<?php echo get_the_permalink($movie->ID); ?>" class="btn btn-light border-0 rounded-0 px-4 py-2">More Info </a>
                                </div>
                                <div class="block__upcoming-movies__movies__movie__poster">
                                    <?php $image = get_post_meta($movie->ID, 'tmdb_poster_path', true); ?>
                                    <img src="https://image.tmdb.org/t/p/original<?php echo $image; ?>" alt="<?php echo get_the_title($movie) ?>" class="img-fluid">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>