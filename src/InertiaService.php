<?php declare(strict_types=1);
 
 namespace PaznerA\NetteInertia;
 
 class InertiaService
{
    private string $framework;
    private string $rootView;
    private bool $ssr;
    private array $sharedProps = [];

    public function setFramework(string $framework): void
    {
        $this->framework = $framework;
    }

    public function setRootView(string $rootView): void
    {
        $this->rootView = $rootView;
    }

    public function setSSR(bool $ssr): void
    {
        $this->ssr = $ssr;
    }

    public function share(string $key, $value): void
    {
        $this->sharedProps[$key] = $value;
    }

    public function render(string $component, array $props = []): array
    {
        return [
            'component' => $component,
            'props' => array_merge($this->sharedProps, $props),
            'url' => $_SERVER['REQUEST_URI'],
            'version' => $this->getVersion(),
        ];
    }

    private function getVersion(): ?string
    {
        // Implementace verzování assets
        return null;
    }
}