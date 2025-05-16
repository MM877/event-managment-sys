<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $this->redirect('/');
    }
    
    public function render()
    {
        return view('livewire.layout.navigation');
    }
} 