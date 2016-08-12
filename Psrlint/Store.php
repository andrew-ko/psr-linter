<?php

namespace Psrlint;

class Store
{
    private $messages;
    private $reducer;

    public function __construct($reducer)
    {
        $this->reducer = $reducer;
        $this->messages = [];
    }

    public function dispatch($actionType, $payload)
    {
        $this->messages = call_user_func(
            $this->reducer,
            $this->messages,
            [
                'actionType' => $actionType,
                'payload' => $payload

            ]
        );
    }

    public function getReport()
    {
        return $this->messages;
    }

    # public function replaceReducer($nextReducer)
    # {
    #     $this->currentReducer = $nextReducer;
    # }

    # public function subscribe($cb)
    # {
    #     $this->listeners[] = $cb;
    # }
}
