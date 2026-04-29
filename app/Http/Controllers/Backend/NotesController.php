<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notes;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $notes = Notes::Where('user_id',$user_id)->orderbydesc('id')->paginate(20);
        return view('admin.notes.list',[
            'notes' => $notes,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'note' => 'required|string|min:3'
        ],[
            'note.*'    => 'Not adı minimum 3 hərfdən ibarət olmalıdır !',
        ]);

        Notes::create([
            'user_id'   => Auth::user()->id,
            'note'      => $request->note
        ]);

        return redirect()->back()->with('success', 'Not əlavə edildi !');
    }

    public function edit($id)
    {
        $note = Notes::findOrFail($id);
        if($note->user_id != Auth::user()->id){
            return redirect()->back()->with('error', 'Bu notda düzəliş edə bilməzsiniz !');
        }
        return view('admin.notes.edit',[
            'note' => $note
        ]);
    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'note' => 'required|string|min:3',
        ],[
            'note.*'    => 'Not adı minimum 3 hərfdən ibarət olmalıdır !'
        ]);

        Notes::findOrFail($id)->update($validated);

        return redirect()->route('admin.notes.list')->with('success', 'Düzəliş olundu !');
    }

    public function delete($id)
    {
        $note = Notes::findOrFail($id);
        if($note->user_id != Auth::user()->id){
            return redirect()->back()->with('error', 'Bu notda düzəliş edə bilməzsiniz !');
        }
        $note->delete();
        return redirect()->back()->with('success', 'Not silindi !');
    }
}
