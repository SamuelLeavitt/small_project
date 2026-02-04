# Small Project - Contact Manager

## Overview
A simple static HTML landing page for a contact manager application. The project includes a database schema for future backend development.

## Project Structure
- `index.html` - Main HTML page
- `database_scripts/schema.sql` - MySQL database schema for users and contacts tables

## Development
The project runs as a static file server on port 5000.

## Database Schema (Reference)
The SQL schema defines:
- `users` table: id, username, email, password_hash
- `contacts` table: id, first_name, last_name, phone_number, email, creation_date, user_id

## Recent Changes
- 2026-02-04: Initial setup for Replit environment
