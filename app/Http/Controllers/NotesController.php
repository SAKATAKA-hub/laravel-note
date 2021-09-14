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
     * ノート一覧ページの表示(list)
     *
     * @param \App\Models\User $user
     * @param string $seach_keys
     * @return \Illuminate\View\View
     */
    public function list()
    {
        return view('notes.list');
    }




}
