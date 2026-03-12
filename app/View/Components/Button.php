<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    public string $classValues;
    private array $classes = [
        'primary' => 'bg-blue-500 text-white',
        'secondary' => 'bg-gray-500 text-white',
        'danger' => 'bg-red-500 text-white',
    ];

    public function __construct(string $type)
    {
        $this->classValues = $this->classes[$type];
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
