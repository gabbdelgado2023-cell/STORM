<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use PDF; // for PDF generation
use Maatwebsite\Excel\Facades\Excel; // for Excel (if needed)
use App\Exports\CustomReportExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $type = $request->type;
        $format = $request->format ?? 'web';
        $sections = $request->sections ?? [];

        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        // Build query based on type
        $data = [];
        switch ($type) {
            case 'users':
                $query = User::query();
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data['users'] = $query->get();
                break;

            case 'organizations':
                $query = Organization::query();
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data['organizations'] = $query->get();
                break;

            case 'activities':
                $query = Event::query();
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data['events'] = $query->get();
                break;

            default:
                $data['overview'] = [
                    'total_users' => User::count(),
                    'total_orgs' => Organization::count(),
                    'total_events' => Event::count(),
                ];
                break;
        }

        // Return in requested format
        if ($format === 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf', compact('data', 'type', 'sections'));
            return $pdf->download(strtolower($type) . '_report.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new CustomReportExport($data, $type, $sections), strtolower($type) . '_report.xlsx');
        }

        return view('admin.reports.view', compact('data', 'type', 'sections'));
    }
}
