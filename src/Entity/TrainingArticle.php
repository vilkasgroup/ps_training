<?php

class TrainingArticle extends ObjectModel
{
    public $name;

    public $description;

    public $type;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'training_article',
        'primary' => 'id_training_article',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'description' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
        ),
    );

}
