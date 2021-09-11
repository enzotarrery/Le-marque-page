<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Nom d\'utilisateur',
                'attr' => array('placeholder' => 'Votre pseudo...'),
                'constraints' => array(new NotBlank(array("message" => "Merci de renseigner un pseudo."))))
            )
            ->add('password', RepeatedType::class, array(
                'type'=> PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => array('constraints' => array(new NotBlank(array("message" => "Merci de sécuriser votre compte.")))),
                'required' => true,
                'first_options' => array('label' => 'Mot de passe', 'attr' => array('placeholder' => 'Votre mot de passe...')),
                'second_options' => array('label' => 'Confirmation', 'attr' => array('placeholder' => 'Confirmation...'))
                ))
            ->add('nom', TextType::class, array('label' => 'Nom', 'attr' => array('placeholder' => 'Votre nom...')))
            ->add('prenom', TextType::class, array('label' => 'Prénom', 'attr' => array('placeholder' => 'Votre prénom...')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
