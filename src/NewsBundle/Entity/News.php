<?php
namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="NewsBundle\Repository\NewsRepository")
 */
class News
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\Column(name="header", type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 250,
     *      minMessage = "Header must be at least 10 characters long",
     *      maxMessage = "Header cannot be longer than 250 characters"
     * )
     */
    private $header;

    /**
     * @ORM\Column(name="description", type="text", length=1000, nullable=true)
     * @Assert\Length(
     *      min = 100,
     *      max = 500,
     *      minMessage = "Description must be at least 100 characters long",
     *      maxMessage = "Description cannot be longer than 500 characters"
     * )
     */
    private $description;

    /**
     * @ORM\Column(name="text", type="text", length=5000, nullable=true)
     * @Assert\Length(
     *      min = 500,
     *      max = 5000,
     *      minMessage = "Text must be at least 500 characters long",
     *      maxMessage = "Text cannot be longer than 5000 characters"
     * )
     */
    private $text;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\Image(mimeTypes={"image/jpeg", "image/png", "image/jpg"})
     */
    private $image;

    /**
     * @ORM\Column(name="date", type="date", length=100)
     */
    private $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}

