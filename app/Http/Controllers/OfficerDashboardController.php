<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Membership;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OfficerDashboardController extends Controller
{
    public function index()
    {
        $officer = Auth::user();
        
        // Get the organization this officer manages
        $organization = Organization::where('officer_id', $officer->id)->first();
        
        if (!$organization) {
            return redirect()->route('officer.setup')->with('error', 'Please set up your organization first.');
        }

        $totalMembers = Membership::where('organization_id', $organization->id)
                                ->where('status', 'approved')
                                ->count();
        
        $pendingRequests = Membership::where('organization_id', $organization->id)
                                   ->where('status', 'pending')
                                   ->count();
        
        $eventsCount = Event::where('organization_id', $organization->id)->count();
        
        $recentMembers = Membership::where('organization_id', $organization->id)
                                 ->with('user')
                                 ->latest()
                                 ->take(5)
                                 ->get();

        return view('officer.dashboard', compact(
            'organization',
            'totalMembers',
            'pendingRequests', 
            'eventsCount',
            'recentMembers'
        ));
    }

        public function showEvent($id)
    {
        $event = Event::findOrFail($id);

        // Optional: Make sure the officer owns this event
        if ($event->organization->officer_id !== auth()->id()) {
            abort(403);
        }

        return view('officer.events_show', compact('event'));
    }


    // Organization Profile Management
    public function editProfile()
    {
        $organization = Organization::where('officer_id', Auth::id())->first();
        
        if (!$organization) {
            return redirect()->route('officer.setup');
        }
        
        return view('officer.profile', compact('organization'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
        ]);

        $organization = Organization::where('officer_id', Auth::id())->first();
        
        $organization->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'vision' => $request->vision,
            'mission' => $request->mission,
        ]);

        return redirect()->route('officer.profile')->with('success', 'Organization profile updated successfully!');
    }

    // Member Management
    public function members()
    {
        $organization = Organization::where('officer_id', Auth::id())->first();
        
        $members = Membership::where('organization_id', $organization->id)
                            ->with('user')
                            ->get()
                            ->groupBy('status');

        return view('officer.members', compact('members', 'organization'));
    }

    public function approveMembership($membershipId)
    {
        $membership = Membership::findOrFail($membershipId);
        $organization = Organization::where('officer_id', Auth::id())->first();

        if ($membership->organization_id !== $organization->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $membership->update(['status' => 'approved']);

        return back()->with('success', 'Membership approved successfully!');
    }

    public function rejectMembership($membershipId)
    {
        $membership = Membership::findOrFail($membershipId);
        $organization = Organization::where('officer_id', Auth::id())->first();

        if ($membership->organization_id !== $organization->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $membership->update(['status' => 'rejected']);

        return back()->with('success', 'Membership rejected.');
    }

    // Event Management
    public function events()
    {
        $organization = Organization::where('officer_id', Auth::id())->first();
        
        $events = Event::where('organization_id', $organization->id)
                      ->orderBy('date', 'desc')
                      ->get();

        return view('officer.events', compact('events', 'organization'));
    }

    public function createEvent()
    {
        return view('officer.create-event');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $organization = Organization::where('officer_id', Auth::id())->first();

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'budget' => $request->budget,
            'organization_id' => $organization->id,
            'status' => 'pending', // Needs dean approval
        ]);

        return redirect()->route('officer.events')->with('success', 'Event created and submitted for approval!');
    }

    public function editEvent($eventId)
    {
        $organization = Organization::where('officer_id', Auth::id())->first();
        $event = Event::where('id', $eventId)
                     ->where('organization_id', $organization->id)
                     ->firstOrFail();

        return view('officer.edit-event', compact('event'));
    }

    public function updateEvent(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $organization = Organization::where('officer_id', Auth::id())->first();
        $event = Event::where('id', $eventId)
                     ->where('organization_id', $organization->id)
                     ->firstOrFail();

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'budget' => $request->budget,
        ]);

        return redirect()->route('officer.events')->with('success', 'Event updated successfully!');
    }

    public function deleteEvent($eventId)
    {
        $organization = Organization::where('officer_id', Auth::id())->first();
        $event = Event::where('id', $eventId)
                     ->where('organization_id', $organization->id)
                     ->firstOrFail();

        $event->delete();

        return redirect()->route('officer.events')->with('success', 'Event deleted successfully!');
    }

    // Organization Setup (for new officers)
    public function setup()
    {
        return view('officer.setup');
    }

    public function storeSetup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organizations',
            'description' => 'required|string',
            'category' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
        ]);

        Organization::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'officer_id' => Auth::id(),
        ]);

        return redirect()->route('officer.dashboard')->with('success', 'Organization created successfully!');
    }
}