<?php

namespace App\Models;

class CategoryModel extends BaseModel
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'code',
        'name',
        'parent',
        'slug',
        'date_added',
        'last_modified',
        'font_awesome_class',
        'thumbnail'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Get all parent categories
     */
    public function getParentCategories()
    {
        return $this->where('parent', 0)->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get sub-categories by parent ID
     */
    public function getSubCategories($parentId)
    {
        return $this->where('parent', $parentId)->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get category with sub-categories
     */
    public function getCategoryWithSubs($categoryId)
    {
        $category = $this->find($categoryId);

        if ($category) {
            $category['sub_categories'] = $this->getSubCategories($categoryId);
        }

        return $category;
    }

    /**
     * Create category
     */
    public function createCategory($data)
    {
        $data['date_added'] = time();
        $data['last_modified'] = time();
        $data['slug'] = $this->generateSlug($data['name']);

        return $this->insert($data);
    }

    /**
     * Update category
     */
    public function updateCategory($id, $data)
    {
        $data['last_modified'] = time();

        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        return $this->update($id, $data);
    }

    /**
     * Generate unique slug
     */
    private function generateSlug($name, $id = null)
    {
        $slug = url_title($name, '-', true);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists($slug, $excludeId = null)
    {
        $builder = $this->where('slug', $slug);

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Delete category and its sub-categories
     */
    public function deleteCategory($id)
    {
        // Delete sub-categories first
        $this->where('parent', $id)->delete();

        // Delete the category
        return $this->delete($id);
    }

    /**
     * Get category hierarchy (for dropdowns)
     */
    public function getCategoryHierarchy()
    {
        $categories = [];
        $parents = $this->getParentCategories();

        foreach ($parents as $parent) {
            $categories[] = $parent;
            $subs = $this->getSubCategories($parent['id']);

            foreach ($subs as $sub) {
                $sub['name'] = '-- ' . $sub['name'];
                $categories[] = $sub;
            }
        }

        return $categories;
    }
}
