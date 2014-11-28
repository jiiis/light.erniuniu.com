<?php
namespace Girl;

use Girl\Model\Girl;
use Girl\Model\GirlTable;
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

    // Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Girl\Model\GirlTable' =>  function($sm) {
                    $tableGateway = $sm->get('GirlTableGateway');
                    $table = new GirlTable($tableGateway);
                    return $table;
                },
                'GirlTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Girl());
                    return new TableGateway('girl', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}