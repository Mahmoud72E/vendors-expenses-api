<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VendorController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        try {
            $vendors = Vendor::latest()->paginate(10);

            return $this->success($vendors, 'Vendors retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch vendors', 500);
        }
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'         => 'required|string|max:255',
                'contact_info' => 'nullable|string',
                'is_active'    => 'boolean',
            ]);

            $vendor = Vendor::create($validated);

            return $this->success($vendor, 'Vendor created successfully', 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->error('Failed to create vendor', 500);
        }
    }

    /**
     * Display a specific vendor.
     */
    public function show(string $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);

            return $this->success($vendor, 'Vendor retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Vendor not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch vendor', 500);
        }
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, string $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);

            $validated = $request->validate([
                'name'         => 'sometimes|required|string|max:255',
                'contact_info' => 'nullable|string',
                'is_active'    => 'boolean',
            ]);

            $vendor->update($validated);

            return $this->success($vendor, 'Vendor updated successfully');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Vendor not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to update vendor', 500);
        }
    }

    /**
     * Soft delete the specified vendor.
     */
    public function destroy(string $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();

            return $this->success(null, 'Vendor deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Vendor not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete vendor', 500);
        }
    }
}
