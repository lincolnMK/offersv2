<?php

class ReportsController
{
    public function index()
    {
        // Load all reports
        require_once __DIR__ . '/../views/reports/index.php';
    }

    public function create()
    {
        // Show form
        require_once __DIR__ . '/../views/reports/create.php';
    }

    public function store()
    {
        // Handle form submission
        echo "Reports stored successfully.";
    }

    public function edit($id)
    {
        // Show edit form
        require_once __DIR__ . '/../views/reports/edit.php';
    }

    public function update($id)
    {
        // Handle update
        echo "Reports updated successfully.";
    }

    public function delete($id)
    {
        // Handle delete
        echo "Reports deleted successfully.";
    }
}
