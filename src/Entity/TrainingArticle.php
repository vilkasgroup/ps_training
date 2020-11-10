<?php

class TrainingArticle extends ObjectModel
{
    public $name;

    public $description;

    public $type;

    public $active;

    public $id_product;

    /**
     * Definition needs to connect your object to database try to keep field definition close to database
     *
     * multilang=true is needed if you have translatable fields
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'training_article',
        'primary' => 'id_training_article',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 255),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
            'active' => array('type' => self::TYPE_BOOL, 'size' => 100),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 100),
        ),
    );

    public function toArray()
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description

        ];
    }
}
