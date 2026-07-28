<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->paginate(10);

        return view('super-admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('super-admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'medicine_code' => 'required|string|max:100|unique:medicines,medicine_code',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        Medicine::create($request->all());

        return redirect()
            ->route('super-admin.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    public function show(Medicine $medicine)
    {
        return view('super-admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        return view('super-admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'medicine_code' => 'required|string|max:100|unique:medicines,medicine_code,' . $medicine->id,
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($request->all());

        return redirect()
            ->route('super-admin.medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()
            ->route('super-admin.medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }
}