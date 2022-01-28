<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\View;
use Illuminate\Validation\ValidationException;

/**
 * This class initializes the view and defines the $data variable.
 */
class ValidateView
{
    public $valid = true;

    public $messages;

    public $exception = null;

    function __construct(View $view, ?array $rules)
    {
        $this->validate($view, $rules ?? []);
    }

    /**
     * Validates view and save exception and messages.
     */
    private function validate($view, $rules)
    {
        if (! empty($rules)) {
            try {
                $view->validate($rules);
            } catch (ValidationException $exception) {
                $this->exception = $exception;
                $this->messages = $exception->validator->messages()->messages();
                $this->valid = false;
            }
        }
    }

    /**
     * Current view is valid.
     */
    public function isValid()
    {
        return $this->valid;
    }
}