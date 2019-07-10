<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactsController extends Controller
{
    public function get()
    {
        $contacts = User::where('id', '!=', auth()->id())->get();

        $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('read', false)
            ->groupBy('from')
            ->get();

        $contacts = $contacts->map(function($contact) use($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();

            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $contact;
        });

        return response()->json($contacts);
    }

    public function getMessagesFor($id)
    {
        Message::where('from', $id)
            ->where('to', auth()->id())
            ->update(['read' => true]);

        $messages = Message::where(function($q) use($id) {
            $q->where('from', auth()->id())
            ->where('to', $id);
        })->orWhere(function($q) use($id) {
            $q->where('from', $id)
            ->where('to', auth()->id());
        })
        ->latest('created_at')
        ->paginate(5);

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $user = auth()->user();
        $message = Message::create([
            'from' => $user->id,
            'to' => $request->contact_id,
            'body' => $request->body
        ]);

        broadcast(new NewMessage($message));

        return response()->json($message);
    }
}
