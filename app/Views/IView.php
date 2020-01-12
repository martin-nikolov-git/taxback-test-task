<?php

namespace App\Views;

/**
 * Interface about what does any view object need to implement
 */
interface IView {
    public function render():string;
}