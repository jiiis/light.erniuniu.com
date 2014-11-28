<?php
namespace Notice;

use Notice\Model\Notice;
use Notice\Model\NoticeTable;
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
                'Notice\Model\NoticeTable' =>  function($sm) {
                    $tableGateway = $sm->get('NoticeTableGateway');
                    $table = new NoticeTable($tableGateway);
                    return $table;
                },
                'NoticeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notice());
                    return new TableGateway('notice', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}