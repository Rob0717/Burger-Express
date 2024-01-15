<?php

namespace application;

use application\Controllers\IController;
use application\Views\IView;

/**
 * Třída potřebná pro start aplikace
 * @author Robert Onder
 */
class AppStart{

    /**
     * @return void
     */
    public function appStart():void{
        if(isset($_GET["page"]) && array_key_exists($_GET["page"],WEB_PAGES)){
            $pageKey = $_GET["page"];
        }else{
            $pageKey = DEFAULT_WEB_PAGE_KEY;
        }
        $pageInfo = WEB_PAGES[$pageKey];

        /** @var IController $controller */
        $controller = new $pageInfo["controller_class_name"];
        $tplData = $controller->show($pageInfo["title"]);

        /** @var IView $view */
        $view = new $pageInfo["view_class_name"];
        $view->printOutput($tplData,$pageInfo["template_type"]);
    }
}