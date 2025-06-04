<ul class="list-unstyled p-0">
    <li class="mb-5">
        <form method="GET">
            <p class="text-secondary m-0">Filter by Year</p>
            <?php
            $terms = get_terms([
                'hide_empty' => false,
                'taxonomy' => 'movie_year'
            ]);
            ?>
            <select name="movie_year" id="movie_year" class="form-select rounded-0">
                <option value="">All</option>
                <?php foreach ($terms as $term): ?>
                    <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="text-end">
                <button type="submit" class="btn btn-light btn-sm mt-3 rounded-0">Filter</button>
            </div>
        </form>
    </li>

    <li>
        <form method="GET">
            <p class="text-secondary m-0">Filter by Genre</p>
            <?php
            $terms = get_terms([
                'hide_empty' => false,
                'taxonomy' => 'genre'
            ]);
            ?>
            <select name="genre" id="genre" class="form-select rounded-0">
                <option value="">All</option>
                <?php foreach ($terms as $term): ?>
                    <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="text-end">
                <button type="submit" class="btn btn-light btn-sm mt-3 rounded-0">Filter</button>
            </div>
        </form>
    </li>

    <?php dynamic_sidebar('movies-sidebar'); ?>
</ul>