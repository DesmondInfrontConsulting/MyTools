<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class LoadingModal extends Component
{
    public $showModal = true;
    public function render()
    {
        return view('livewire.loading-modal');
    }

    #[On('hide-modal')]
    public function hideModal()
    {
        $this->showModal = false;
    }
}
