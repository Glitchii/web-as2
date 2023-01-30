<?php

namespace Tests;

require __DIR__ . '/../controllers/Jobs.php';

use PHPUnit\Framework\TestCase;
use Classes\Database;
use Controllers\Jobs;

class JobsTest extends TestCase {
    protected $db;
    protected $jobsController;
    protected $uriSegments;

    public function setUp(): void {
        $REQUEST_URI = '/admin/jobs';

        $this->db = new Database('student', 'student', 'job');
        $this->uriSegments = explode('/', explode('?', $REQUEST_URI)[0]);
        $this->jobsController = new Jobs($this->db, $this->uriSegments, true);
    }

    public function testJobsFormValidation() {
        $validForm = [
            'title' => 'test',
            'description' => 'test',
            'salary' => 'test',
            'location' => 'test',
            'categoryId' => '1',
            'closingDate' => '2025-05-05',
            'accountId' => '1',
        ];

        foreach ($validForm as $field => $value) {
            // Iterate through each field in the $validForm array and create an invalid form by setting the current field to an empty string.
            $invalidForm = array_merge($validForm, [$field => '']);
            // Then, assert that the returned errors array contains the message "Field '$field' is required." for that specific field.
            $this->assertContains("Field '$field' is required.", $this->jobsController->validateJobForm($invalidForm));
        }
        
        $pastClosingDate = array_merge($validForm, ['closingDate' => '2021-01-01']);
        $this->assertContains('Closing date must be in the future.', $this->jobsController->validateJobForm($pastClosingDate));
        
        $nonexistentCategory = array_merge($validForm, ['categoryId' => 65768673]);
        $this->assertContains('Category does not exist.', $this->jobsController->validateJobForm($nonexistentCategory));
        
        // validateJobForm() returns an array of errors if the form is invalid.
        // If the form is valid, it returns the $validForm array (instead of an empty array) to be used by the calling method.
        // Below we assert that the returned array is the same as the $validForm array.
        $this->assertEquals($validForm, $this->jobsController->validateJobForm($validForm));
    }
}
