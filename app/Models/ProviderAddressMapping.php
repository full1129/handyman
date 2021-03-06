<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderAddressMapping extends Model
{
    use HasFactory;
    protected $table = 'provider_address_mappings';
    protected $fillable = [ 'provider_id', 'address', 'latitude' , 'longitude' ,'status' ];

    public function providers(){
        return $this->belongsTo(User::class, 'provider_id','id');
    }

    public function getServiceAddress(){
        return $this->hasMany(ProviderServiceAddressMapping::class, 'provider_address_id','id');
    }

    public function scopeMyAddress($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->where('provider_id', $user->id);
        }
        
        return $query;
    }

    public function handyman(){
        return $this->hasMany(User::class, 'service_address_id','id');
    }
}
