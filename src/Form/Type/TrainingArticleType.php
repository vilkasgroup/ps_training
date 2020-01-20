<?php

namespace Invertus\Training\Form\Type;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class TrainingArticleType extends AbstractType
{
    /**
     * Type will be passed to form builder so here we define what fields we will see in form
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class)
            ->add('name', TranslatableType::class, [
                'type' => TextType::class,
                'constraints' => [
                    new DefaultLanguage(),
                ],
                ]
               )
            ->add('description', TranslatableType::class, [
                    'type' => TextareaType::class,
                    'constraints' => [
                        new DefaultLanguage(),
                    ],
                ]
            )


//            ->add('training', ChoiceType::class, [
//                'choices' => [
//                    'Prestashop training using Symfony' => 1,
//                    'PrestaShop integrator training' => 2,
//                    'PrestaShop backend training' => 3,
//                ],
//                'expanded' => false,
//                'multiple' => false
//            ])
//            ->add('number_of_attendees', IntegerType::class)
//            ->add('date', DateType::class, [
//                'widget' => 'single_text',
//            ])
//            ->add('notes', TextType::class)
        ;
    }

}
