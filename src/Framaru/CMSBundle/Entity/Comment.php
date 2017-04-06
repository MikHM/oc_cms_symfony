<?php

namespace Framaru\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Framaru\CMSBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Framaru\CMSBundle\Entity\Page", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     *
     * @Assert\NotBlank(message="Veuillez ajouter un pseudo.")
     * @Assert\Length(min="2")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank(message="Le message ne peut être vide.")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="flag", type="boolean", nullable=false, options={"default": 0})
     */
    private $flag = false;

    /**
     * @ORM\ManyToOne(targetEntity="Framaru\CMSBundle\Entity\Comment", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Framaru\CMSBundle\Entity\Comment", mappedBy="parent")
     */
    private $children;


    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->children = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set page
     *
     * @param \Framaru\CMSBundle\Entity\Page $page
     *
     * @return Comment
     */
    public function setPage(\Framaru\CMSBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Framaru\CMSBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set flag
     *
     * @param boolean $flag
     *
     * @return Comment
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return boolean
     */
    public function getFlag()
    {
        return $this->flag;
    }


    /**
     * Set parent
     *
     * @param \Framaru\CMSBundle\Entity\Comment $parent
     *
     * @return Comment
     */
    public function setParent(\Framaru\CMSBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;
        $this->page = $parent->getPage();

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Framaru\CMSBundle\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Framaru\CMSBundle\Entity\Comment $child
     *
     * @return Comment
     */
    public function addChild(\Framaru\CMSBundle\Entity\Comment $child)
    {
        $this->children[] = $child;
        
        return $this;
    }

    /**
     * Remove child
     *
     * @param \Framaru\CMSBundle\Entity\Comment $child
     */
    public function removeChild(\Framaru\CMSBundle\Entity\Comment $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getchildren()
    {
        return $this->children;
    }
}
