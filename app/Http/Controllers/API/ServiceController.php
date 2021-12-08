<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\BookingRating;
use App\Models\UserFavouriteService;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\ServiceDetailResource;
use App\Http\Resources\API\BookingRatingResource;
use App\Http\Resources\API\CouponResource;
use App\Http\Resources\API\UserFavouriteResource;
use App\Models\ProviderServiceAddressMapping;

class ServiceController extends Controller
{
    public function getServiceList(Request $request){

        $service = Service::with('providers','category');

        if($request->has('status') && isset($request->status)){
            $service->where('status',$request->status);
        }
        
        if($request->has('provider_id')){
            $service->where('provider_id',$request->provider_id);
        }
        
        if($request->has('category_id')){
            $service->where('category_id',$request->category_id);
        }

        if($request->has('is_featured')){
            $service->where('is_featured',$request->is_featured);
        }
        if($request->has('is_discount')){
            $service->where('discount','>',0)->orderBy('discount','desc');
        }
        if ($request->has('city_id')) {
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('city_id', $request->city_id);
            });
        }

        $service->whereHas('providers', function ($a) use ($request) {
            $a->where('status', 1);
        });
        
        if ($request->has('latitude') && !empty($request->latitude) && $request->has('longitude') && !empty($request->longitude)) {
            $get_distance = getSettingKeyValue('DISTANCE','DISTANCE_RADIOUS');
            $get_unit = getSettingKeyValue('DISTANCE','DISTANCE_TYPE');
            
            $locations = $service->locationService($request->latitude,$request->longitude,$get_distance,$get_unit);
            $service_in_location = ProviderServiceAddressMapping::whereIn('provider_address_id',$locations)->get()->pluck('service_id');
            $service->with('providerServiceAddress')->whereIn('id',$service_in_location);
        }

        if($request->has('search')){
            $service->where('name','like',"{$request->search}%");
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $service->count();
            }
        }

        $service = $service->orderBy('created_at','desc')->paginate($per_page);
        $items = ServiceResource::collection($service);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }

    public function getServiceDetail(Request $request){

        $id = $request->service_id;
        $service = Service::with('providers','category','serviceRating')->findorfail($id);

        $service_detail = new ServiceDetailResource($service);

        $rating_data = BookingRatingResource::collection($service_detail->serviceRating);
                
        $customer_reviews = [];
        if($request->customer_id != null){
            $customer_review = BookingRating::where('customer_id',$request->customer_id)->where('service_id',$id)->get();
            if (!empty($customer_review))
            {
                $customer_reviews = BookingRatingResource::collection($customer_review);
            }
        }
        
        $coupon = Coupon::with('serviceAdded')
                ->where('expire_date','>',date('Y-m-d H:i'))
                ->where('status',1)
                ->whereHas('serviceAdded',function($coupon) use($id){
                    $coupon->where('service_id', $id );
                })->get();
        $coupon_data = CouponResource::collection($coupon);
        $response = [
            'service_detail'    => $service_detail,
            'provider'          => new UserResource(optional($service->providers)),
            'rating_data'       => $rating_data,
            'customer_review'   => $customer_reviews,
            'coupon_data'       => $coupon_data,
        ];

        return comman_custom_response($response);
    }

    public function getServiceRating(Request $request){

        $rating_data = BookingRating::where('service_id',$request->service_id);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $rating_data->count();
            }
        }

        $rating_data = $rating_data->orderBy('id','desc')->paginate($per_page);
        $items = BookingRatingResource::collection($rating_data);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }

    public function saveFavouriteService(Request $request)
    {
        $user_favourite = $request->all();

        $result = UserFavouriteService::updateOrCreate(['id' => $request->id], $user_favourite);

        $message = __('messages.update_form',[ 'form' => __('messages.favourite') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.favourite') ] );
		}

        return comman_message_response($message);
    }

    public function deleteFavouriteService(Request $request)
    {
        
        $service_rating = UserFavouriteService::where('user_id',$request->user_id)->where('service_id',$request->service_id)->delete();
        
        $message = __('messages.delete_form',[ 'form' => __('messages.favourite') ] );

        return comman_message_response($message);
    }

    public function getUserFavouriteService(Request $request)
    {
        $user = auth()->user();

        $favourite = UserFavouriteService::where('user_id',$user->id);

        $per_page = config('constant.PER_PAGE_LIMIT');

        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $favourite->count();
            }
        }

        $favourite = $favourite->orderBy('created_at','desc')->paginate($per_page);

        $items = UserFavouriteResource::collection($favourite);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
    
        return comman_custom_response($response);
    }
    public function getTopRatedService(){
        $rating_data = BookingRating::orderBy('rating','desc')->limit(5)->get();
        $items = BookingRatingResource::collection($rating_data);

        $response = [
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
}