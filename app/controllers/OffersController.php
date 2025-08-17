<?php

class OffersController
{
    public function index()
    {
        // Load all offers
        require_once __DIR__ . '/../views/offers/index.php';
    }

    public function create()
    {
        // Show form
        require_once __DIR__ . '/../views/offers/create.php';
    }

    public function store()
    {
        // Handle form submission
        echo "Offers stored successfully.";
    }

    public function edit($id)
    {
        // Show edit form
        require_once __DIR__ . '/../views/offers/edit.php';
    }

    public function update($id)
    {
        // Handle update
        echo "Offers updated successfully.";
    }

    public function delete($id)
    {
        // Handle delete
        echo "Offers deleted successfully.";
    }
}
