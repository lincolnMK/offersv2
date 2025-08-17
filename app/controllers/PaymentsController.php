<?php

class PaymentsController
{
    public function index()
    {
        // Load all payments
        require_once __DIR__ . '/../views/payments/index.php';
    }

    public function create()
    {
        // Show form
        require_once __DIR__ . '/../views/payments/create.php';
    }

    public function store()
    {
        // Handle form submission
        echo "Payments stored successfully.";
    }

    public function edit($id)
    {
        // Show edit form
        require_once __DIR__ . '/../views/payments/edit.php';
    }

    public function update($id)
    {
        // Handle update
        echo "Payments updated successfully.";
    }

    public function delete($id)
    {
        // Handle delete
        echo "Payments deleted successfully.";
    }
}
