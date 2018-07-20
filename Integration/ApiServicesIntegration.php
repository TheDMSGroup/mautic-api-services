<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticApiServicesBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

/**
 * Class SourceIntegration.
 *
 * This plugin does not add integrations. This is here purely for name/logo/etc.
 */
class ApiServicesIntegration extends AbstractIntegration
{
    /**
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * @return array
     */
    public function getSupportedFeatures()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ApiServices';
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return 'Api Services';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $data
     * @param string                              $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ('features' == $formArea) {
            $builder->add(
                'api_services_name',
                'text',
                [
                    'label' => 'mautic.api_services.name',
                    'data'       => !isset($data['api_services_name']) ? null : $data['api_services_name'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.name',
                    ],
                ]
            );

            $builder->add(
                'api_services_alias',
                'text',
                [
                    'label' => 'mautic.api_services.alias',
                    'data'       => !isset($data['api_services_alias']) ? null : $data['api_services_alias'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.alias',
                    ],
                ]
            );

            $builder->add(
                'api_services_endpoint',
                'text',
                [
                    'label' => 'mautic.api_services.endpoint',
                    'data'       => !isset($data['api_services_endpoint']) ? null : $data['api_services_endpoint'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.endpoint',
                    ],
                ]
            );
            $builder->add(
                'api_services_description',
                'textarea',
                [
                    'label' => 'mautic.api_services.description',
                    'data'       => !isset($data['api_services_description']) ? null : $data['api_services_description'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.description',
                    ],
                ]
            );
            $builder->add(
                'api_services_headers',
                'textarea',
                [
                    'label' => 'mautic.api_services.headers',
                    'data'       => !isset($data['api_services_apikey']) ? null : $data['api_services_headers'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.headers',
                    ],
                ]
            );
            $builder->add(
                'api_services_exclude_headers',
                'textarea',
                [
                    'label' => 'mautic.api_services.exclude_headers',
                    'data'       => !isset($data['api_services_exclude_headers']) ? null : $data['api_services_exclude_headers'],
                    'attr'  => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.exclude_headers',
                    ],
                ]
            );
        }
    }
}
