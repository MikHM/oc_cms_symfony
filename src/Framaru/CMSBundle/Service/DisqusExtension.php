<?php


namespace Framaru\CMSBundle\Service;


use Framaru\CMSBundle\Entity\Page;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DisqusExtension extends \Twig_Extension
{
    private $twig;
    private $router;
    private $config;

    public function __construct(\Twig_Environment $twig, RouterInterface $router, array $config)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->config = $config;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('disqus_embed', array($this, 'renderEmbed'), array('is_safe' => array('html'))),
        );
    }

    public function renderEmbed(Page $post)
    {
        return $this->twig->render('@CMS/Disqus/_embed.html.twig', array(
            'shortname' => $this->config['shortname'],
            'identifier' => sprintf('post_%d', $post->getId()),
            'title' => $post->getTitle(),
            'url' => $this->router->generate('cms_page_display', array('id' => $post->getId()), UrlGeneratorInterface::ABSOLUTE_URL),
        ));
    }

}