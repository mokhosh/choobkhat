<?php

namespace App\Models\States\Session;

use App\Models\Session;
use Spatie\ModelStates\Transition;

class OngoingToFinished extends Transition
{
    public function __construct(
        private readonly Session $session,
    ) {}

    public function handle(): Session
    {
        $this->session->state = new Finished($this->session);
        $this->session->end = now();

        $this->session->save();

        return $this->session;
    }
}
