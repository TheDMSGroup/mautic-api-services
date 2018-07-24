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
use MauticPlugin\MauticApiServicesBundle\Entity\ApiServiceEntity;

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
                'api_services_settings',
                'textarea',
                [
                    'data'       => !isset($data['api_services_settings']) ? null : $data['api_services_settings'],
                    'label'      => false,
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.api_services.form.config.name',
                        'rows'    => 10,
                    ],
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param $section
     *
     * @return string|array
     */
    public function getFormNotes($section)
    {
        if ('custom' === $section) {
            return [
                'template'   => 'MauticApiServicesBundle:Integration:form.html.php',
                'parameters' => [],
            ];
        }

        return parent::getFormNotes($section);
    }
}
