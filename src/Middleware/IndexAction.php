<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\Middleware;

use Asgrim\RandomArtist\ArtistListFetcher\ArtistListFetcher;
use Asgrim\RandomArtist\Spotify\FetchArtistSpotifyUri;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

final class IndexAction implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var ArtistListFetcher
     */
    private $artistListFetcher;

    /**
     * @var string
     */
    private $user;

    /**
     * @var FetchArtistSpotifyUri
     */
    private $fetchArtistSpotifyUri;

    public function __construct(
        TemplateRendererInterface $renderer,
        ArtistListFetcher $artistListFetcher,
        FetchArtistSpotifyUri $fetchArtistSpotifyUri,
        \ArrayObject $config
    ) {
        $this->renderer = $renderer;
        $this->artistListFetcher = $artistListFetcher;
        $this->fetchArtistSpotifyUri = $fetchArtistSpotifyUri;
        $this->user = $config['random-lastfm-artist']['user'];
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $list = $this->artistListFetcher->fetchArtistList($this->user);
        $artist = $list[array_rand($list)];
        return new HtmlResponse($this->renderer->render(
            'app::display-artist',
            [
                'artist' => $artist,
                'spotifyUri' => $this->fetchArtistSpotifyUri->fetch($artist),
            ]
        ));
    }
}
