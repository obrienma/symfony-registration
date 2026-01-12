<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Registration
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $petName = '';

    #[LiveProp]
    public string $petType = '';

    #[LiveProp]
    public string $breed = '';

    #[LiveProp]
    public string $breedOption = '';

    #[LiveProp]
    public string $gender = '';

    #[LiveProp]
    public bool $submitted = false;

    #[LiveAction]
    public function submit(): void
    {
        $this->submitted = true;
        // Handle form submission here
    }
}
