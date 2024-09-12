# Livewire Basics
# Video 1 (How it Works)

# To get started, let's review the basics of Livewire and build the obligatory counter example. Next, we'll take a brief look at the request/response lifecycle of a Livewire component.

# live wire works on ajax conecpet make changes in real time. js framework goes under build when you change something. make reactive components without javascript.

# Request Response Lifecycle of LiveWire

# live wire component are render on server that why yits seo friendly
# work on ajax
# after changed server render component and send new html
# and the live wire javascript update what changes in our html.

# we will work on these things in these series.
# forms
# search bar
# datatable componenets with seach bar, filter and pagination.
# comment with alert message.

# docs for livewire installation
https://laravel-livewire.com/

# or you can directly install with laravel

# everytime you want to make something with livewire you will make component because its component based.

# use this command for making live wire components
php artisan make:livewire counter

# It will make two files one is controller and one will be the view.
# The controller class is like a script in view and the view is simple wiew template in view.
# we can call wire:model or wire:click to call methods in controller.

# call livewire component in view file.
<div>
    <@livewire('counter')
</div>

# livewire controller
<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

# livewire view
<div>
    <span>{{ $count }}</span>
    <button wire:click="increment()">+</button>
    <button wire:click="decrement()">-</button>
</div>

# wire class directive on which we can call livewire methods
wire:click 
# like @click in view

wire:model
# like v-model in view

------------------------------------------------------------------------------------------------
