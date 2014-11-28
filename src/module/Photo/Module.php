<?php
namespace Photo;

use Photo\Model\Photo;
use Photo\Model\PhotoTable;
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
                'Photo\Model\PhotoTable' =>  function($sm) {
                    $tableGateway = $sm->get('PhotoTableGateway');
                    $table = new PhotoTable($tableGateway);
                    return $table;
                },
                'PhotoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Photo());
                    return new TableGateway('photo', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}