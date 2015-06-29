<?php

namespace GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Picture
 */
 
/**
 * http://symfony.com/doc/current/cookbook/doctrine/file_uploads.html
 * Some code are based from examples on Symfony's website.
 * Accessed 29/05/2015
 */
 
 
class Picture
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var integer
     */
    private $userid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

	/**
     * @var integer
     */
	private $category;

    /**
     * @var \DateTime
     */
    private $timestamp;


    public function getAbsolutePath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadRootDir().'/'.$this->filename;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->filename;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return '';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Picture
     */
     
    public function generateName()
    {
		$userid = $this->getUserid();
		$filename = sha1(uniqid(mt_rand(), true));	// From Symfony's website.
		$filetype = $this->getFilename()->guessExtension();
		return $userid . $filename . "." . $filetype;
	}
    
    public function upload()
    {	
		// Check is file picture is not empty
		if (null === $this->getFilename()) {
			return false;
		}
		
		if (is_uploaded_file($this->getFilename()))
		{
			$filename = $this->generateName();
			$filelocation = $this->getUploadRootDir() . $filename;
			$this->getFilename()->move($this->getUploadRootDir(), $filelocation);
			$this->filename = $filename;
			return true;
		}
		return false;
	}
     
    public function setFilename(UploadedFile $filename = null)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
		
        return $this->filename;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Picture
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Picture
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Picture
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
     * Set category
     *
     * @param \DateTime $Category
     * @return Picture
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this->category;
    }

    /**
     * Get category
     *
     * @return \DateTime 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Picture
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
