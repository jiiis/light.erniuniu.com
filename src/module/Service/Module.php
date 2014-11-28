<?php
namespace Service;

use Service\Model\Service;
use Service\Model\ServiceTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Service\Model\ServiceTable' =>  function($sm) {
                    $tableGateway = $sm->get('ServiceTableGateway');
                    $table = new ServiceTable($tableGateway);
                    return $table;
                },
                'ServiceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Service());
                    return new TableGateway('service', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}