<?php

namespace application\Models;

/**
 * Model pro zahájení Session
 * @author Robert Onder
 */
class MySession{

    /** Při vytvoření instance je zahájena Session */
    public function __construct(){
        session_start();
    }
}