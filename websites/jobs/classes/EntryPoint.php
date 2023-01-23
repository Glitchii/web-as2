<?php

namespace Classes;

class EntryPoint {
    public Database $db;
    public array $uriSegments;

    public function __construct(Database $db = null) {
        $this->db = $db ?? new Database('student', 'student', 'job');
        $this->uriSegments = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);;
    }

    public function run() {
        // If path is "/admin/jobs", $location is "admin"
        $location = $this->uriSegments[1] ?? '';
        $controllerPath = '\\Controllers\\' . ucfirst($location);

        if (!class_exists($controllerPath))
            // Load efault controller if no controller exists for current location.
            $controller = new \Controllers\Home($this->db);
        else {
            $controller = '\\Controllers\\' . ucfirst($location);
            $controller = new $controller($this->db, $this->uriSegments);
        }

        // Cannot forget the footer. 
        include '../templates/footer.html.php';
    }

    /**
     * Truncate a string if it exceeds a certain length
     *
     * @param string $str   The string to be truncated
     * @param int    $len   The maximum length of the string, defaults to 20
     * @return string       The truncated string with "..." appended if necessary
     */
    public function sub(string $str, int $len = 20): string {
        return strlen($str) >= $len ? substr($str, 0, $len) . '...' : $str;
    }
}
