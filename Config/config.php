<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Api Services',
    'description' => 'Proxy function for service calls as an API.',
    'version'     => '1.0',
    'author'      => 'Mautic',
    'routes' => [
        'api' => [
            'mautic_api_services_standard' => [
                'path'       => '/services/{service}',
                'controller' => 'MauticApiServicesBundle:ServicesApi:initialize',
                'method'     => 'GET',
            ],
        ],
    ],
    'services'    => [
    ],
];
