<template>
<div>
  <breadcrumb-component  :sectionName="'Booking'" :homeName="this.$route.meta.label" />
  <div class="container mar-top mar-bot">
    <div class="row" v-if="bookingList.length > 0">
      <div class="col-lg-8">
        <div class="booking-wrapper" v-for="(data, index) in bookingList" :key="index">
            <router-link
                :to="{
                    name: 'bookingdetail',
                    params: { booking_id: data.id },
                }"
            >
            <div class="row">
                <div class="col-md-4">
                    <div class="book-img-box">
                        <img :src="data.service_attchments[0] ? data.service_attchments[0] : baseUrl+'/images/default.png'" alt="image" class="img-fluid"> 
                        <span class="badge badge-1 rounded-pill bg-primary price-box">{{ data.price_formate }}</span>
                    </div>
                </div>
                <div class="col-md-8 mt-md-0 mt-4">
                    <div class="booking-content">
                       <h5 class="b-title mb-3">{{ data.service_name }}  </h5>
                        <p class="mb-2"><span class="hightlight-text">{{__('messages.address')}}:</span> <span>{{ data.address ? data.address : '-'  }}</span></p>
                        <div class="row">
                            <div class="col-lg-6">                                       
                                <p class="mb-2"><span class="hightlight-text">{{__('messages.status')}}</span><span>{{ data.status }}</span></p>  
                                <p class="mb-2"><span class="hightlight-text">{{__('messages.provider')}}:</span> <span>{{data.provider_name}}</span></p>  
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-2"><span class="hightlight-text">{{__('messages.payment_status')}}:</span><span>{{data.payment_status ? data.payment_method : __('messages.pending')}}</span></p>  
                                <p class="mb-2"><span class="hightlight-text">{{__('messages.payment_method')}}:</span><span>{{data.payment_method ? data.payment_method : __('messages.cash')}}</span></p>  
                            </div>
                        </div>
                        <div class="separator"></div>
                        <div class="d-flex book-btn-wrapper">
                                <router-link v-if="data.payment_id == null"  :to="{
                                name: 'bookingdetail',
                                params: { booking_id: data.id },
                                }" class="btn btn-primary pay"> {{__('messages.pay_now')}} </router-link>
                                <router-link v-if="data.payment_id !== null && data.status == 'completed'" :to="{
                                    name: 'bookingdetail',
                                    params: { booking_id: data.id },
                                }" class="btn btn-outline-primary rate">{{__('messages.rate_now')}}</router-link>
                        </div>
                    </div>
                </div>
            </div>
            </router-link>
        </div>
      </div>
      <div class="col-lg-4 mt-lg-0 mt-5">
          <div class="booking-sidebar">
            <h5 class="mb-3">{{__('messages.filter')}}</h5>
            <div class="search-text sidebar-search">
                <form>
                    <div class="input-group mb-0">
                        <input type="text" placeholder="Search" class="form-control"  @keyup="getBookingList" v-model="filterData.keyword"> 
                        <div class="search-icon">
                            <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle> 
                            <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>                                
                    </div>                
                </form>
            </div> 
            <div class="separator"></div> 
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="status" class="form-control-label mb-3">{{__('messages.filter_by_status')}}</label>
                        <multi-select
                            deselect-label=""
                            select-label=""
                            v-model="filterData.status"
                            @input="handleFilterChange"
                            tag-placeholder="status" id="status" 
                            label="label" track-by="id" :options="bookingStatus"
                        ></multi-select>
                    </div>
                </div>
            </div>
          </div>
      </div>  
    </div>
    <div v-else class="row">
        <img :src="baseUrl+'/images/frontend/data_not_found.png'"  />
    </div>
  </div>
</div>
</template>
<script>
import {get} from '../../request'
export default {
  name: "BookingList",
  data() {
    return {
      bookingList: [],
      bookingStatus: [],
      filterData:{},
      baseUrl:window.baseUrl
    };
  },
  mounted() {
    this.filterData = this.defaultFilterData();
    this.getBookingStatus();
  },
  methods: {
    defaultFilterData: function () {
        return {
            status: {},
            keyword: ""
        }
    },
    getBookingList() {
      get("booking-list", {}).then((response) => {
        if(this.filterData.keyword){
            this.bookingList = response.data.data.filter(people =>
                people.service_name.toLowerCase().includes(this.filterData.keyword.toLowerCase()) 
            );
        }else{
            this.bookingList = response.data.data;
        }  
      });
    },
    getBookingStatus(){
        get("booking-status", {}).then((response) => {
            this.bookingStatus = response.data;
        });
    },
    handleFilterChange(){
        let filterData =this.filterData;
        filterData.status = this.filterData.status.value;
        get("booking-list", {}).then((response) => {
            if(this.filterData.status != ''){
                this.bookingList = response.data.data.filter(people =>
                    people.status.toLowerCase().includes(filterData.status.toLowerCase()) 
                );
            }else{
                this.bookingList = response.data.data;
            }   
        }); 
    }
  },
  created() {
    this.getBookingList();
  }
};
</script>