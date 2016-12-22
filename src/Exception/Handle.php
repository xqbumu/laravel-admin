<?php

namespace Encore\Incore\Exception;

class Handle
{
    protected $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function render()
    {
        return view('docore::error', ['e' => $this->exception])->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
