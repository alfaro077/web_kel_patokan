<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('files')->withCount('files')->latest()->paginate(10);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'file_months' => 'nullable|array',
            'file_months.*' => 'required_with:pdf_documents.*|integer|between:1,12',
            'file_years' => 'nullable|array',
            'file_years.*' => 'required_with:pdf_documents.*|integer|min:2000',
            'pdf_documents' => 'nullable|array',
            'pdf_documents.*' => 'required_with:file_months.*|mimes:pdf|max:' . (\App\Http\Controllers\Admin\SettingController::getSettings()['max_upload_pdf_mb'] * 1024),
        ]);

        $data = $request->except(['pdf_documents', 'file_months', 'file_years']);
        $data['is_active'] = $request->has('is_active');

        $document = Document::create($data);

        // Auto sync to NavigationMenu
        NavigationMenu::create([
            'section' => 'dokumen',
            'title' => $document->name,
            'url' => '/dokumen?id=' . $document->id,
            'order' => NavigationMenu::where('section', 'dokumen')->max('order') + 1,
            'is_active' => true,
        ]);

        // Handle File Uploads
        if ($request->has('file_months') && $request->has('file_years') && $request->hasFile('pdf_documents')) {
            $months = $request->input('file_months');
            $years = $request->input('file_years');
            $files = $request->file('pdf_documents');
            
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            foreach ($files as $index => $file) {
                if (isset($months[$index]) && isset($years[$index])) {
                    $path = $file->store('docs', 'public');
                    $name = $document->name . ' - ' . $monthNames[(int)$months[$index]] . ' ' . $years[$index];
                    DocumentFile::create([
                        'document_id' => $document->id,
                        'name' => $name,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show(Document $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $document->load('files');
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file_months' => 'nullable|array',
            'file_months.*' => 'required_with:pdf_documents.*|integer|between:1,12',
            'file_years' => 'nullable|array',
            'file_years.*' => 'required_with:pdf_documents.*|integer|min:2000',
            'pdf_documents' => 'nullable|array',
            'pdf_documents.*' => 'required_with:file_months.*|mimes:pdf|max:' . (\App\Http\Controllers\Admin\SettingController::getSettings()['max_upload_pdf_mb'] * 1024),
        ]);

        $data = $request->except(['pdf_documents', 'file_months', 'file_years']);
        $data['is_active'] = $request->has('is_active');
        
        $oldName = $document->name;
        $document->update($data);

        // Update Navigation Menu title
        if ($oldName !== $document->name) {
            $navMenu = NavigationMenu::where('section', 'dokumen')
                ->where('url', '/dokumen?id=' . $document->id)
                ->first();
            if ($navMenu) {
                $navMenu->update(['title' => $document->name]);
            }
        }

        // Add New File Uploads
        if ($request->has('file_months') && $request->has('file_years') && $request->hasFile('pdf_documents')) {
            $months = $request->input('file_months');
            $years = $request->input('file_years');
            $files = $request->file('pdf_documents');
            
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            foreach ($files as $index => $file) {
                if (isset($months[$index]) && isset($years[$index])) {
                    $path = $file->store('docs', 'public');
                    $name = $document->name . ' - ' . $monthNames[(int)$months[$index]] . ' ' . $years[$index];
                    DocumentFile::create([
                        'document_id' => $document->id,
                        'name' => $name,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $id = $document->id;
        $document->delete(); // Boot method deletes physical files
        
        NavigationMenu::where('section', 'dokumen')
                ->where('url', '/dokumen?id=' . $id)
                ->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function destroyFile($id)
    {
        $file = DocumentFile::findOrFail($id);
        if ($file->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($file->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();

        return response()->json(['success' => true, 'message' => 'File berhasil dihapus']);
    }
}
