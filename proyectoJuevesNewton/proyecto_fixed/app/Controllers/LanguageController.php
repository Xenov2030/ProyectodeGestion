<?php
// app/Controllers/LanguageController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\I18n;

class LanguageController extends Controller {
    public function switch() {
        if (isset($_GET['lang'])) {
            I18n::setLang($_GET['lang']);
        }
        
        // Redirigir a la página anterior o al dashboard
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header("Location: $referer");
        exit;
    }
}
