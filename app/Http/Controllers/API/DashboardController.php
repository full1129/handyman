<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Slider;
use App\Models\User;
use App\Models\Setting;
use App\Models\AppSetting;
use App\Models\AppDownload;
use App\Models\ProviderServiceAddressMapping;
use App\Http\Resources\API\BookingResource;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\CategoryResource;
use App\Http\Resources\API\SliderResource;
use App\Http\Resources\API\UserResource;

class DashboardController extends Controller
{
    public function dashboardDetail(Request $request)
    {
        $per_page = config('constant.PER_PAGE_LIMIT');

        $slider = SliderResource::collection(Slider::where('status',1)->paginate($per_page));

        $category = CategoryResource::collection(Category::where('status',1)->where('is_featured',1)->orderBy('name','asc')->paginate($per_page));
        
        $service = Service::where('status',1);
        
            if ($request->has('city_id') && !empty($request->city_id)) {
                $service->whereHas('providers', function ($a) use ($request) {
                    $a->where('city_id', $request->city_id);
                });
            }
            $service = $service->orderBy('id','desc')->paginate($per_page);

        $service = ServiceResource::collection($service);

        $provider = User::where('user_type','provider')->where('status',1);
            if ($request->has('city_id') && !empty($request->city_id)) {
                $provider = $provider->where('city_id', $request->city_id);
            }
        $provider = $provider->paginate($per_page);

        $provider = UserResource::collection($provider);

        $configurations = Setting::with('country')->get();

        $general_settings = AppSetting::first();
        $general_settings->site_logo = getSingleMedia(settingSession('get'),'site_logo',null);


        $paypal_configuration = false;        
        if ($request->has('latitude') && !empty($request->latitude) && $request->has('longitude') && !empty($request->longitude)) {
            $get_distance = getSettingKeyValue('DISTANCE','DISTANCE_RADIOUS');
            $get_unit = getSettingKeyValue('DISTANCE','DISTANCE_TYPE');
            
            $locations = Service::locationService($request->latitude,$request->longitude,$get_distance,$get_unit);
            $service_in_location = ProviderServiceAddressMapping::whereIn('provider_address_id',$locations)->get()->pluck('service_id');
            $service = Service::with('providerServiceAddress')->whereIn('id',$service_in_location)->get();
            $service = ServiceResource::collection($service);
        }
        $privacy_policy = Setting::where('type','privacy_policy')->where('key','privacy_policy')->first();
        $term_conditions = Setting::where('type','terms_condition')->where('key','terms_condition')->first();
        $response = [
           'status'         => true,
           'slider'         => $slider,
           'category'       => $category,
           'service'        => $service,
           'provider'       => $provider,
           'configurations'  => $configurations,
           'generalsetting'  => $general_settings,
           'privacy_policy' => $privacy_policy,
           'term_conditions' => $term_conditions
        ];

        return comman_custom_response($response);
    }
    public function providerDashboard(Request $request){
        $per_page = config('constant.PER_PAGE_LIMIT');
        $booking = Booking::myBooking();
        $total_booking = $booking->count();
        $service = Service::myService()->where('status',1);
        if ($request->has('city_id') && !empty($request->city_id)) {
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('city_id', $request->city_id);
            });
        }
        $service = $service->orderBy('id','desc')->paginate($per_page);
        $service = ServiceResource::collection($service);

        $category = CategoryResource::collection(Category::orderBy('name','asc')->paginate($per_page));
        
        $handyman = User::myUsers();
        $handyman = $handyman->paginate($per_page);

        $handyman = UserResource::collection($handyman);

        $total_revenue      = Payment::where('payment_status','paid');
        $providerBooking    = $booking->pluck('id');
        $total_revenue      = $total_revenue->whereIn('booking_id',$providerBooking)->sum('total_amount');

        $revenuedata        = Payment::selectRaw('sum(total_amount) as total , DATE_FORMAT(datetime , "%m") as month' )
                                    ->whereYear('datetime',date('Y'))
                                    ->where('payment_status','paid')
                                    ->groupBy('month');
        $revenuedata = $revenuedata->whereIn('booking_id',$providerBooking)->get()->toArray();
        $data['revenueData']    =    [];
        for($i = 1; $i <= 12; $i++ ){
            $revenueData = 0;
            foreach($revenuedata as $revenue){
                if((int)$revenue['month'] == $i){
                    
                    $data['revenueData'][] = [
                        $i => (int)$revenue['total']
                    ];
                    $revenueData++;
                }
            }
            if($revenueData == 0){
                $data['revenueData'][] = (object) [] ;
            }
        }

        $configurations = Setting::with('country')->get();

        $response = [
            'status'         => true,
            'total_booking'  => $total_booking,
            'service'        => $service,
            'category'       => $category,
            'handyman'       => $handyman,
            'total_revenue'  => $total_revenue,
            'monthly_revenue'=> $data,
            'configurations' => $configurations
         ];
 
         return comman_custom_response($response);

    }
}