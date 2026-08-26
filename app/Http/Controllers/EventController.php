<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Marcom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['marcom', 'user', 'branch', 'brand']);

        $viewType = $request->input('view', 'month');

        // Base Date
        $baseDate = \Carbon\Carbon::now();
        if ($request->has('date')) {
            $baseDate = \Carbon\Carbon::parse($request->date);
        }

        // Calendar Grid limits based on view type
        if ($viewType === 'day') {
            $startOfCalendar = $baseDate->copy()->startOfDay();
            $endOfCalendar = $baseDate->copy()->endOfDay();
        } elseif ($viewType === 'week') {
            $startOfCalendar = $baseDate->copy()->startOfWeek();
            $endOfCalendar = $baseDate->copy()->endOfWeek();
        } elseif ($viewType === 'year') {
            $startOfCalendar = $baseDate->copy()->startOfYear();
            $endOfCalendar = $baseDate->copy()->endOfYear();
        } else {
            // Default to month view
            $startOfMonth = $baseDate->copy()->startOfMonth();
            $endOfMonth = $baseDate->copy()->endOfMonth();
            $startOfCalendar = $startOfMonth->copy()->startOfWeek();
            $endOfCalendar = $endOfMonth->copy()->endOfWeek();
        }

        $query->whereBetween('start_date', [$startOfCalendar, $endOfCalendar]);

        // Filters
        if ($request->has('marcom_id') && $request->marcom_id != '') {
            $query->where('marcom_id', $request->marcom_id);
        }
        if ($request->has('branch') && $request->branch != '') {
            $query->whereHas('branch', function($q) use ($request) {
                $q->where('name', $request->branch);
            });
        }
        if ($request->has('brand') && $request->brand != '') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', $request->brand);
            });
        }

        $events = $query->orderBy('start_date', 'asc')->get();

        // Build grid days array for day, week, and month views
        $days = [];
        if ($viewType !== 'year') {
            $current = $startOfCalendar->copy();
            while ($current <= $endOfCalendar) {
                $days[] = $current->copy();
                $current->addDay();
            }
        }

        // Hierarchy Data for Nested Dropdown (Branch -> Brand -> Marcom)
        // Fetch all marcoms to build a complete hierarchy based on actual relationships
        $allMarcoms = Marcom::with(['branch', 'brand'])->get();
        
        $hierarchy = [];
        foreach ($allMarcoms as $marcom) {
            if (!$marcom->branch || !$marcom->brand) continue;
            
            $branchName = $marcom->branch->name;
            $brandName = $marcom->brand->name;
            
            if (!isset($hierarchy[$branchName])) {
                $hierarchy[$branchName] = [];
            }
            if (!isset($hierarchy[$branchName][$brandName])) {
                $hierarchy[$branchName][$brandName] = [];
            }
            
            $hierarchy[$branchName][$brandName][] = [
                'id' => $marcom->id,
                'name' => $marcom->name
            ];
        }

        $marcoms = Marcom::all();

        return view('events.index', compact('events', 'marcoms', 'days', 'baseDate', 'hierarchy', 'viewType'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'marcom_id' => 'required|exists:marcoms,id',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'branch_id' => 'required|exists:branches,id',
            'brand_id' => 'required|exists:brands,id',
            'estimation' => 'required|numeric',
            'result' => 'nullable|numeric',
        ]);

        $request->user()->events()->create($request->all());

        if ($request->user()->role === 'admin') {
            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } else {
            return redirect()->route('user.dashboard')->with('success', 'Event created successfully.');
        }
    }

    public function analytics(Request $request)
    {
        $selectedMonth = $request->query('month', Carbon::now()->format('m'));
        $selectedYear = $request->query('year', Carbon::now()->format('Y'));

        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 1. Top Summary Cards (Current Month)
        $monthEvents = Event::whereBetween('start_date', [$startOfMonth, $endOfMonth])->get();
        
        $totalEvents = $monthEvents->count();
        $totalEstimation = $monthEvents->sum('estimation');
        $totalResult = $monthEvents->sum('result');
        $achievementPercentage = $totalEstimation > 0 ? min(100, ($totalResult / $totalEstimation) * 100) : 0;

        // 2. Chart Data: Estimation vs Result per Branch (Current Month)
        $branches = \App\Models\Branch::all();
        $chartData = [
            'labels' => [],
            'estimation' => [],
            'result' => []
        ];

        foreach ($branches as $branch) {
            $branchEvents = $monthEvents->where('branch_id', $branch->id);
            $chartData['labels'][] = $branch->name;
            $chartData['estimation'][] = $branchEvents->sum('estimation');
            $chartData['result'][] = $branchEvents->sum('result');
        }

        // 3. Recent Events Table
        // Fetch latest 10 events, regardless of month, for the "Recent" view
        $recentEvents = Event::with(['branch', 'brand', 'marcom'])
            ->orderBy('start_date', 'desc')
            ->limit(10)
            ->get();

        return view('events.analytics', compact(
            'totalEvents',
            'totalEstimation',
            'totalResult',
            'achievementPercentage',
            'chartData',
            'recentEvents',
            'selectedMonth',
            'selectedYear'
        ));
    }

    public function userDashboard()
    {
        $marcoms = Marcom::all();
        $branches = \App\Models\Branch::all();
        $brands = \App\Models\Brand::all();
        return view('user.dashboard', compact('marcoms', 'branches', 'brands'));
    }

    public function regionalIndex()
    {
        $regionalEvents = Event::with(['branch', 'brand', 'marcom'])
            ->where('is_regional', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('events.regional', compact('regionalEvents'));
    }

    public function removeBulkRegional(Request $request)
    {
        $eventIds = $request->input('event_ids', []);
        
        if (!empty($eventIds)) {
            Event::whereIn('id', $eventIds)->update(['is_regional' => false]);
            return back()->with('success', count($eventIds) . ' events removed from Regional.');
        }

        return back()->with('success', 'No events selected.');
    }

    public function updateRegional(Request $request, Event $event)
    {
        $event->update([
            'is_regional' => !$event->is_regional
        ]);

        return back()->with('success', 'Regional status updated.');
    }

    public function updateResult(Request $request, Event $event)
    {
        $request->validate([
            'result' => 'required|numeric|min:0'
        ]);

        $event->update([
            'result' => $request->result
        ]);

        return back()->with('success', 'Event result updated successfully.');
    }
}
