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
        $location = $this->uriSegments[1] ?? '';
        $controllerPath = '\\Controllers\\' . ucfirst($location);

        if (!class_exists($controllerPath))
            // Load default controller if no controller exists for current location.
            $controller = new \Controllers\Home($this->db);
        else
            // Otherwise, load the controller for the current location.
            // If path is "/admin/jobs", then $location is "admin" and controller class name is most likely "Admin"
            $controller = new $controllerPath($this->db, $this->uriSegments);

        $controller->run();
        
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
    public static function sub(string $str, int $len = 20): string {
        return strlen($str) >= $len ? substr($str, 0, $len) . '...' : $str;
    }
}
