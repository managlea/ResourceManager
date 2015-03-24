<?php

namespace Managlea\CoreBundle\Tests;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\CoreBundle\Utility\Resource\Action as ResourceAction;
use Managlea\CoreBundle\Utility\Resource\Data\Collection as ResourceCollection;
use Managlea\CoreBundle\Utility\Resource\Data\Single as SingleResource;
use Managlea\TestingBundle\Utility\ResourceManager\Testing as ResourceManager;

class ResourceManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int $fooId Mocked object id
     */
    private $fooId;
    /**
     * @var ResourceManager
     */
    private $resourceManager;

    public function setUp()
    {
        $this->fooId = rand(1,999);
        $rdbms = new RDBMS($this->getEntityManagerMock(true, true));
        $formFactory = $this->getFormFactoryMock();
        $this->resourceManager = new ResourceManager($rdbms, $formFactory);
    }

    public function testGetSingleFail()
    {
        $resource = $this->resourceManager->getSingle('foo', $this->fooId);
        $this->assertEquals(true, $resource === false);
    }

    public function testGetSingleSuccess()
    {
        $resource = $this->resourceManager->getSingle('foos', $this->fooId);
        $this->assertEquals(true, $resource instanceof SingleResource);
        $this->assertEquals(true, $resource->getId() == $this->fooId);
    }

    public function testGetCollectionFail()
    {
        $resource = $this->resourceManager->getCollection('foo');
        $this->assertEquals(true, $resource === false);
    }

    public function testGetCollectionSuccess()
    {
        $resource = $this->resourceManager->getCollection('foos');
        $this->assertEquals(true, $resource instanceof ResourceCollection);
    }

    // POST
    public function testPostSingleFail()
    {
        $resource = $this->resourceManager->postSingle('foo', array());
        $this->assertEquals(true, $resource === false);
    }

    public function testPostSingleSuccess()
    {
        $resource = $this->resourceManager->postSingle('foos');
        $this->assertEquals(true, $resource instanceof ResourceAction);
    }

    // PUT
    public function testPutSingleFail()
    {
        $resource = $this->resourceManager->putSingle('foo', array());
        $this->assertEquals(true, $resource === false);
    }

    public function testPutSingleSuccess()
    {
        $resource = $this->resourceManager->putSingle('foos', 1, array());
        $this->assertEquals(true, $resource instanceof ResourceAction);
    }

    // DELETE
    public function testDeleteSingleFail()
    {
        $resource = $this->resourceManager->deleteSingle('foo', 1);
        $this->assertEquals(true, $resource === false);
    }

    public function testDeleteSingleSuccess()
    {
        $resource = $this->resourceManager->deleteSingle('foos', 1);
        $this->assertEquals(true, $resource instanceof ResourceAction);
    }

    private function getEntityManagerMock($mockRepo = false, $mockFoo = false)
    {
        $foo = null;
        $fooCollection = null;
        $fooRepository = null;

        if ($mockFoo)
        {
            $foo = $this->getMock('\Managlea\TestingBundle\Entity\Foo');
            $foo->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($this->fooId));
            $foo->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('Random Joe'));

            $fooCollection = array($foo);
        }

        if ($mockRepo)
        {
            $fooRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock();
            $fooRepository->expects($this->any())
                ->method('findBy')
                ->will($this->returnValue($fooCollection));
            $fooRepository->expects($this->any())
                ->method('find')
                ->will($this->returnValue($foo));
        }

        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($fooRepository));

        return $entityManager;
    }

    private function getFormFactoryMock()
    {
        $form = $this->getMockBuilder('\Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        $form->expects($this->any())
            ->method('submit')
            ->will($this->returnValue(true));
        $form->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator($form)));
        $form->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));


        $formFactory = $this->getMockBuilder('\Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $formFactory->expects($this->any())
            ->method('create')
            ->will($this->returnValue($form));

        return $formFactory;
    }
}