<?php
// so we don't need to add more files for connection details
// Make sure to copy the same database from schema.sql into digital ocean mysql
// Add your own ip and credentials here
$conn = new mysqli("YourDigitalOceanIP", "root", "YOUR_PASSWORD", "contact_manager");
?>