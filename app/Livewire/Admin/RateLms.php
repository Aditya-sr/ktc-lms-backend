<?php

namespace App\Livewire\Admin;

use App\Models\Admin\RateLms as AdminRateLms;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RateLms extends Component
{
    public $feedback = '';
    public $rating = 0;
    public $rated = false;
    public $organization;

    public function mount()
    {
        $user = Auth::user();
        $this->organization = $user->organization;

        // Check if organization has already rated
        $existingRating = AdminRateLms::where('organization_id', $this->organization->id)->first();
        $this->rated = $existingRating ? true : false;

        if ($this->rated) {
            $this->feedback = $existingRating->feedback;
            $this->rating = $existingRating->rating;
        }
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function submit()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string',
        ]);

        AdminRateLms::updateOrCreate(
            ['organization_id' => $this->organization->id],
            [
                'feedback' => $this->feedback,
                'rating' => $this->rating,
                'status' => true
            ]
        );

        $this->rated = true;
        session()->flash('message', 'Thank you for your feedback! We appreciate your time and input.');
    }

    public function render()
    {
        return view('livewire.admin.rate-lms');
    }
}
