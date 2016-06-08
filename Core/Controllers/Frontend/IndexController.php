<?php

class Core_Controllers_Frontend_IndexController extends Core_Pages_Render
{
    //put your code here
    
    public function indexAction()
    {
        $this->getLayout();
        $this->renderLayout();
    }
}
