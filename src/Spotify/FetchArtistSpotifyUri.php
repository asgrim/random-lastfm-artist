<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\Spotify;

interface FetchArtistSpotifyUri
{
    public function fetch(string $artistName) : string;
}
