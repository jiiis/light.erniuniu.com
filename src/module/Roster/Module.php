<?php
namespace Roster;

use Roster\Model\Roster;
use Roster\Model\RosterTable;
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
                'Roster\Model\RosterTable' =>  function($sm) {
                    $tableGateway = $sm->get('RosterTableGateway');
                    $table = new RosterTable($tableGateway);
                    return $table;
                },
                'RosterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Roster());
                    return new TableGateway('roster', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}