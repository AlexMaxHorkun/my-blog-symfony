<?php
namespace AMH\MyBlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form used for registration.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class UserRegistrationType extends UserLoginType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder->add('email','email')
            ->add('password','password')
            ->add('remember_me','checkbox',array('data'=>TRUE, 'mapped'=>FALSE));*/
        parent::buildForm($builder, $options);
        $builder->remove('remember_me')->remove('password')
            ->add(
                'password',
                'repeated',
                array(
                    'first_name' => 'password',
                    'second_name' => 'confirm_password',
                    'type' => 'password'
                )
            )
            ->add('name', 'text');
    }

    public function getName()
    {
        return 'user_registration';
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'validation_groups' => array('login', 'registration'),
            )
        );
    }
}

?>
