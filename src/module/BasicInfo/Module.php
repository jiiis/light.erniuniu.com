<?php
namespace BasicInfo;

use BasicInfo\Model\BasicInfoTable;
use BasicInfo\Model\BasicInfo;
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
                'BasicInfo\Model\BasicInfoTable' =>  function($sm) {
                    $tableGateway = $sm->get('BasicInfoTableGateway');
                    $table = new BasicInfoTable($tableGateway);
                    return $table;
                },
                'BasicInfoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BasicInfo());
                    return new TableGateway('basicinfo', $dbAdapter, null, $resultSetPrototype);
                },
                'BasicInfo\Model\SectionTable' =>  function($sm) {
                    $tableGateway = $sm->get('SectionTableGateway');
                    $table = new \BasicInfo\Model\SectionTable($tableGateway);
                    return $table;
                },
                'SectionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \BasicInfo\Model\Section());
                    return new TableGateway('section', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
