<?php declare(strict_types=1);
 
 namespace PaznerA\NetteInertia;

 use Latte\Compiler\Tag;
 use Latte\Extension;
 
 class InertiaLatte extends Extension
{
    public function getTags(): array
    {
        return [
            'inertia' => [$this, 'inertiaTag'],
        ];
    }

    public function inertiaTag(Tag $tag): string
    {
        return "echo '<div id=\"app\" data-page=\"' . htmlspecialchars(json_encode(\$inertiaData)) . '\"></div>'";
    }
}