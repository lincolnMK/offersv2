<?php

class ClientsController
{
    public function index()
    {
        // Load all clients
        require_once __DIR__ . '/../views/clients/index.php';
    }

    public function create()
    {
        // Show form
        require_once __DIR__ . '/../views/clients/create.php';
    }

    public function store()
    {
        // Handle form submission
        echo "Clients stored successfully.";
    }

    public function edit($id)
    {
        // Show edit form
        require_once __DIR__ . '/../views/clients/edit.php';
    }

    public function update($id)
    {
        // Handle update
        echo "Clients updated successfully.";
    }

    public function delete($id)
    {
        // Handle delete
        echo "Clients deleted successfully.";
    }
}
