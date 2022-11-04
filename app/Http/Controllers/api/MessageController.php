<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function indexInbox(){
        // all inbox
        $user_id = auth()->user()->id;
        $messages = Message::where('recipient_id', '=', $user_id)->get();

        return response()->json(
            $messages, 200
        );
    }

    public function indexOutbox(){
        // all outbox
        $user_id = auth()->user()->id;
        $messages = Message::where('sender_id', '=', $user_id)->get();

        return response()->json(
            $messages, 200
        );
    }

    public function showInbox(Request $request){
        // one inbox
        $message = Message::where('id', '=', $request['id'])->first();

        return response()->json(
            $message, 200
        );
    }

    public function showOutbox(Request $request){
        // one outbox
        $message = Message::where('id', '=', $request['id'])->first();

        return response()->json(
            $message, 200
        );
    }

    // store one message in database from donee
    public function storeRequest(Request $request){

        $message['sender_id'] = auth()->user()->id;
        $message['sender_name'] = auth()->user()->name;

        $message['recipient_id'] = $request['recipient_id'];
        $message['product_id'] = $request['product_id'];
        $message['subject'] = $request['subject'];
        $message['message'] = $request['message'];

        //return $message;
        Message::create($message);

        return response()->json(
            [
                'message' => 'Üzenet elküldve!',
            ],
            200
        );
    }

    // store one message in database from donator
    public function storeResponse(Request $request){

        $message['sender_id'] = auth()->user()->id;
        $message['sender_name'] = auth()->user()->name;

        $message['recipient_id'] = $request['recipient_id'];
        $message['product_id'] = $request['product_id'];
        $message['subject'] = $request['subject'];
        $message['message'] = $request['message'];

        $message['is_accepted'] = $request['is_accepted'];

        if($message['is_accepted']){
            $product = Product::where('id', $request['product_id'])->first();
            $product->is_active = 0;
            $product->save();
        }

        //return $message;
        Message::create($message);

        return response()->json(
            [
                'message' => 'Üzenet elküldve!',
            ],
            200
        );
    }

    public function destroy(Request $request){
        // inbox or outbox

        $message = Message::where('id', $request['id'])->first();
        $message->delete();

        return response()->json(
            ['messages' => 'Üzenet törölve!']
        );
    }
}
