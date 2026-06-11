<?php
/**
 * ClinixPro - Hospital Management System
 * Home Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $this->view('home/index', [
            'title' => 'Welcome to ClinixPro'
        ]);
    }
}
