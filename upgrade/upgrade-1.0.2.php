<?php

function upgrade_module_1_0_2(training $module)
{
    return Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'training_article ADD COLUMN `id_product` INTEGER(10) DEFAULT NULL');
}
