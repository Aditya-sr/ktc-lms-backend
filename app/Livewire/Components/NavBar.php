<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NavBar extends Component
{
    public $showSuperAdminLogoutModal = false;
    public $showAdminLogoutModal = false;

    public function confirmLogout()
    {
        if (Auth::user()->role === 'super-admin') {
            $this->showSuperAdminLogoutModal = true;
        } else {
            $this->showAdminLogoutModal = true;
        }
    }

    public function superAdminLogout()
    {
        Auth::logout();
        return redirect()->route('super-admin.login');
    }

    public function adminLogout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function profilePage()
    {
        return redirect()->route('admin.profile', ['organization' => Auth::user()->organization]);
    }

    public function notificationPage()
    {
        return redirect()->route('admin.notification', ['organization' => Auth::user()->organization]);
    }

    public function render()
    {
        return view('livewire.components.nav-bar');
    }
}
