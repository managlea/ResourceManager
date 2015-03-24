<?php

namespace Managlea\CoreBundle\Utility;

abstract class ResourceManager
{
    /**
     * @var array $resourceMapping
     */
    protected $resourceMapping = array();

    /**
     * @var \Managlea\CoreBundle\Utility\RDBMS $rdbms
     */
    private $rdbms;
    private $formFactory;

    public function __construct(
        \Managlea\CoreBundle\Utility\RDBMS $rdbms,
        \Symfony\Component\Form\FormFactory $formFactory = null
    ) {
        $this->rdbms = $rdbms;
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $resourceName
     * @param int $resourceId
     * @return mixed
     */
    public function getSingle($resourceName, $resourceId)
    {
        $resourceMapper = $this->getResourceMapper($resourceName);

        if (!$resourceMapper) {
            return false;
        }

        return $resourceMapper->getSingle($resourceId);
    }

    /**
     * @param string $resourceName
     * @param array $filters
     * @return bool|mixed
     */
    public function getCollection($resourceName, $filters = array())
    {
        $resourceMapper = $this->getResourceMapper($resourceName);

        if (!$resourceMapper) {
            return false;
        }

        return $resourceMapper->getCollection($filters);
    }

    public function postSingle($resourceName, $data = array())
    {
        $resourceMapper = $this->getResourceMapper($resourceName);

        if (!$resourceMapper) {
            return false;
        }

        $resourceMapper->setFormFactory($this->formFactory);
        return $resourceMapper->postSingle($data);
    }

    public function putSingle($resourceName, $resourceId, $data = array())
    {
        $resourceMapper = $this->getResourceMapper($resourceName);

        if (!$resourceMapper) {
            return false;
        }

        $resourceMapper->setFormFactory($this->formFactory);
        return $resourceMapper->putSingle($resourceId, $data);
    }

    public function deleteSingle($resourceName, $resourceId)
    {
        $resourceMapper = $this->getResourceMapper($resourceName);

        if (!$resourceMapper) {
            return false;
        }

        return $resourceMapper->deleteSingle($resourceId);
    }

    /**
     * @param string $resourceName
     * @return bool
     */
    private function isResourceMapped($resourceName)
    {
        return array_key_exists($resourceName, $this->resourceMapping);
    }

    /**
     * @param string $resourceName
     * @return bool|\Managlea\CoreBundle\Utility\ResourceMapper
     */
    private function getResourceMapper($resourceName)
    {
        if (!$this->isResourceMapped($resourceName)) {
            return false;
        }

        $resourceMapperClassName = $this->resourceMapping[$resourceName];
        $resourceMapper = new $resourceMapperClassName;

        if (method_exists($resourceMapper, 'setRDBMS')) {
            $resourceMapper->setRDBMS($this->rdbms);
        }

        return $resourceMapper;
    }
}