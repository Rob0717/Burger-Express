<?php

namespace application\Views;

/**
 * Rozhraní pro šablony
 * @author Robert Onder
 */
interface IView{

    /**
     * @param array $tplData
     * @param string $pageType
     */
    public function printOutput(array $tplData,string $pageType);
}