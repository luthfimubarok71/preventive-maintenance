<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SegmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'super admin') {
            $segments = Segment::all();
        } else {
            $segments = Segment::where('regional_id', $user->regional_id)->get();
        }

        return view('segments.index', compact('segments'));
    }

    public function create()
    {
        return view('segments.create');
    }

    public function store(Request $request)
    {
        Segment::create([
            'nama_segment' => $request->nama_segment,
            'kode_segment' => $request->kode_segment,
            'jalur' => $request->jalur,
            'regional_id' => Auth::user()->regional_id
        ]);

        return redirect()->route('segments.index');
    }

    public function edit($id)
    {
        $segment = Segment::findOrFail($id);

        // 🔐 SECURITY
        if ($segment->regional_id != Auth::user()->regional_id 
            && Auth::user()->role != 'super admin') {
            abort(403);
        }

        return view('segments.edit', compact('segment'));
    }

    public function update(Request $request, $id)
    {
        $segment = Segment::findOrFail($id);

        // 🔐 SECURITY
        if ($segment->regional_id != Auth::user()->regional_id 
            && Auth::user()->role != 'super admin') {
            abort(403);
        }

        $segment->update([
            'nama_segment' => $request->nama_segment,
            'kode_segment' => $request->kode_segment,
            'jalur' => $request->jalur,
        ]);

        return redirect()->route('segments.index');
    }

    public function destroy($id)
    {
        $segment = Segment::findOrFail($id);

        // 🔐 SECURITY
        if ($segment->regional_id != Auth::user()->regional_id 
            && Auth::user()->role != 'super admin') {
            abort(403);
        }

        $segment->delete();

        return redirect()->route('segments.index');
    }
}