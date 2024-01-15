<?php

namespace application\Controllers;

/**
 * Rozhraní pro kontrolery
 * @author Robert Onder
 */
interface IController{

    /**
     * @param string $pageTitle
     * @return array
     */
    public function show(string $pageTitle):array;
}