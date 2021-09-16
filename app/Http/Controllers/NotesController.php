<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateNoteFomeRequest;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

class NotesController extends Controller
{
    /**
     * マイページの表示(list)
     *
     * @param \App\Models\User $user
     * @param string $seach_keys
     * @return \Illuminate\View\View
     */
    public function list( User $user, $seach_keys=null )
    {
        $notes = Note::where('user_id',$user->id)->paginate(8);

        return view('notes.list',compact('user','notes'));
    }




    /**
     * ノート閲覧ページの表示(show)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function show(Note $note){

        return view('notes.show',compact('note'));
    }




}
