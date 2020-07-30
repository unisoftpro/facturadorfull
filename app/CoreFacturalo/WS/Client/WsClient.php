<?php

namespace App\CoreFacturalo\WS\Client;

use SoapClient;

class WsClient
{
    private $client;

    /**
     * SoapClient constructor.
     *
     * @param string $wsdl       Url of WSDL
     * @param array  $parameters Soap's parameters
     */
    public function __construct($wsdl = '', $parameters = [])
    {
        if (empty($wsdl)) {
            $wsdl = __DIR__.DIRECTORY_SEPARATOR.'Resources'.
                            DIRECTORY_SEPARATOR.'wsdl'.
                            DIRECTORY_SEPARATOR.'billService.wsdl';
        }else if($wsdl === 'consultCdrStatus'){
            $wsdl = __DIR__.DIRECTORY_SEPARATOR.'Resources'.
                            DIRECTORY_SEPARATOR.'wsdl'.
                            DIRECTORY_SEPARATOR.'billConsultService.wsdl';
        }
        $this->client = new SoapClient($wsdl, $this->getParameters($parameters));
    }

    /**
     * @param $user
     * @param $password
     */
    public function setCredentials($user, $password)
    {
        $this->client->__setSoapHeaders(new WsSecurityHeader($user, $password));
    }

    /**
     * Set Url of Service.
     *
     * @param string $url
     */
    public function setService($url)
    {
        $this->client->__setLocation($url);
    }

    /**
     * @param $function
     * @param $arguments
     *
     * @return mixed
     */
    public function call($function, $arguments)
    {
        return $this->client->__soapCall($function, $arguments);
    }


    private function getParameters($parameters){
        
        $additional_parameters = [
            'stream_context' => stream_context_create([
                'ssl' => [
                    'ciphers'=>'AES256-SHA',
                ],
            ]),
        ];

        return array_merge($parameters, $additional_parameters);
    }

}
