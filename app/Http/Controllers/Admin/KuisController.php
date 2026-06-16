<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class KuisController extends Controller
{
    public function index()
    {
        $kuisList = Kuis::orderBy('waktu_mulai', 'desc')->get();
        return view('admin.kuis.index', compact('kuisList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:50',
            'kategori' => 'required|in:literasi,numerasi',
            'tema' => 'required|string|max:255',
            'soal' => 'required|string',
            'waktu_mulai' => 'required|date',
            'durasi_menit' => 'required|integer|min:1',
            'file_pdf' => 'required|file|mimes:pdf|max:51200', // max 50 MB
        ]);

        $file = $request->file('file_pdf');
        $filename = 'kuis_' . time() . '.pdf';
        $path = $file->storeAs('kuis-pdf', $filename, 'public');

        $jumlahHalaman = $this->convertPdfToImages($path, $filename);

        Kuis::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tema' => $request->tema,
            'soal' => $request->soal,
            'file_pdf' => $path,
            'jumlah_halaman_pdf' => $jumlahHalaman,
            'waktu_mulai' => $request->waktu_mulai,
            'durasi_menit' => $request->durasi_menit,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.kuis')->with('success', 'Kuis berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kuis = Kuis::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:50',
            'kategori' => 'required|in:literasi,numerasi',
            'tema' => 'required|string|max:255',
            'soal' => 'required|string',
            'waktu_mulai' => 'required|date',
            'durasi_menit' => 'required|integer|min:1',
            'file_pdf' => 'nullable|file|mimes:pdf|max:51200', // max 50 MB
        ]);

        $data = $request->only(['judul', 'kategori', 'tema', 'soal', 'waktu_mulai', 'durasi_menit']);

        if ($request->hasFile('file_pdf')) {
            // hapus file & gambar lama
            Storage::disk('public')->delete($kuis->file_pdf);
            $this->deleteImages($kuis);

            $file = $request->file('file_pdf');
            $filename = 'kuis_' . time() . '.pdf';
            $path = $file->storeAs('kuis-pdf', $filename, 'public');

            $data['file_pdf'] = $path;
            $data['jumlah_halaman_pdf'] = $this->convertPdfToImages($path, $filename);
        }

        $kuis->update($data);

        return redirect()->route('admin.kuis')->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kuis = Kuis::findOrFail($id);
        Storage::disk('public')->delete($kuis->file_pdf);
        $this->deleteImages($kuis);
        $kuis->delete();

        return redirect()->route('admin.kuis')->with('success', 'Kuis berhasil dihapus.');
    }

    public function getData($id)
    {
        $kuis = Kuis::findOrFail($id);
        return response()->json([
            'id' => $kuis->id,
            'judul' => $kuis->judul,
            'kategori' => $kuis->kategori,
            'tema' => $kuis->tema,
            'soal' => $kuis->soal,
            'waktu_mulai' => $kuis->waktu_mulai->format('Y-m-d\TH:i'),
            'durasi_menit' => $kuis->durasi_menit,
        ]);
    }

    private function convertPdfToImages(string $pdfPath, string $filename): int
    {
        $fullPath = storage_path('app/public/' . $pdfPath);
        $outputDir = storage_path('app/public/kuis-pages/' . pathinfo($filename, PATHINFO_FILENAME));

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $pdf = new Pdf($fullPath);
        $pageCount = $pdf->pageCount();

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->selectPage($i)->save($outputDir . '/page-' . $i . '.jpg');
        }

        return $pageCount;
    }

    private function deleteImages(Kuis $kuis): void
    {
        $dir = storage_path('app/public/kuis-pages/' . pathinfo($kuis->file_pdf, PATHINFO_FILENAME));
        if (is_dir($dir)) {
            array_map('unlink', glob("$dir/*.*"));
            rmdir($dir);
        }
    }
}