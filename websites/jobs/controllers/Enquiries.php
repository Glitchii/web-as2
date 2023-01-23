<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Enquiries extends Page {
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
        $action = $this->param('action');
        $enquiryId = $this->param('id');
        $enquiry = $enquiryId ? $this->db->enquiry->select(['id' => $enquiryId]) : 0;

        if ($enquiryId && !$enquiry)
            $this->redirect('/admin/enquiries', 'Enquiry not found.');

        if (method_exists($this, $page))
            return $this->{$page}($enquiry);

        if ($action)
            return $this->action($action, $enquiry);

        $this->enquiriesPage($enquiry);
    }

    public function edit($enquiry) {
        $type = $this->param('type', 1);
        $username = $this->param('username', 1);
        $password = $this->param('password');
        $enquiry2 = $this->db->enquiry->select(['username' => $username]);

        if ($enquiry2 && $enquiry2['id'] != $enquiry['id'])
            exit('Username already exists');

        $this->db->enquiry->update([
            'username' => $username,
            'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : $enquiry['password'],
            'isAdmin' => $type === 'staff' ? 1 : 0
        ], ['id' => $enquiry['id']]);

        $this->redirect('/admin/enquiries', 'Enquiry updated.');
    }

    public function add() {
        $type = $this->param('type', 1);
        $username = $this->param('username', 1);
        $password = $this->param('password', 1);

        if ($this->db->enquiry->select(['username' => $username]))
            exit('Username exists, try another.');

        $this->db->enquiry->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'isAdmin' => $type === 'staff' ? 1 : 0
        ]);

        $this->redirect('/admin/enquiries', 'Enquiry created.');
    }

    public function action(string $action, $enquiry) {
        switch ($action) {
            case 'delete':
                $this->db->enquiry->delete(['id' => $enquiry['id']]);
                $this->redirect('/admin/enquiries', 'Enquiry Deleted');
                break;
            case 'complete':
                $this->db->enquiry->update(['completedBy' => $this->user['id']], ['id' => $enquiry['id']]);
                $this->redirect('/admin/enquiries', 'Enquiry marked as completed.');
                break;
            case 'incomplete':
                $this->db->enquiry->update(['completedBy' => null], ['id' => $enquiry['id']]);
                $this->redirect('/admin/enquiries', 'Enquiry marked as incomplete.');
                break;
            default:
                $this->redirect('/admin/enquiries', 'Invalid action.');
        }
    }

    public function enquiriesPage($enquiry) {
        $completedFilter = $this->param('completed') !== null;
        $incompleteFilter = $this->param('incomplete') !== null;
        
        if (!$enquiry)
            $enquiries = $this->db->enquiry->selectAll();
        else {
            $enquiry = $this->db->enquiry->select(['id' => $enquiry['id']]);
            if ($enquiry['completedBy'])
                $enquiry['completedBy'] = $this->db->account->select(['id' => $enquiry['completedBy']])['username'] ?? 'Unknown';
        }

        $this->renderPage('admin/enquiries', 'Enquiries', [
            'enquiries' => $enquiries ?? [],
            'enquiry' => $enquiry ?? 0,
            'completedFilter' => $completedFilter,
            'incompleteFilter' => $incompleteFilter,
        ]);
    }

    /** Page to add or edit an enquiry depending on whether an id is specified. */
    public function modifyPage($enquiry) {
        if (!$this->param('submit'))
            return $this->renderPage('admin/enquirymodify', 'Enquiry Management', [
                'enquiry' => $enquiry,
                'pageType' => $enquiry ? 'Edit' : 'Add'
            ]);

        $this->{$enquiry ? 'edit' : 'add'}($enquiry);
    }

    public function leftSection($data) {
        $this->renderTemplate('leftsection-enquiries', $data);
    }
}
