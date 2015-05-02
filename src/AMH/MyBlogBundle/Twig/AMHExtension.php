<?php
namespace AMH\MyBlogBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class AMHExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter(Router $r)
    {
        $this->router = $r;
    }

    public function getName()
    {
        return 'amh_myblog';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'pagination', array($this, 'paginationFunction'), array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * @param int Current page.
     * @param int Pages count.
     * @param int Max page links to show.
     * @param string Route name, needed to generate page links, a parameter page will be given to that route.
     * @param array|null Route params, optional.
     */
    public function paginationFunction($page, $count, $lCount, $hrefRoute, array $hrefParams = array())
    {
        $html = '<div class="pagination">';
        $startPos = $page - (int)($lCount / 2);
        if ($startPos < 1) {
            $startPos = 1;
        }
        $endPos = $startPos + $lCount - 1;
        if ($endPos > $count) {
            $endPos = $count;
        }
        if ($startPos > 1) {
            $html .= '<a class="page" href="' .
                $this->getRouter()->generate($hrefRoute, array_merge(array('page' => 1), $hrefParams))
                . '">1</a>';
            $html .= '<span class="page">...</span>';
        }
        for ($i = $startPos; $i <= $endPos; $i++) {
            $html .= '<a class="page' . (($i == $page) ? ' active-page' : '') . '" href="' .
                $this->getRouter()->generate($hrefRoute, array_merge(array('page' => $i), $hrefParams))
                . '">' . $i . '</a>';
        }
        if ($endPos < $count) {
            $html .= '<span class="page">...</span>';
            $html .= '<a class="page" href="' .
                $this->getRouter()->generate($hrefRoute, array_merge(array('page' => $count), $hrefParams))
                . '">' . $count . '</a>';
        }
        $html .= '</div>';

        return $html;
    }
}

?>
