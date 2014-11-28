<?php
namespace Rates;

use Rates\Model\Rates;
use Rates\Model\RatesTable;
use Rates\Model\RatesCat;
use Rates\Model\RatesCatTable;
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
                'Rates\Model\RatesTable' =>  function($sm) {
                    $tableGateway = $sm->get('RatesTableGateway');
                    $table = new RatesTable($tableGateway);
                    return $table;
                },
                'RatesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Rates());
                    return new TableGateway('rates', $dbAdapter, null, $resultSetPrototype);
                },
                'Rates\Model\RatesCatTable' =>  function($sm) {
                    $tableGateway = $sm->get('RatesCatTableGateway');
                    $table = new RatesCatTable($tableGateway);
                    return $table;
                },
                'RatesCatTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RatesCat());
                    return  new TableGateway('rates_cat', $dbAdapter, null, $resultSetPrototype);
                },

            ),
        );
    }
}