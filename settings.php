<?php

use application\Controllers\AdminController;
use application\Controllers\HomePageController;
use application\Controllers\LoginController;
use application\Controllers\NabidkaController;
use application\Controllers\RegistrationController;
use application\Controllers\SupplierController;
use application\Views\BasicTemplate;

const DB_SERVER = "localhost";
const DB_NAME = "web";
const DB_USER = "root";
const DB_PASS = "";


const TABLE_UZIVATEL = "uzivatel";
const TABLE_OBJEDNAVKA = "objednavka";
const TABLE_OBSAHUJE = "obsahuje";
const TABLE_PRODUKT = "produkt";


const DEFAULT_WEB_PAGE_KEY = "uvodni_strana";
const WEB_PAGES = array(
    "uvodni_strana" => array(
        "title" => "Burger Express",
        "controller_class_name" => HomePageController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_UVOD,
    ),
    "nabidka_strana" => array(
        "title" => "Nabídka",
        "controller_class_name" => NabidkaController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_NABIDKA,
    ),
    "prihlasovaci_strana" => array(
        "title" => "Přihlášení",
        "controller_class_name" => LoginController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_PRIHLASENI,
    ),
    "registracni_strana" => array(
        "title" => "Registrace",
        "controller_class_name" => RegistrationController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_REGISTRACE,
    ),
    "dodavatel_strana" => array(
        "title" => "Dodavatelská sekce",
        "controller_class_name" => SupplierController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_DODAVATEL,
    ),
    "admin_strana" => array(
        "title" => "Administrátorská sekce",
        "controller_class_name" => AdminController::class,
        "view_class_name" => BasicTemplate::class,
        "template_type" => BasicTemplate::PAGE_ADMIN,
    ),
);