<?php

namespace WpTheme\Lib;

class TMDB
{

    public static function getUpcomingMovies($page = 1, $language = "en-US"): array
    {
        $data = static::doApiRequest("https://api.themoviedb.org/3/movie/upcoming?language=$language&page=$page");
        return $data;
    }

    public static function getMovieData($movieId): array
    {
        $data = static::doApiRequest("https://api.themoviedb.org/3/movie/$movieId");
        return $data;
    }

    public static function getPopularPeople($page = 1, $language = "en-US"): array
    {
        $data = static::doApiRequest("https://api.themoviedb.org/3/person/popular?language=$language&page=$page");
        return $data;
    }

    public static function getMovieGenres($language = "en"): array
    {
        $data = static::doApiRequest("https://api.themoviedb.org/3/genre/movie/list?language=$language");
        return $data;
    }

    private static function getApiKey(): string
    {
        $apiKey = carbon_get_theme_option("tmdb_api_key");
        return $apiKey;
    }

    private static function doApiRequest($url)
    {
        $curl = curl_init();
        $apiKey = self::getApiKey();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            wp_die($err);
        }

        $data = json_decode($response, true);

        return $data;
    }
}
