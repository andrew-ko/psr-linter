<?php

namespace Psrlint;

class Store
{
    private $messages;
    private $reducer;
    private $listeners;

    public function __construct($reducer)
    {
        $this->reducer = $reducer;
        $this->listeners = [];
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

        foreach ($this->listeners as $listener) {
            $listener($this->messages, $actionType, $payload);
        }
    }

    public function getResult()
    {
        return $this->messages;
    }

    public function subscribe($cb)
    {
        if (is_callable($cb)) {
            $this->listeners[] = $cb;
        } else {
            throw new \Psrlint\Error('Subscriber must be a callable.');
        }
    }

    # public function replaceReducer($nextReducer)
    # {
    #     $this->currentReducer = $nextReducer;
    # }
}
