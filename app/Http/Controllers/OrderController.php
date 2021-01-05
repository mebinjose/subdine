<?php

namespace App\Http\Controllers;

use App\Mail\AlertLimitMail;
use App\Models\Dish;
use App\Models\OrderStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function order(Request $request){
        if($request->dish){
            if(Dish::where('dish', $request->dish)->where('available', '>', 0)->exists()){
                $dish = Dish::where('dish', $request->dish)->first();
            
                if($dish->available - 1 < config('constants.DISH_MIN_VAL')){
                    $this->sendAlertMail($request->dish);
                }
                $this->storeStats($dish);
                $dish->decrement('available');
                return response()->json(['error' => 'Order placed successfully'], 200);    
            }else{
                return response()->json(['error' => 'Dish not available'], 422);    
            }
        }else{
            return response()->json(['error' => 'Please enter dish name'], 422);
        }
    }

    public function lastTwoDayStats(Request $request){
        $date = Carbon::now()->subDays(2);
        $result = $this->calculateStats($date);
        return response()->json(['result' => $result], 200);
    }

    public function calculateStats($date){
        $data = OrderStats::where('date', '>=',  $date)->get()->groupBy('dish_id')->toArray();

        $result = [];
        $dish_ids = array_keys($data);
        if($dish_ids){
            $dishes = Dish::whereIn('id', $dish_ids)->pluck('dish', 'id');
            foreach($data as $key => $dish){
                $result[$dishes[$key]] = array_sum(array_column($dish,'count'));
            }
            return $result;
        }
        return 'No data';
    } 

    public function lastTenDayStats(Request $request){
        $date = Carbon::now()->subDays(10);
        $result = $this->calculateStats($date);
        if($result != 'No data'){
            $min = min($result);
            $max = max($result);
            $minKey = array_keys($result,$min)[0];
            $maxKey = array_keys($result,$max)[0];
            $out = ['minimum' =>[
                $minKey => $min,
            ],'maximum' => [
                $maxKey => $max
            ]];
            return response()->json(['result' => $out], 200);
        }
        return response()->json(['result' => $result], 200);
    }

    public function sendAlertMail($dish){
        Mail::to(config('constants.CONTACT_EMAIL'))->send(new AlertLimitMail($dish));
        return;
    }

    public function storeStats($dish){
        $alreadyEnterd = OrderStats::where('date', Carbon::today())->where('dish_id', $dish->id)->first();

        if($alreadyEnterd){
            $alreadyEnterd->update([
                'count' => $alreadyEnterd->count+1 
            ]);
        }
        else{
            OrderStats::create([
                'date' => Carbon::today(),
                'dish_id' => $dish->id,
                'count' => 1
            ]);
        }
        return true;
    }
}
