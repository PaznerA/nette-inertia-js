<?php declare(strict_types=1);
 
 namespace PaznerA\NetteInertia;

 use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Application\UI\Presenter;

class InertiaExtension extends CompilerExtension
{
    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'ssr' => Expect::bool(false),
            'version' => Expect::string()->nullable(),
            'framework' => Expect::anyOf('vue', 'react', 'svelte')->default('vue'),
            'rootView' => Expect::string('App/Views/Root'),
            'testing' => Expect::bool(false),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();

        // Registrace hlavní Inertia service
        $builder->addDefinition($this->prefix('inertia'))
            ->setFactory(InertiaService::class)
            ->addSetup('setFramework', [$config->framework])
            ->addSetup('setRootView', [$config->rootView])
            ->addSetup('setSSR', [$config->ssr]);

        // Registrace middleware pro zpracování Inertia requestů
        $builder->addDefinition($this->prefix('middleware'))
            ->setFactory(InertiaMiddleware::class);

        // Registrace Latte makra pro Inertia komponenty
        $builder->addDefinition($this->prefix('latteExtension'))
            ->setFactory(InertiaLatte::class);
    }

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();
        
        // Napojení na LatteFactory
        $latteFactoryService = $builder->getByType(LatteFactory::class);
        if ($latteFactoryService) {
            $definition = $builder->getDefinition($latteFactoryService);
            $definition->addSetup('addExtension', ['@' . $this->prefix('latteExtension')]);
        }
        
        // Registrace base presenteru pro Inertia
        $presenterFactory = $builder->getByType('Nette\Application\IPresenterFactory');
        if ($presenterFactory) {
            $builder->getDefinition($presenterFactory)
                ->addSetup('setMapping', [
                    ['Inertia' => 'Acme\Inertia\*Presenter']
                ]);
        }
    }
}