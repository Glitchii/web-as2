<?php

namespace Controllers;

use \Database;
use \Page;

class Contact extends Page{
    public $db;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->dispatchMethod();
    }
    
    protected function dispatchMethod() {
        $this->renderPage("contact");
    }
    
    public function handleSubmit() {
        if (!$this->param('submit', 0))
            return 'For any enquiries, please contact us using the form below.';
    
        $name = $this->param('name', 0);
        $email = $this->param('email', 0);
        $telephone = $this->param('telephone', 0);
        $enquiry = $this->param('enquiry', 0);
    
        if (!($name && $email && $telephone && $enquiry))
            return 'Please fill in all fields.';
    
        // Using regular expressions to check if number is valid.
        // Personal numbers should start with a 0 or a + (followed by a country code) then 9 numbers after 7.
        if (!preg_match('/^(\+\d|0)7\d{9}$/', $telephone))
            // I validate through PHP instead of using HTML input's pattern attribute because while it would stop the user from
            // submitting if the pattern does not match, it would not tell the user why it is not valid or what the correct format is.
            return 'Phone number should start with 0 or country code (eg. +44) followed by 10 numbers.';
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            // ref https://www.php.net/manual/en/filter.examples.validation.php
            return 'Please enter a valid email address.';
    
        $this->db->enquiry->insert([
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'enquiry' => $enquiry,
        ]);
    
        return 'Enquiry sent. Got something else to say? Fill in the form below.';
    }
}
