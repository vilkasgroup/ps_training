<?php

namespace Invertus\Training\Language;

use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Core\Language\LanguageActivatorInterface;

/**
 * Decorates core LanguageActivator with Shop motto resetting when any language is being disabled.
 * Decorator is way to override functionality of another class in symfony. Use it in place of overrides.
 * See definition in services.yml to how it knows which class to decorate.
 */
final class DecoratingLanguageActivator implements LanguageActivatorInterface
{
    /**
     * @var LanguageActivatorInterface
     */
    private $activator;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(LanguageActivatorInterface $activator, ConfigurationInterface $configuration)
    {
        $this->activator = $activator;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function enable($langId)
    {
        $this->activator->enable($langId);
        $this->configuration->set('PS_TRAINING_SHOP_MOTTO', '');

    }

    /**
     * {@inheritdoc}
     */
    public function disable($langId)
    {
        $this->activator->disable($langId);
        $this->configuration->set('PS_TRAINING_SHOP_MOTTO', '');
    }
}
