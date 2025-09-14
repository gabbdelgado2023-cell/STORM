<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Membership;
use App\Models\Event;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index()
    {
       
    
        $organizationsCount = Organization::count();
        $myMembershipsCount = Membership::where('user_id', auth()->id())->count();

        // Only approved future events for recent list
        $recentEvents = Event::where('status', 'approved')
                            ->where('date', '>', Carbon::now())
                            ->orderBy('date', 'asc')
                            ->take(5)
                            ->get();

        $eventsCount = $recentEvents->count();

        // Prepare calendar events (safe mapping in case your column is named differently)
        $calendarEvents = Event::where('status', 'approved')
            ->where('date', '>', Carbon::now())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($e) {
                // handle different possible column names gracefully
                $title = $e->title ?? $e->name ?? $e->event_name ?? 'Untitled Event';

                // ensure start is ISO datetime string
                $start = null;
                if ($e->date instanceof \Carbon\Carbon) {
                    $start = $e->date->toDateTimeString();
                } else {
                    // try parsing
                    $start = Carbon::parse($e->date)->toDateTimeString();
                }

                return [
                    'id'    => $e->id,
                    'title' => $title,
                    'start' => $start,
                ];
            })
            ->values();

        // Example notifications (replace with real)
        $notifications = session('student_notifications', []); // or however you produce notifications

        return view('student.dashboard', compact(
            'organizationsCount',
            'myMembershipsCount',
            'eventsCount',
            'recentEvents',
            'calendarEvents',
            'notifications'
        ));
    }

     public function organizations()
    {
        // Load organizations with officer and approved members count
        $organizations = Organization::with([
            'officer', // singular officer relationship
            'approvedMembers' // for counting approved members
        ])->get();

        return view('student.organizations', compact('organizations'));
    }

    public function apply($orgId)
    {
        Membership::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'organization_id' => $orgId,
            ],
            [
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Application submitted!');
    }

   // StudentDashboardController.php
    public function memberships()
    {
        $memberships = auth()->user()
            ->memberships()
            ->with('organization') // only load the organization
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'status' => $m->status,
                    'created_at' => $m->created_at ? $m->created_at->format('M d, Y') : null,
                    'organization' => [
                        'name' => $m->organization->name ?? 'N/A',
                        'description' => $m->organization->description ?? '',
                        'vision' => $m->organization->vision ?? '',
                        'mission' => $m->organization->mission ?? '',
                        // Remove projects and events if they don’t exist
                        //'projects' => [],
                        //'events' => [],
                        'members_count' => $m->organization->memberships->count() ?? 0,
                    ]
                ];
            });

        return view('student.memberships', compact('memberships'));
    }



    public function events()
    {
        $events = Event::where('status', 'approved')->get();
        return view('student.events', compact('events'));
    }

    public function withdraw($membershipId)
    {
        $membership = auth()->user()->memberships()->findOrFail($membershipId);

        // Only allow withdrawal if status is approved or pending
        if(in_array($membership->status, ['approved', 'pending'])) {
            $membership->status = 'withdrawn'; // Or delete: $membership->delete();
            $membership->save();

            return redirect()->back()->with('success', 'You have successfully withdrawn from the organization.');
        }

        return redirect()->back()->with('error', 'You cannot withdraw from this membership.');
    }

    


    
    

}
