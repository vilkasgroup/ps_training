<?php

namespace Invertus\Training\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class TrainingArticleController extends FrameworkBundleAdminController
{

    public function listingAction()
    {
        return $this->render('@Modules/training/views/templates/admin/adminListingController.html.twig');
    }

}
