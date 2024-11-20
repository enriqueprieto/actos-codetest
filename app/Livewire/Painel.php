<?php

namespace App\Livewire;

use Livewire\Component;

class Painel extends Component
{
    public function render()
    {
        return view('livewire.painel')->extends('layouts.app');
    }
}
