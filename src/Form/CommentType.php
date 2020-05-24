<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 2000,
                        'minMessage' => 'Длина должна быть не менее  {{ limit }} символов',
                        'maxMessage' => 'Длина должна быть не более  {{ limit }} символов'
                    ]),
                    new Regex([
                        'pattern' => '/^[1-9a-zа-яё,.!?\-\r\n ]+$/ui',
                        'message' => 'Из знаков можно использовать только [.,!?- ]'
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
