<?php

/**
 * Manage jobs path
 * =====================
 * /admin/jobs
 * 
 * Add job paths
 * =================
 * /admin/jobs/modify
 * /admin/jobs/modify?action=add
 * 
 * Edit job paths
 * ==================
 * /admin/jobs/modify?id=X
 * /admin/jobs/modify?id=X&action=edit
 * 
 * Delete job path
 * ====================
 * /admin/jobs/modify?id=X&action=delete
 * 
 * Archive/Unarchive job path
 * ====================
 * /admin/jobs/modify?id=X&action=archive
 * /admin/jobs/modify?id=X&action=unarchive
 * 
 * Job applintants path
 * ====================
 * /admin/jobs/applicants?id=X
 */

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Jobs extends Page {
    protected $uriSegments;
    protected $subpage;
    protected $adminPage; // /admin/jobs/* or /jobs/*

    public function __construct(Database $db, array $uriSegment, bool $testing = false) {
        parent::__construct($db, $testing);
        $this->uriSegments = $uriSegment;
        $this->adminPage = $this->uriSegments[1] == 'admin';
        $this->subpage = $this->uriSegments[$this->adminPage ? 3 : 2] ?? '';
    }

    public function run() {
        $page = "{$this->subpage}Page";
        $jobId = $this->param('id');

        if ($jobId && $this->adminPage && !$this->isOwnerOrAdmin($jobId))
            $this->redirect('/admin/jobs', 'Job not found or you lack permission to manage it.');

        if (method_exists($this, $page))
            return $this->{$page}($jobId);

        $this->jobsPage($jobId);
    }

    public function add(array $fields) {
        $this->db->job->insert($fields);
        $this->redirect('/admin/jobs', 'Job Added');
    }

    public function edit(array $fields, $jobId) {
        $this->db->job->update($fields, ['id' => $jobId]);
        $this->redirect('/admin/jobs', 'Job Updated');
    }

    /** Action to delete, archive or unarchive a job. */
    public function action(string $action, $jobId) {
        switch ($action) {
            case 'delete':
                $this->db->job->delete(['id' => $jobId]);
                $this->redirect('/admin/jobs', 'Job Deleted');
                break;
            case 'archive':
                $this->db->job->update(['archived' => 1], ['id' => $jobId]);
                $this->redirect('/admin/jobs', 'Job Archived');
                break;
            case 'unarchive':
                $this->db->job->update(['archived' => 0], ['id' => $jobId]);
                $this->redirect('/admin/jobs', 'Job Unarchived');
                break;
            case 'edit':
                $this->redirect('/admin/jobs/modify?id=' . $jobId);
                break;
            case 'add':
                $this->redirect('/admin/jobs/modify');
                break;
            default:
                $this->redirect('/admin/jobs', 'Invalid action.');
        }
    }

    public function jobsPage($jobId) {
        $job = null;
        $category = null;
        $location = $this->param('location');
        $categoryId = $this->param('categoryId');

        // Path is /admin/jobs
        if ($this->adminPage) {
            $user = $this->userInfo();

            $binds = [];
            if ($categoryId)
                // Jobs in the category created by the current user or all if user is staff.
                $binds = $user->isAdmin ? ['categoryId' => $categoryId] : ['categoryId' => $categoryId, 'AND', 'accountId' => $user->id];
            else
                // Above but without category filter.
                $binds = $user->isAdmin ? [] : ['accountId' => $user->id];

            // Also filter by location or any locations if location is not set and search.
            $jobs = $this->db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

            return $this->renderPage('admin/jobs', 'Jobs', [
                'jobs' => $jobs,
                'jobId' => $jobId,
                'subpage' => $this->subpage
            ]);
        }

        // Path is /jobs
        if ($jobId) {
            // If jobId param is set, select the job and the category it belongs to.
            $job = $this->db->job->select(['id' => $jobId]);
            $categoryId = $job->categoryId ?? null;
            $category = $this->db->category->select(['id' => $categoryId]);
        } else if ($categoryId) {
            // Otherwise, if a categoryId param is set, select the category
            $category = $this->db->category->select(['id' => $categoryId]);
            if (!$category)
                $categoryId = null;
        }

        // Select the category if no job or category is selected
        if (!$job && !$category) {
            $category = $this->db->category->select();
            $categoryId = $category->id ?? null;
        }

        $categoryName = $category->name ?? null;
        $categoryId = $category->id ?? null;

        // Fetch all jobs in the category that are not archived and have a closing date in the future with a location that matches the search term if one is set
        $binds = ['categoryId' => $categoryId, 'AND', 'archived' => 0, 'AND', 'closingDate > NOW()'];
        $jobs = $this->db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

        $this->renderPage('jobs', 'Jobs', compact(
            'job',
            'jobs',
            'jobId',
            'location',
            'category',
            'categoryId',
            'categoryName'
        ));
    }

    /** Page to add or edit a job depending on whether an id is specified. */
    public function modifyPage($jobId) {
        $action = $this->param('action');

        if ($action)
            return $this->action($action, $jobId);

        if (!$this->param('submit'))
            return $this->renderPage('admin/jobmodify', 'Job Management', compact('jobId'));

        $fields = $this->validateJobForm();
        $this->{$jobId ? 'edit' : 'add'}($fields, $jobId);
    }

    public function applicantsPage($jobId) {
        $jobId || $this->redirect('/admin/jobs');

        $this->renderPage('admin/jobapplicants', 'Applicants', compact('jobId'));
    }

    public function applyPage($jobId) {
        $jobId || $this->redirect('/jobs', 'Job ID not specified.');
        $output = null;

        if ($this->param('submit')) {
            $required = ['name', 'email', 'jobId', 'details'];
            foreach ($required as $field)
                if (empty($this->param($field, 0)))
                    $output = 'Please fill in all required fields';

            // Error codes: https://www.php.net/manual/en/features.file-upload.errors.php#115746)
            if ($_FILES['cv']['error'] == 4)
                $output = 'CV file not uploaded';
            else if ($_FILES['cv']['error'] == 1 || $_FILES['cv']['error'] == 2)
                $output = 'CV file is too large';
            else if ($_FILES['cv']['error'] != 0)
                $output = 'There was an error uploading your CV';
            else {
                $parts = explode('.', $_FILES['cv']['name']);
                $extension = end($parts);
                // $fileName = uniqid() . '.' . $extension;
                $fileName = "job$jobId-" . uniqid() . ".$extension";

                move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

                $this->db->applicant->insert([
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'details' => $_POST['details'],
                    'jobId' => $_POST['jobId'],
                    'cv' => $fileName
                ]);

                $output = 'Your application is complete. We will contact you after the closing date.';
            }
        }

        $this->renderPage('apply', 'Apply', [
            'job' => $this->db->job->select(['id' => $jobId, 'AND', 'archived' => 0]),
            'output' => $output,
        ]);
    }

    public function leftSection($jobId) {
        $this->renderTemplate('leftsection-staff-jobs', [
            'jobId' => $jobId,
            'subpage' => $this->subpage,
        ]);
    }

    /**
     * Validates a job form and exits with an error message if invalid.
     * A job form is submited after editing an existing job or adding a new one.
     * @param array $form An array of fields to use instead of the form data.
     * @return array An array of fields with values from the form if valid.
     */
    public function validateJobForm($form = []) {
        $errors = [];
        $form || $form = $_POST;
        $fields = [
            'title' => $form['title'] ?? '',
            'description' => $form['description'] ?? '',
            'salary' => $form['salary'] ?? '',
            'location' => $form['location'] ?? '',
            'categoryId' => $form['categoryId'] ?? '',
            'closingDate' => $form['closingDate'] ?? '',
            'accountId' => ($this->testing ? $form['accountId'] : $_SESSION['loggedIn']) ?? '',
        ];

        foreach ($fields as $field => $value)
            // == is used instead of === to allow empty strings
            if ($value == null)
                $errors[] = "Field '$field' is required.";

        // Salary does not need to be a number, it can have a range eg. 20,000 - 30,000, currency symbols,
        // and other stuff the job poster might want to add, eg. a comment or information about the salary eg. "negotiable".
        // Feature to sort jobs by salary is not asked by the client, so we don't need a number anyway.

        // Verify that the closing date is in the future
        if ($fields['closingDate'] && strtotime($fields['closingDate']) < time())
            $errors[] = "Closing date must be in the future.";

        // Verify that category exists the in database
        if ($fields['categoryId'] && !$this->db->category->select(['id' => $fields['categoryId']]))
            $errors[] = "Category does not exist.";

        if ($errors)
            if ($this->testing)
                // Return errors for testing instead of exiting
                return $errors;
            else
                // Exit to the error page
                exit((new Error($this->db, $errors, 'Form Validation Error'))->run());

        return $fields;
    }
}
