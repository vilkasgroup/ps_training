<?php

function upgrade_module_1_0_1(training $module)
{
    $result = $module->registerHook('displayAdminOrderMain');
    $result &= $module->registerHook('displayAdminOrderLeft');

    $tabRegister = $module->getSymfonyContainer()->get('prestashop.adapter.module.tab.register');
    $moduleRepository = $module->getSymfonyContainer()->get('prestashop.core.admin.module.repository');

    $tabRegister->registerTabs($moduleRepository->getModule($module->name));
    return $result;
}
