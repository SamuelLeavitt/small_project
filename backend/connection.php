<?php

// so we don't need to add more files for connection details
// Make sure to copy the same database from schema.sql into digital ocean mysql
//  create a new user with password and give it permissions to that database, then update the connection details below.
$conn = new mysqli("localhost", "appuser", "SomeStrongPassword1!", "contact_manager");