<?php

class Core_Controllers_AdminController extends Core_Pages_Render
{
    //put your code here
    
    public function adminAction()
    {
        $this->getAdminLayout();
        $this->renderLayout();
    }
}
