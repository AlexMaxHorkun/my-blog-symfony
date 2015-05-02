<?php
namespace AMH\MyBlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form used for sign in.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class UserLoginType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email')
            ->add('password', 'password')
            ->add('remember_me', 'checkbox', array('data' => true, 'mapped' => false));
    }

    public function getName()
    {
        return 'user_login';
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AMH\MyBlogBundle\Entity\User\User',
                'validation_groups' => array('login'),
            )
        );
    }
}

?>
