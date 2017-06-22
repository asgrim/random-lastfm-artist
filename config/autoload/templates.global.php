<?php
declare(strict_types=1);

use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\ZendView\ZendViewRendererFactory;

return [
    'dependencies' => [
        'factories' => [
            TemplateRendererInterface::class => ZendViewRendererFactory::class,
        ],
    ],
    'templates' => [
        'layout' => 'layout::default',
        'map' => [
            'layout::default' => 'templates/layout/default.phtml',
        ],
        'paths' => [
            'app' => ['templates/app'],
        ],
    ],
];
