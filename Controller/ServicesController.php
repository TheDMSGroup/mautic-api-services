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

use FOS\RestBundle\Util\Codes;
use Mautic\ApiBundle\Controller\CommonApiController;
use Mautic\LeadBundle\Entity\LeadField;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class FieldApiController.
 */
class ServicesController extends CommonApiController
{
    public function initializeAction(FilterControllerEvent $event)
    {
        // TODO create custom permission

        if (!$this->get('mautic.security')->isGranted(['apiservices:apiservices:access'], 'MATCH_ONE')) {
            return $this->accessDenied();
        }

        $view    = $this->view($fields, Codes::HTTP_OK);
        $context = SerializationContext::create()->setGroups(['leadFieldList']);
        $view->setSerializationContext($context);

        return $this->handleView($view);

    }
}