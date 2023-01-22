<?php

namespace Controllers;

use \Database;
use \Page;

class Categories extends Page {
    protected array $uriSegments;
    protected $subpage;
    protected $user;

    public function __construct(Database $db, array $uriSegment) {
        parent::__construct($db);
        $this->uriSegments = $uriSegment;
        $this->subpage = $this->uriSegments[3] ?? '';
        $this->user = $this->staffOnly();

        $this->dispatchMethod();
    }

    protected function dispatchMethod() {
        $page = "{$this->subpage}Page";
        $action = $this->param('action', 0);
        $categoryId = $this->param('id', 0);
        $category = $categoryId ? $this->db->category->select(['id' => $categoryId]) : 0;

        if ($categoryId && !$category)
            $this->redirect('/admin/categories', 'Category not found.');

        if (method_exists($this, $page))
            return $this->{$page}($category);

        if ($action)
            return $this->action($action, $category);

        $this->categoriesPage($category);
    }

    public function edit($category) {
        $newName = $this->param('name');

        $this->db->category->update(['name' => $newName], ['id' => $category['id']]);
        $this->redirect('/admin/categories', 'Category updated.');
    }

    public function add() {
        $name = $this->param('name');
        $category = $this->db->category->select(['name' => $name], 'id');

        if ($category)
            exit("Category with the same name already exists. Want to
                  <a href='/admin/categories/modify?id={$category['id']}'>manage it</a>?");

        $this->db->category->insert(['name' => $name]);
        $this->redirect('/admin/categories', 'Category added.');
    }

    public function action(string $action, $category) {
        switch ($action) {
            case 'delete':
                $this->db->category->delete(['id' => $category['id']]);
                $this->redirect('/admin/categories', 'Category Deleted');
                break;
            default:
                $this->redirect('/admin/categories', 'Invalid action.');
        }
    }

    public function categoriesPage() {
        $this->renderPage('admin/categories', 'Categories', [
            'categories' => $this->db->category->selectAll()
        ]);
    }

    public function modifyPage($category) {
        if (!$this->param('submit', 0))
            return $this->renderPage('admin/categorymodify', 'Category Management', [
                'category' => $category,
                'pageType' => $category ? 'Edit' : 'Add'
            ]);

        $this->{$category ? 'edit' : 'add'}($category);
    }

    public function leftSection($category) {
        $this->renderTemplate('leftsection-staff-categories', [
            'category' => $category,
            'subpage' => $this->subpage
        ]);
    }
}