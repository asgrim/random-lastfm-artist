<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\ArtistListFetcher;

final class CachingArtistListFetcher implements ArtistListFetcher
{
    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * @var ArtistListFetcher
     */
    private $implementation;

    public function __construct(ArtistListFetcher $implementation, string $cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
        $this->implementation = $implementation;
    }

    public function fetchArtistList(string $username) : array
    {
        $cacheFile = $this->cacheDirectory . '/' . $username . '.json';
        if (!file_exists($cacheFile)) {
            $list = $this->implementation->fetchArtistList($username);
            file_put_contents($cacheFile, json_encode($list));
        }

        return json_decode(file_get_contents($cacheFile), true);
    }
}
