<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\Pagination;

class BranchController extends Controller
{
    use Pagination;

    /**
     * Display a listing of branches.
     */
    public function index(Request $request)
    {
        try {
            $pagination = $this->getPaginationParams($request);
            
            $branches = Branch::latest()
                ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

            return response()->json([
                'status' => 'success',
                'data' => BranchResource::collection($branches),
                'meta' => [
                    'total' => $branches->total(),
                    'per_page' => $branches->perPage(),
                    'current_page' => $branches->currentPage(),
                    'last_page' => $branches->lastPage(),
                    'from' => $branches->firstItem(),
                    'to' => $branches->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch branches: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch branches'
            ], 500);
        }
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if branch with same name already exists
            if (Branch::where('name', $request->name)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Branch with this name already exists'
                ], 409);
            }

            $branch = Branch::create($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Branch created successfully',
                'data' => new BranchResource($branch)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create branch: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create branch'
            ], 500);
        }
    }

    /**
     * Display the specified branch.
     */
    public function show($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new BranchResource($branch)
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch branch: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch branch details'
            ], 500);
        }
    }

    /**
     * Update the specified branch.
     */
    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'address' => 'sometimes|required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if branch with same name exists (excluding current branch)
            if ($request->has('name') && Branch::where('name', $request->name)
                ->where('id', '!=', $id)
                ->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Branch with this name already exists'
                ], 409);
            }

            $branch->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Branch updated successfully',
                'data' => new BranchResource($branch)
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update branch: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update branch'
            ], 500);
        }
    }

    /**
     * Remove the specified branch.
     */
    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            
            $branch->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Branch deleted successfully'
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete branch: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete branch'
            ], 500);
        }
    }
}