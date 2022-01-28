<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->livewire('Million Art Tattoo · Flensburg · Million Art Tattoo · Tattoo', [
            'dashboard.index'
        ]);
    }
}
