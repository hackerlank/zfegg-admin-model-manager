<?php

namespace Zfegg\ModelManager\Db;


use Zend\Db\ResultSet\AbstractResultSet;

class CallbackResultSet extends AbstractResultSet
{

    protected $callable;

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param mixed $callable
     * @return $this
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
        return $this;
    }

    public function current()
    {
        if ($this->buffer === null) {
            $this->buffer = -2; // implicitly disable buffering from here on
        } elseif (is_array($this->buffer) && isset($this->buffer[$this->position])) {
            return $this->buffer[$this->position];
        }
        $data     = $this->dataSource->current();
        $callable = $this->getCallable();
        $data     = $callable($data);

        if (is_array($this->buffer)) {
            $this->buffer[$this->position] = $data;
        }
        return $data;
    }
}