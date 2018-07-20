<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticApiServicesBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Mautic\ApiBundle\Controller\CommonApiController;
use GuzzleHttp\Client;
use JMS\Serializer\SerializationContext;
/**
 * Class FieldApiController.
 */
class ServicesApiController extends CommonApiController
{
    protected $integrationSettings;

    protected $em;

    private $client;

    public function initializeAction($service)
    {
        // build request body
        $params = $this->getRequest()->query->all();
        $content = $this->getRequest()->getContent();
        $params['content'] = $content;
        $body = json_encode($params);

        // build request headers
        $blacklist = [
            'authorization',
            'cookie',
            'php-auth-user',
            'php-auth-pw',
            'x-php-ob-level',
        ];
        $curlHeaders = [];
        $settings = $this->getIntegrationSetting();
        $configHeaders = explode(',', $settings['api_services_headers']);
        foreach($configHeaders as $configHeader)
        {
            $headerValue = explode(':', $configHeader);
            if(isset($headerValue[0]) && isset($headerValue[1]))
            {
                $blacklist[] = trim($headerValue[0]);
                $curlHeaders[trim($headerValue[0])] = trim($headerValue[1]);
            }
        }
        $excludeHeadersStr = $settings['api_services_exclude_headers'];
        $excludeHeaders = explode(',', $excludeHeadersStr);
        foreach($excludeHeaders as $excludeHeader)
        {
            $blacklist[] = trim($excludeHeader);
        }
        $headers = $this->getRequest()->headers->all();
        foreach($headers as $headerKey => $value)
        {
            if(!in_array($headerKey, $blacklist))
            {
                $curlHeaders[$headerKey] = $value[0];
            }
        }

        $url = $settings['api_services_endpoint'];

        $settings = [
            'allow_redirects' => [
                'max'             => 10,
                'strict'          => false,
                'referer'         => false,
                'protocols'       => ['https', 'http'],
                'track_redirects' => true,
            ],
            'connect_timeout' => 10,
            'http_errors'     => false,
            'synchronous'     => true,
            'verify'          => false,
            'timeout'         => 30,
            'version'         => 1.1,
            'headers'         => $curlHeaders,
            'body'            => $body,
        ];

        $this->client = new Client($settings);
        $data = $this->client->request('GET', $url, $settings);
        $responceBody = $data->getBody()->getContents();


        // return service responce to requestor
        $view    = $this->view($responceBody, Codes::HTTP_OK);
        $context = SerializationContext::create()->setGroups(['apiServices']);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    private function getIntegrationSetting($key = null)
    {
        if (null === $this->integrationSettings) {
            $this->em = $this->container->get('doctrine.orm.entity_manager');
            /** @var IntegrationRepository $integrationRepo */
            $integrationRepo = $this->em->getRepository('MauticPluginBundle:Integration');
            $integrations    = $integrationRepo->getIntegrations();

            if (!empty($integrations['ApiServices'])) {
                /** @var Integration $integration */
                $integration               = $integrations['ApiServices'];
                $this->integrationSettings = $integration->getFeatureSettings();
            }
        }
        if (!empty($key)) {
            if (isset($this->integrationSettings[$key])){
                return $this->integrationSettings[$key];
            } else {
                return null;
            }
        }

        return $this->integrationSettings;
    }
}