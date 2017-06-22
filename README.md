# Random Last.fm Artist Picker

## Requirements

 * Docker
 * Docker Compose
 * Last.fm API key ([from here](http://www.last.fm/api/account/create))

## Usage

```bash
$ composer install
$ cp config/autoload/local.php.dist config/autoload/local.php
$ docker-compose build
$ docker-compose up --abort-on-container-exit --force-recreate
```

Configure `local.php` with your username and API key.

Head to [http://localhost:8080](http://localhost:8080). Refresh for a new artist.

### Limitations

 * No tests. deal-with-it.gif
 * Limited to first 1,000 of your top artists.
 * Slow first request
 * Manual cache clearing (delete `./cache/*.json`)
