<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Controller\Api;

use Mautic\CoreBundle\Security\Permissions\AbstractPermissions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ApiPermissions.
 */
class ServicesPermissions extends AbstractPermissions
{
    /**
     * {@inheritdoc}
     */
    public function __construct($params)
    {
        parent::__construct($params);

        $this->permissions = [
            'access' => [
                'full' => 1024,
            ],
        ];
        $this->addStandardPermissions('clients', false);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'apiservices';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface &$builder, array $options, array $data)
    {
        $builder->add('apiservices:access', 'permissionlist', [
            'choices' => [
                'full' => 'mautic.api.services.permissions.granted',
            ],
            'label'  => 'mautic.api.servicespermissions.apiaccess',
            'data'   => (!empty($data['access']) ? $data['access'] : []),
            'bundle' => 'apiservices',
            'level'  => 'access',
        ]);

        $this->addStandardFormFields('apiservices', 'clients', $builder, $data, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($name, $perm)
    {
        //ensure api is enabled system wide
        if (empty($this->params['api_enabled'])) {
            return 0;
        }

        return parent::getValue($name, $perm);
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return !empty($this->params['api_enabled']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSynonym($name, $level)
    {
        if ($name == 'access' && $level == 'granted') {
            return [$name, 'full'];
        }

        return parent::getSynonym($name, $level);
    }
}
