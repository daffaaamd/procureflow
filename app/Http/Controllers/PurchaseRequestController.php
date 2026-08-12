<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Product;
use App\Services\PurchaseRequestService;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    protected $prService;

    public function __construct(PurchaseRequestService $prService)
    {
        $this->prService = $prService;
    }

    public function index(Request $request)
    {
        $query = PurchaseRequest::with('requester', 'department')->latest();

        // If simple employee, only see their own PRs
        if (Auth::user()->role === 'Employee' || Auth::user()->role === 'Requester') {
            $query->where('requester_id', Auth::id());
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('pr_number', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $prs = $query->paginate(15);
        return view('pr.index', compact('prs'));
    }

    public function create()
    {
        $products = Product::where('status', 'Active')->get();
        return view('pr.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'required_date' => 'required|date',
            'priority' => 'required|string',
            'purpose' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimated_price' => 'required|numeric|min:0',
        ]);

        $pr = $this->prService->create($request->all(), $request->items);

        return redirect()->route('pr.show', $pr)
            ->with('success', 'Purchase Request has been created successfully.');
    }

    public function show(PurchaseRequest $pr)
    {
        // Simple authorization check could be added here
        $pr->load('items.product', 'approvals.approver', 'requester', 'department');
        return view('pr.show', compact('pr'));
    }

    // Manager Approvals View
    public function approvals()
    {
        $prs = PurchaseRequest::with('requester', 'department')
                ->where('status', 'Submitted')
                ->latest()
                ->paginate(15);
        
        return view('pr.approvals', compact('prs'));
    }

    public function approve(Request $request, PurchaseRequest $pr)
    {
        $this->prService->approve($pr, $request->comments);
        return back()->with('success', "Purchase Request {$pr->pr_number} approved successfully.");
    }

    public function reject(Request $request, PurchaseRequest $pr)
    {
        $request->validate(['comments' => 'required|string']);
        $this->prService->reject($pr, $request->comments);
        return back()->with('success', "Purchase Request {$pr->pr_number} rejected.");
    }
}
