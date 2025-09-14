<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
        public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(10);
        return view('admin.audit-logs.index', compact('logs'));
    }

}
