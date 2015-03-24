<?php

namespace Managlea\TestingBundle\Utility\ResourceManager;

use Managlea\CoreBundle\Utility\ResourceManager as BaseResourceManager;

class Testing extends BaseResourceManager
{
    /**
     * @var array
     */
    protected $resourceMapping = array(
        'foos' => 'Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo',
        'bars' => 'Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Bar',
        'bazs' => 'Managlea\TestingBundle\Utility\ResourceMapper\Baz'
    );
}