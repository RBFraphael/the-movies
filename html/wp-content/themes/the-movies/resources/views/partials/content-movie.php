<div class="content content__movie">
    <div class="content__movie__poster">
        <div class="wrapper">
            <?php $image = get_post_meta(get_the_ID(), 'tmdb_poster_path', true); ?>
            <img src="https://image.tmdb.org/t/p/original<?php echo $image; ?>" alt="<?php echo get_the_title() ?>" class="img-fluid">
        </div>
    </div>
    <div class="content__movie__details">
        <h2><?php echo get_the_title(); ?></h2>
        <p class="mb-0">Released: <?php echo get_post_meta(get_the_ID(), 'tmdb_release_date', true); ?></p>
        <p>Rating: <?php echo get_post_meta(get_the_ID(), 'tmdb_vote_average', true); ?></p>
        <a href="<?php echo get_the_permalink(); ?>" target="_blank" class="btn btn-light px-4 py-2 rounded-0">View more</a>
    </div>
</div>