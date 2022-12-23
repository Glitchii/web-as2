<?php
session_start();
session_destroy(); // Destroys all data registered to a session. Better than unset($_SESSION['loggedIn']).

header('Location: /');
?>