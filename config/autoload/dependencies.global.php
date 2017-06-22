<?php
declare(strict_types=1);

use Asgrim\RandomArtist\ArtistListFetcher;
use Asgrim\RandomArtist\Middleware;
use Asgrim\RandomArtist\Spotify;
use Http\Client\HttpClient;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

return [
    'dependencies' => [
        'abstract_factories' => [
            ConfigAbstractFactory::class,
        ],
        'factories' => [
            Middleware\IndexAction::class => ConfigAbstractFactory::class,
            HttpClient::class => new class implements FactoryInterface {
                public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
                {
                    return new Http\Client\Curl\Client();
                }
            },
            ArtistListFetcher\ArtistListFetcher::class => new class implements FactoryInterface {
                public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
                {
                    $config = $container->get('config');
                    return new ArtistListFetcher\CachingArtistListFetcher(
                        new ArtistListFetcher\LastFmArtistListFetcher(
                            $container->get(HttpClient::class),
                            $config['last-fm']['apiBaseUrl'],
                            $config['last-fm']['apiKey']
                        ),
                        $config['random-lastfm-artist']['cachedArtistListDirectory']
                    );
                }
            },
            Spotify\FetchArtistSpotifyUri::class => new class implements FactoryInterface {
                public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
                {
                    $config = $container->get('config');
                    return new Spotify\SpotifyFetchArtistSpotifyUri(
                        new \League\OAuth2\Client\Provider\GenericProvider([
                            'clientId' => $config['spotify']['clientId'],
                            'clientSecret' => $config['spotify']['clientSecret'],
                            'urlAuthorize' => $config['spotify']['authorizeUrl'],
                            'urlAccessToken' => $config['spotify']['tokenUrl'],
                            'urlResourceOwnerDetails' => $config['spotify']['resourceUrl'],
                        ]),
                        $container->get(HttpClient::class),
                        $config['spotify']['searchBaseUrl']
                    );
                }
            },
        ],
    ],
    ConfigAbstractFactory::class => [
        Middleware\IndexAction::class => [
            TemplateRendererInterface::class,
            ArtistListFetcher\ArtistListFetcher::class,
            Spotify\FetchArtistSpotifyUri::class,
            'config',
        ],
    ],
];
