<?php

namespace App\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class RecipeComponent
{
    public int $id;
    public string $name;
    public string $description;

}