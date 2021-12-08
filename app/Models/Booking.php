<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Booking extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bookings';
    protected $fillable = [
        'customer_id', 'service_id', 'provider_id', 'date', 'start_at' , 'end_at' ,'amount' , 'discount','total_amount' , 'quantity', 'description' , 'coupon_id' , 'status' , 'payment_id' , 'reason' , 'address' ,'duration_diff' , 'booking_address_id'
    ];

    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }

    public function provider(){
        return $this->belongsTo(User::class,'provider_id', 'id')->withTrashed();
    }

    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id')->withTrashed();
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id', 'id');
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'id', 'booking_id')->withTrashed();
    }

    public function bookingRating(){
        return $this->hasMany(BookingRating::class, 'service_id','service_id')->with(['customer']);
    }

    public function couponAdded(){
        return $this->belongsTo(BookingCouponMapping::class,'id','booking_id');
    }

    public function handymanAdded(){
        return $this->hasMany(BookingHandymanMapping::class,'booking_id','id')->with(['handyman']);
    }
    
    public function bookingActivity(){
        return $this->hasMany(BookingActivity::class,'booking_id','id');
    }

    public function scopeMyBooking($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->where('provider_id', $user->id);
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->whereHas('handymanAdded',function ($q) use($user){
                $q->where('handyman_id',$user->id);
            });
        }

        return $query;
    }

    public function categoryService(){
        return $this->belongsTo(Service::class,'service_id', 'id')->with('category');
    }

    public function addressAdded(){
        return $this->belongsTo(BookingAddressMapping::class,'id','booking_id');
    }

    public function scopeShowServiceCount($query){
        $query->select(\DB::raw('DISTINCT service_id, COUNT(*) AS count_pid'))
              ->groupBy('service_id')
              ->orderBy('count_pid', 'desc');
        return $query->with('categoryService');
    }

    protected static function boot(){
        parent::boot();
        static::deleted(function ($row) {
            $row->couponAdded()->delete();
            $row->bookingActivity()->delete();
            $row->payment()->delete();
            $row->handymanAdded()->delete();
            $row->bookingRating()->delete();
            if($row->forceDeleting === true)
            {
                $row->couponAdded()->delete();
                $row->bookingActivity()->delete();
                $row->payment()->delete();
                $row->handymanAdded()->delete();
                $row->bookingRating()->delete();
            }
        });

        static::restoring(function($row) {
            $row->service()->withTrashed()->restore();
            $row->provider()->withTrashed()->restore();
            $row->customer()->withTrashed()->restore();
            $row->bookingActivity()->withTrashed()->restore(); 
            $row->couponAdded()->withTrashed()->restore();
            $row->payment()->withTrashed()->restore();
            $row->handymanAdded()->withTrashed()->restore();
            $row->bookingRating()->withTrashed()->restore();
        });        
    }

    public function handymanByAddress(){
        return $this->belongsTo(ProviderAddressMapping::class,'booking_address_id','id')->with(['handyman']);
    }
}
