<?php
declare(strict_types=1);

return [
    'random-lastfm-artist' => [
        'user' => 'rj',
        'cachedArtistListDirectory' => __DIR__ . '/../../cache/',
    ],
    'last-fm' => [
        'apiKey' => 'YOUR_API_KEY',
        'apiBaseUrl' => 'https://ws.audioscrobbler.com/2.0/',
    ],
    'spotify' => [
        'searchBaseUrl' => 'https://api.spotify.com/v1/search',
        'clientId' => 'YOUR_CLIENT_ID',
        'clientSecret' => 'YOUR_CLIENT_SECRET',
        'authorizeUrl' => 'https://accounts.spotify.com/authorize',
        'tokenUrl' => 'https://accounts.spotify.com/api/token',
        'resourceUrl' => 'https://api.spotify.com/v1/me',
    ],
];
