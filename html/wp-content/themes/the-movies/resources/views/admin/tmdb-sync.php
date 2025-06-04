<hr>
<h3>Sync Content</h3>
<p>Sync movies, actors and genres from TMDB with WordPress</p>

<table>
    <tbody>
        <tr>
            <th scope="row">Upcoming Movies</th>
            <td>
                <button class="button button-primary" id="sync-movies" type="button" <?php echo $args['importingMovies'] ? 'disabled' : ''; ?>>
                    <img src="/wp-admin/images/spinner.gif" style="display:<?php echo $args['importingMovies'] ? 'inline' : 'none'; ?>;vertical-align:middle;"> Sync Movies
                </button>
                <button class="button button-secondary" id="process-movies" type="button" <?php echo $args['processingMovies'] ? 'disabled' : ''; ?>>
                    <img src="/wp-admin/images/spinner.gif" style="display:<?php echo $args['processingMovies'] ? 'inline' : 'none'; ?>;vertical-align:middle;"> Process Imported Movies
                </button>
            </td>
        </tr>

        <tr>
            <th scope="row">Popular Actors</th>
            <td>
                <button class="button button-primary" id="sync-actors" type="button" <?php echo $args['importingActors'] ? 'disabled' : ''; ?>>
                    <img src="/wp-admin/images/spinner.gif" style="display:<?php echo $args['importingActors'] ? 'inline' : 'none'; ?>;vertical-align:middle;"> Sync Actors
                </button>
                <button class="button button-secondary" id="process-actors" type="button" <?php echo $args['processingActors'] ? 'disabled' : ''; ?>>
                    <img src="/wp-admin/images/spinner.gif" style="display:<?php echo $args['processingActors'] ? 'inline' : 'none'; ?>;vertical-align:middle;"> Process Imported Actors
                </button>
            </td>
        </tr>

        <tr>
            <th scope="row">Movie Genres</th>
            <td>
                <button class="button button-primary" id="sync-genres" <?php echo $args['importingGenres'] ? 'disabled' : ''; ?>>
                    <img src="/wp-admin/images/spinner.gif" style="display:<?php echo $args['importingGenres'] ? 'inline' : 'none'; ?>;vertical-align:middle;"> Sync Genres
                </button>
            </td>
        </tr>
    </tbody>
</table>

<style>
    table tbody tr th {
        text-align: left;
        width: 200px;
    }

    table tbody tr:not(:last-child) th,
    table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #ccc;
    }

    table tbody tr th,
    table tbody tr td {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>

<script type="text/javascript">
    const syncMoviesButton = document.getElementById("sync-movies");
    syncMoviesButton.addEventListener("click", () => {
        doAction(syncMoviesButton, "/wp-json/the-movies/v1/import-movies", "Finished. All upcoming movies have been synced.", "Failed to sync upcoming movies. Please try again.");
    });

    const processMoviesButton = document.getElementById("process-movies");
    processMoviesButton.addEventListener("click", () => {
        doAction(processMoviesButton, "/wp-json/the-movies/v1/process-movies", "Finished. All movies have been processed.", "Failed to process movies. Please try again.");
    });

    const syncActorsButton = document.getElementById("sync-actors");
    syncActorsButton.addEventListener("click", () => {
        doAction(syncActorsButton, "/wp-json/the-movies/v1/import-actors", "Finished. All popular actors have been synced.", "Failed to sync popular actors. Please try again.");
    });

    const processActorsButton = document.getElementById("process-actors");
    processActorsButton.addEventListener("click", () => {
        doAction(processActorsButton, "/wp-json/the-movies/v1/process-actors", "Finished. All actors have been processed.", "Failed to process actors. Please try again.");
    });

    const syncGenresButton = document.getElementById("sync-genres");
    syncGenresButton.addEventListener("click", () => {
        doAction(syncGenresButton, "/wp-json/the-movies/v1/import-genres", "Finished. All genres have been synced.", "Failed to sync genres. Please try again.");
    });

    const doAction = (button, url, successMessage, errorMessage) => {
            button.querySelector("img").style.display = "inline";
            button.disabled = true;
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: "{}",
            }).then(() => {
                alert(successMessage);
            }).catch((error) => {
                if (error.name === 'AbortError') {
                    return;
                } else {
                    console.error(error);
                    alert(errorMessage);
                }
            }).finally(() => {
                button.querySelector("img").style.display = "none";
                button.disabled = false;
            });
        }

        const checkStatus = () => {
            fetch(`/wp-json/the-movies/v1/status`, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
            }).then((data) => data.json()).then((data) => {
                syncMoviesButton.disabled = data.importingMovies;
                syncMoviesButton.querySelector("img").style.display = data.importingMovies ? "inline" : "none";

                processMoviesButton.disabled = data.processingMovies;
                processMoviesButton.querySelector("img").style.display = data.processingMovies ? "inline" : "none";

                syncActorsButton.disabled = data.importingActors;
                syncActorsButton.querySelector("img").style.display = data.importingActors ? "inline" : "none";

                processActorsButton.disabled = data.processingActors;
                processActorsButton.querySelector("img").style.display = data.processingActors ? "inline" : "none";

                syncGenresButton.disabled = data.importingGenres;
                syncGenresButton.querySelector("img").style.display = data.importingGenres ? "inline" : "none";
            }).catch((error) => {
                console.error(error);
            });
        }

        const checkInterval = setInterval(checkStatus, 5000);
</script>