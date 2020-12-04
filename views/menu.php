<?php

namespace PHPMaker2021\tooms;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(1, "mi_cf_user", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "CfUserList", -1, "", IsLoggedIn() || AllowListMenu('{CC653E94-F1DB-48D9-AF47-12FFD85C39BD}cf_user'), false, false, "", "", false);
echo $sideMenu->toScript();
