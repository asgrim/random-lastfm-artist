<?php
declare(strict_types=1);

use Asgrim\RandomArtist\ArtistListFetcher;
use Asgrim\RandomArtist\Middleware;
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
        ],
    ],
    ConfigAbstractFactory::class => [
        Middleware\IndexAction::class => [
            TemplateRendererInterface::class,
            ArtistListFetcher\ArtistListFetcher::class,
            'config',
        ],
    ],
];
