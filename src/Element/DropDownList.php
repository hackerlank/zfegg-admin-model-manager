<?php

namespace Zfegg\ModelManager\Element;


use Gzfextra\Stdlib\OptionsTrait;
use Zfegg\ModelManager\DataSource\DataSourceInterface;

class DropDownList implements ElementInterface
{
    use OptionsTrait;
    protected $dataSource = [],
        $dataTextField = 'text',
        $dataValueField = 'value';

    public function __construct($options = [])
    {
        return $this->setOptions($options);
    }

    /**
     * @return DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param DataSourceInterface $dataSource
     * @return $this
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataTextField()
    {
        return $this->dataTextField;
    }

    /**
     * @param string $dataTextField
     * @return $this
     */
    public function setDataTextField($dataTextField)
    {
        $this->dataTextField = $dataTextField;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataValueField()
    {
        return $this->dataValueField;
    }

    /**
     * @param string $dataValueField
     * @return $this
     */
    public function setDataValueField($dataValueField)
    {
        $this->dataValueField = $dataValueField;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function toStaticSource()
    {
        $data   = [];

        foreach ($this->getDataSource()->select() as $row) {
            $data[] = ['text' => $row[$this->getDataTextField()], 'value' => $row[$this->getDataValueField()]];
        }

        return $data;
    }
}