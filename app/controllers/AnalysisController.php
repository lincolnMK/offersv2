<?php

class AnalysisController  extends Controller {
  private string $module = 'analysis';
  public function __construct(){ Auth::ensure(); }
  public function index(){ ACL::ensure($this->module,'view');
    $m = new Analysis(); $analysis = $m->all();
    $this->view('analysis/index', compact('analysis'));
  }

    public function create()
    {
        // Show form
        require_once __DIR__ . '/../views/analysis/create.php';
    }

    public function store()
    {
        // Handle form submission
        echo "Analysis stored successfully.";
    }

    public function edit($id)
    {
        // Show edit form
        require_once __DIR__ . '/../views/analysis/edit.php';
    }

    public function update($id)
    {
        // Handle update
        echo "Analysis updated successfully.";
    }

    public function delete($id)
    {
        // Handle delete
        echo "Analysis deleted successfully.";
    }
}
