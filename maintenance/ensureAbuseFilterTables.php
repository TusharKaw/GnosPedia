#!/usr/bin/env php
<?php
/**
 * This script ensures that AbuseFilter tables exist for all wikis in the farm.
 * It should be run from the command line.
 */

if (PHP_SAPI !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get the MediaWiki installation directory
$mwDir = dirname(__DIR__);
$IP = $mwDir;

// Include necessary files
require_once "$IP/maintenance/Maintenance.php";

// Function to output messages
function output($message) {
    echo $message . "\n";
}

// Function to execute SQL file
function executeSqlFile($db, $file) {
    if (!file_exists($file)) {
        output("Error: SQL file not found: $file");
        return false;
    }
    
    $sql = file_get_contents($file);
    if ($sql === false) {
        output("Error: Could not read SQL file: $file");
        return false;
    }
    
    // Remove comments and split into statements
    $sql = preg_replace("/--.*?\n/", "\n", $sql);
    $sql = preg_replace("/\/\*.*?\*\//s", "", $sql);
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($line) { return !empty($line); }
    );
    
    // Execute each statement
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) {
            continue;
        }
        
        try {
            $db->query($statement);
            output(".");
        } catch (Exception $e) {
            output("\nError executing SQL: " . $e->getMessage());
            output("Statement: $statement");
            return false;
        }
    }
    
    output("");
    return true;
}

// Main execution
try {
    // Get all wiki databases
    $dataDir = "$mwDir/data";
    $dbFiles = glob("$dataDir/*.sqlite");
    
    if (empty($dbFiles)) {
        output("No database files found in $dataDir");
        exit(1);
    }
    
    // Process each database
    foreach ($dbFiles as $dbFile) {
        $dbName = basename($dbFile, '.sqlite');
        output("Processing database: $dbName");
        
        // Connect to the database
        $db = new SQLite3($dbFile);
        if (!$db) {
            output("  Error: Could not open database $dbName");
            continue;
        }
        
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='abuse_filter'");
        if ($result->fetchArray() !== false) {
            output("  - AbuseFilter tables already exist");
            continue;
        }
        
        // Create the tables
        output("  - Creating AbuseFilter tables...");
        $schemaFile = "$mwDir/extensions/AbuseFilter/db_patches/sqlite/tables-generated.sql";
        if (!file_exists($schemaFile)) {
            output("  - Error: Could not find schema file at $schemaFile");
            continue;
        }
        
        if (executeSqlFile($db, $schemaFile)) {
            output("  - Successfully created AbuseFilter tables");
        } else {
            output("  - Failed to create AbuseFilter tables");
        }
        $db->close();
    }
    
    output("\nDone!");
    exit(0);
    
} catch (Exception $e) {
    output("Error: " . $e->getMessage());
    exit(1);
}
