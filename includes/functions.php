<?php

function sanitize_input($data) {
    return htmlspecialchars(trim($data));

}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>