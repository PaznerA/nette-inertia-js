<?php declare(strict_types=1);
 
 namespace PaznerA\NetteInertia;
 
 abstract class InertiaPresenter extends Presenter
{
    /** @inject */
    public InertiaService $inertia;

    protected function startup(): void
    {
        parent::startup();
        
        if (!$this->isAjax() && !$this->getHttpRequest()->getHeader('X-Inertia')) {
            $this->template->setFile(__DIR__ . '/templates/root.latte');
        }
    }

    public function renderInertia(string $component, array $props = []): void
    {
        $response = $this->inertia->render($component, $props);
        
        if ($this->isAjax() || $this->getHttpRequest()->getHeader('X-Inertia')) {
            $this->sendJson($response);
        } else {
            $this->template->inertiaData = $response;
        }
    }
}