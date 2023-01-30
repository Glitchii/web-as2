<?php

namespace Controllers;

use \Classes\Database;
use \Classes\Page;

class Home extends Page {
    public function __construct(Database $db) {
        parent::__construct($db);
    }

    public function run() {
        // Array to select unarchived and unexpired jobs.
        $binds = ['archived' => 0, 'and', 'closingDate > NOW()'];
        $categoryId = $this->param('categoryId');
        $location = $this->param('location');

        // Include category filter if set.
        if ($categoryId) {
            $binds[] = 'and';
            $binds['categoryId'] = $categoryId;
        }

        // Order by closing date in ascending to show the jobs that are closing soonest first and limit to 10.
        $binds[] = 'order by closingDate asc limit 10';

        // Also filter by location or any locations if location is not set and search.
        $jobs = $this->db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

        $this->renderPage("index", "Home", compact('jobs'));
    }
}
