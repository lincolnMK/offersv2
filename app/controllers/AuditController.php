
<?php
class AuditController extends Controller
{
    private string $module = 'audit';

    public function __construct()
    {
        Auth::ensure();
        ACL::ensure($this->module, 'view');
    }

    public function index()
    {
        $auditModel = new AuditModel();
        $logs = $auditModel->getRecentLogs(100); // Fetch the last 100 logs
        $this->view('audit/index', compact('logs'));
    }


}
