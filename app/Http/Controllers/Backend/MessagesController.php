<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        $msgs = Messages::orderByDesc('id')->paginate(20);

        return view('admin.messages.list',compact('msgs'));
    }

    public function view($id)
    {
        $message = Messages::findOrFail($id);
        $message->status = 1;
        $message->save();

        return view('admin.messages.view',compact('message'));
    }

}
