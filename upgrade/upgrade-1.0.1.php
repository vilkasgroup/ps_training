<?php

/**
 * Upgrade needs to follow this syntax and return true or false
 * parameter should be whatever you are using this upgrade for
 */
function upgrade_module_1_0_1(training $module)
{
    return $module->registerHook('displayProductExtraContent');
}
