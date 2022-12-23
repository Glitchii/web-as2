<?php
session_start();
// Destroys all data registered to a session. Better than unset($_SESSION['loggedIn']).
session_destroy();

header('Location: /');
?>