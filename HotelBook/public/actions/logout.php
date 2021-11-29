<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;

// Remove user's token to logout
setcookie('user_token', null, time() + 0, '/' );
header(sprintf('Location: ../index.php'));

?>