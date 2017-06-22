<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\ArtistListFetcher;

interface ArtistListFetcher
{
    public function fetchArtistList(string $username) : array;
}
