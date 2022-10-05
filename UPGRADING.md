# Upgrading

## Component configuration

The configuration has be rewritten so that the bundle configuration of the core bundle is used to customize the default
configuration. Therefore, automatically loading all `contao_bootstrap.y(a)ml` files got removed. Each component has
to register itself in the core extension and has to describe its configuration. Its default configuration has to be
loaded using the contao manager plugin for instance also.

```php
final class ContaoBootstrapFormComponent implements \ContaoBootstrap\Core\ContaoBootstrapComponent
{
    public function addBootstrapConfiguration(ArrayNodeDefinition $builder): void
    {
        $builder->children()->arrayNode('form');
        // ...
    }
}

final class ContaoBootstrapFormBundle extends \Symfony\Component\HttpKernel\Bundle\Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $extension = $container->getExtension('contao_bootstrap');
        assert($extension instanceof ContaoBootstrapCoreExtension);
        $extension->addComponent(new ContaoBootstrapComponent());
    }
}

final class Plugin implements \Contao\ManagerPlugin\Config\ConfigPluginInterface
{
    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__ . '/../Resources/config/contao_bootstrap.yaml');
    }
}
```

# Events

The events use the FQCN as event name now. Furthermore, its signature has be changed.
