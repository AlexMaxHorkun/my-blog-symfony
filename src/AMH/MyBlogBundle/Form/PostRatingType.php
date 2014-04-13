<?php
namespace AMH\MyBlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
/**
Form used for sign in.

@author Alexander Horkun mindkilleralexs@gmail.com
*/
class PostRatingType extends \Symfony\Component\Form\AbstractType{
	/**
	@var array 1st value is starting point, 2nd is ending point.
	*/
	private $ratingRange=array(1,5);
	/**
	@return array 1st value is starting point, 2nd is ending point.
	*/
	public function getRatingRange(){
		return $this->ratingRange;
	}
	/**
	@param int starting point.
	@param itn ending point.
	*/
	public function setRatingRange($s,$e){
		$s=(int)$s;
		$e=(int)$e;
		if($e<$s){
			throw new \InvalidArgumentException('Ending point cannot be less then starting');
		}
		$this->ratingRange=array($s,$e);
	}
	public function buildForm(FormBuilderInterface $builder, array $options){
		$valueOptions=array();
		for($i=$this->ratingRange[0];$i<=$this->ratingRange[1];$i++){
			$valueOptions[$i]=$i;
		}
		$builder->add('rating','choice',array('choices'=>$valueOptions, 'expanded'=>TRUE, 'multiple'=>FALSE));
	}
	
	public function getName(){
		return 'post_rating';
	}
	/*
	public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
		));
	}*/
}
?>
