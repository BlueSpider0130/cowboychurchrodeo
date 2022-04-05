<?php

namespace App\Http\Controllers\L4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use App\RodeoEntry;
use App\CompetitionEntry;
use Carbon\Carbon;

// use App\User;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addPaymentTable( Request $request )
    {
        $amount = $request -> amount;
        $tax = $request -> tax;
        $contestant_user_id = $request-> contestant_id;
        $rodeo = $request -> rodeo;
        $payer_user_id = $request
                            ->user()
                            ->id;
        $contestant_name = $request -> contestant_name ;
        $competition_entry_id = $request -> competition_entry_id;
        // dd($contestant_name); exit();
        $payment = new Payment;

        
        $payment -> amount = $amount;
        $payment -> tax = $tax;
        $payment -> payer_user_id = $payer_user_id;
        $payment -> created_by_user_id = $payer_user_id;
        $payment -> method = "3"; 
        $payment -> save();

        $getPaymentId = $payment -> where('payer_user_id', $payer_user_id)
                                 -> get()
                                 ->max('id');
                                 
        $competitionEntry = new CompetitionEntry;
        $competitionEntry -> where('id', $competition_entry_id)->update(['paid' => "3", 'payment_id' => $getPaymentId]);

        $rodeoEntry = new RodeoEntry;
        $rodeoEntry::updateOrCreate
        (
            ['contestant_id' => $contestant_user_id, 'rodeo_id' => $rodeo],
            [
                'contestant_id' => $contestant_user_id,
                'rodeo_id' => $rodeo,
                'check_in_notes' => $contestant_name . " " . $amount . "amount" . $tax . "fee" . $amount * 1.05 . "total amount", 
                'checked_in_notes' => 'Paid Online',
                'checked_in_at' => Carbon::now() -> toDateTimeString(),
                'checked_in_by_user_id' => $payer_user_id,
                'payment_id' => $getPaymentId
             ]
        );


    }
}
