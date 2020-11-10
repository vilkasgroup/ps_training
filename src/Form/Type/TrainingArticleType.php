<?php

namespace Invertus\Training\Form\Type;

use Invertus\Training\Form\ChoiceProvider\ProductChoiceProvider;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Length;

class TrainingArticleType extends TranslatorAwareType
{
    /**
     * @var \Invertus\Training\Form\ChoiceProvider\ProductChoiceProvider
     */
    private $productChoiceProvider;

    public function __construct(TranslatorInterface $translator, array $locales, FormChoiceProviderInterface $productChoiceProvider)
    {
        parent::__construct($translator, $locales);
        $this->productChoiceProvider = $productChoiceProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class, [
                    'label' => $this->trans('Type', 'Modules.Training.Article'),
                    'constraints' => [
                        new Length(
                            [
                                'max' => 5
                            ]
                        )
                    ]
                ]
            )->add('name', TranslatableType::class, [
                    'label' => 'Name'
                ]
            )->add('description', TranslatableType::class, [
                    'help' => 'Help',
                    'type' => TextareaType::class,
                    'label' => 'label'
                ]
            )->add('description', TranslatableType::class, [
                    'help' => 'Help',
                    'type' => TextareaType::class,
                    'label' => 'Description'
                ]
            )->add('id_product', ChoiceType::class, [
                    'label' => 'Product',
                    'choices'  => $this->productChoiceProvider->getChoices()
                ]
            );
    }
}
