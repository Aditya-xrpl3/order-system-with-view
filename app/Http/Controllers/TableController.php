<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:tables,number',
        ]);
        Table::create($request->only('number'));
        return redirect()->route('tables.index')->with('success', 'Table created!');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'number' => 'required|unique:tables,number,'.$table->id,
            'is_disabled' => 'boolean',
        ]);
        $table->update($request->only('number', 'is_disabled'));
        return redirect()->route('tables.index')->with('success', 'Table updated!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted!');
    }

    // Method untuk mengubah status meja menjadi kosong
    public function setEmpty(Table $table)
    {
        $table->is_available = false;
        $table->save();

        return redirect()->back()->with('success', 'Status meja berhasil diubah menjadi kosong!');
    }
}
