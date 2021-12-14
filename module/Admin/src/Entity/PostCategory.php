<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *  Категорії в блозі.
 * @ORM\Entity(repositoryClass="\Admin\Repository\PostCategoryRepository")
 * @ORM\Table(name="post_category")
 */
class PostCategory
{

    const CATEGORY_PUBLISHED   = 1; // Опублікована
    const CATEGORY_DRAFT       = 2; // Чорновик

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="category_id")
     */
    protected $category_id;

    /**
     * @ORM\Column(name="category_name")
     */
    protected $category_name;
    /**
     * @ORM\Column(name="category_description")
     */
    protected $category_description;
    /**
     * @ORM\Column(name="category_parent_id")
     */
    protected $category_parent_id;
    /**
     * @ORM\Column(name="category_active")
     */
    protected $category_active;
    /**
     * @ORM\Column(name="category_date_created")
     */
    protected $category_date_created;
    /**
     * @ORM\Column(name="category_date_deleted")
     */
    protected $category_date_deleted;

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $category_name
     */
    public function setCategoryName($category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * @return mixed
     */
    public function getCategoryDescription()
    {
        return $this->category_description;
    }

    /**
     * @param mixed $category_description
     */
    public function setCategoryDescription($category_description): void
    {
        $this->category_description = $category_description;
    }

    /**
     * @return mixed
     */
    public function getCategoryParentId()
    {
        return $this->category_parent_id;
    }

    /**
     * @param mixed $category_parent_id
     */
    public function setCategoryParentId($category_parent_id): void
    {
        $this->category_parent_id = $category_parent_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryActive()
    {
        return $this->category_active;
    }

    /**
     * @param mixed $category_active
     */
    public function setCategoryActive($category_active): void
    {
        $this->category_active = $category_active;
    }

    /**
     * @return mixed
     */
    public function getCategoryDateCreated()
    {
        return $this->category_date_created;
    }

    /**
     * @param mixed $category_date_created
     */
    public function setCategoryDateCreated($category_date_created): void
    {
        $this->category_date_created = $category_date_created;
    }

    /**
     * @return mixed
     */
    public function getCategoryDateDeleted()
    {
        return $this->category_date_deleted;
    }

    /**
     * @param mixed $category_date_deleted
     */
    public function setCategoryDateDeleted($category_date_deleted): void
    {
        $this->category_date_deleted = $category_date_deleted;
    }


}