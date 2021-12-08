<template>
<div>
  <breadcrumb-component  :sectionName="'Service'" :homeName="this.$route.meta.label" />
  <div class="service-detail service-main mar-top mar-bot" >
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="service-detail-box">
            <div class="service-image">
                <img :src="serviceData.attchments[0]" alt="image" class="img-fluid rounded">
            </div>
            <div class="service-detail-info">
                <div class="service-heading-part">
                    <div class="service-title">
                    <h3 class="service-title-main">{{serviceData.name}}</h3>
                    <div class="price d-flex align-items-center">
                        <h4 class="primary-color">{{serviceData.price_format}} {{ serviceData.type }}</h4> <span v-if="serviceData.discount" class="ps-2">{{serviceData.discount}}% {{__('messages.off')}}</span>
                    </div>
                </div>
                <div class="service-meta">
                    <ul class="list-inline d-flex flex-wrap align-items-center">
                        <li>
                            <span class="list-label heading-weight primary-color"><a href="#">{{serviceData.category_name}}</a></span>
                        </li>
                        <li class="d-flex align-items-center">
                            <span class="list-label heading-color heading-weight">Rating :</span><span class="list-value"> 
                                <div class="d-flex align-items-center text-primary px-2">                               
                                  <rating-component :readonly = true :showrating ="false" :ratingvalue="serviceData.total_rating" />
                                </div>                                            
                            </span>
                        </li>
                    </ul>
                </div>
                </div>
                <div v-if="serviceData.service_address_mapping.length >  0 " class="service-availability">
                      <div class="header-title-inner-page">
                          <h3>{{__('messages.availability')}}</h3>                    
                      </div>
                      <ul class="list-inline availability-box d-flex align-items-center flex-wrap">
                          <li  v-for="(address, index) in serviceData.service_address_mapping" :key="index">
                              <span  @click="getBookingAddress(address.provider_address_id)">{{ address.provider_address_mapping.address }}</span>
                          </li>
                      </ul>
                </div>
                <div v-if="serviceData.description" class="service-description">
                    <div class="header-title-inner-page">
                        <h3>{{__('messages.description')}}</h3>                    
                    </div> 
                    <div class="service-details">
                        <p>{{serviceData.description}}</p>
                    </div>                            
                </div>
                <div class="service-provider-info">
                  <ul class="list-inline">
                    <li class="provider-card">
                      <div class="author-wrapper">
                        <div class="autho-img">
                            <img :src="provider.profile_image" alt="image" class="img-fluid rounded-circle">
                        </div>
                        <div class="autho-detail">
                            <h5>{{provider.display_name}}</h5>
                            <p class="author-description">{{provider.description}}</p>
                            <ul class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 list-inline">
                                <li class="col">
                                    <label>{{__('messages.email')}}</label>
                                    <p class="mb-0">{{provider.email}}</p>
                                </li>
                                <li class="col">
                                    <label>{{__('messages.address')}}</label>
                                    <p class="mb-0">{{provider.address}}</p>
                                </li>
                                <li class="col">
                                    <label>{{__('messages.contact_number')}}</label>
                                    <p class="mb-0">{{provider.contact_number}}</p>
                                </li>
                                <li class="col">                                                       
                                    <label>{{__('messages.rating')}} </label>  
                                    <div class="d-flex align-items-center">                                                     
                                        <span class="list-value"> 
                                            <div class="d-flex align-items-center text-primary pe-2">                               
                                              <rating-component :readonly = true :showrating ="false" :ratingvalue="provider.providers_service_rating" />
                                            </div>
                                        </span>                                                       
                                    </div>                                                        
                                </li>
                            </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div v-if="ratingData.length >  0 " class="service-review-data">
                    <div class="header-title-inner-page mb-0">
                        <h3>{{__('messages.review')}}</h3>                    
                    </div> 
                    <div class="review-details">
                      <ul class="list-inline">
                        <li class="review-card" v-for="(rating,index) in ratingData"  :key="index">
                            <div class="author-wrapper card-details">
                                <div class="autho-img">
                                    <img :src="rating.profile_image" alt="image" class="img-fluid rounded-circle">
                                </div>
                                <div class="autho-detail">
                                    <div class="autho-title">
                                        <h5>{{rating.customer_name}}</h5>
                                        <div class="review-date">{{rating.created_at}}</div>
                                    </div>
                                    <div class="ratting-value"> 
                                        <div class="d-flex align-items-center text-primary">                               
                                            <rating-component :readonly=true :showrating =false :ratingvalue="rating.rating"  />
                                        </div>
                                    </div>
                                    <p class="review-description mb-0">{{rating.review}}</p>
                                </div>                                            
                            </div>
                        </li>
                      </ul>
                    </div>                            
                </div>
              </div>
          </div>
        </div>
        <div class="col-lg-4 mt-lg-0 mt-5 serivce-sidebar">
          <form @submit.prevent="saveBooking">
          <div class="card">
            <div class="card-body">
                <h4 class="mb-4">{{__('messages.detail_information')}}</h4>
                    <div class="form-group input-text">
                      <v-date-picker v-model="booking.date" mode="dateTime" is24hr required="required">
                        <template v-slot="{ inputValue, inputEvents }">
                          <input
                            class="form-control"
                            :value="inputValue"
                            v-on="inputEvents"
                          />
                        </template>
                      </v-date-picker>
                      <i class="far fa-calendar-alt input-icon"></i>                      
                    </div>
                    <div class="form-group">
                      <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"  v-model="booking.address" :placeholder="__('messages.address')"></textarea>
                      <i class="far fa-map input-icon"></i>
                      <div v-if="submitted && !$v.booking.address.required" class="invalid-feedback-data">
                        {{__('messages.address_required')}}
                      </div>
                    </div>
                    <div class="form-group">
                      <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"  v-model="booking.description" :placeholder="__('messages.description')"></textarea>
                       <i class="far fa-comment-alt input-icon"></i>
                    </div>
                    <div class="form-group"  v-if="serviceData.type === 'fixed'">
                        <b-input-group>
                          <b-input-group-prepend>
                            <b-btn variant="primary" @click="decrement()">-</b-btn>
                          </b-input-group-prepend>
                          <b-form-input
                            type="number"
                            min="0.00"
                            :value="booking.quantity"
                            disabled
                          ></b-form-input>
                          <b-input-group-append>
                            <b-btn variant="primary" @click="increment()">+</b-btn>
                          </b-input-group-append>
                        </b-input-group>
                    </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="payment-info">
                <div class="bill-box">
                  <span>{{__('messages.price')}}</span>
                  <span class="hightlight-text"> {{ serviceData.price_format }}</span>
                </div>   
                <div class="bill-box">
                    <span>{{__('messages.sub_total')}}</span>
                    <span class="hightlight-text">{{ booking.quantity * serviceData.price }}</span>
                </div>  
                <div class="bill-box">
                    <span>{{__('messages.quantity')}}</span>
                    <span class="hightlight-text">* {{ booking.quantity }}</span>
                </div>
                <div v-if=" serviceData.discount" class="bill-box">
                    <span>{{__('messages.discount')}}</span>
                    <h5 class="primary-color"> {{ serviceData.discount }}{{discountType}}</h5>
                </div>
                <div class="bill-box">
                    <span>{{__('messages.coupon')}}</span>
                    <b-link v-if="show" variant="primary">{{ coupons.value }} {{ coupons.type }}</b-link>
                    <div v-if="!show">
                      <b-link
                        v-if="couponData.length > 0"
                        variant="primary"
                        v-b-modal="'applycoupon'"
                        >{{__('messages.apply_coupon')}}</b-link
                      >
                      <b-link v-else variant="primary"
                        >{{__('messages.no_coupon_available')}}</b-link
                      >
                    </div>
                </div>
                <div class="separator"></div>
                <div class="bill-box">
                    <h6>{{__('messages.total_amount')}}</h6>
                    <h5 class="primary-color">{{ booking.total_amount  }} {{ currencySymobl }} </h5>
                </div>
                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-block">{{__('messages.booking')}}</button>
                    <button type="submit" class="btn btn-outline-primary btn-cancel btn-block mt-4" @click="cancel">{{__('messages.cancel')}}</button>
                </div> 
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <b-modal
    ref="modal"
    id="applycoupon"
    title="Apply Coupon"
  >
      <template #modal-header="{ close }">
        <h5>Coupons</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="close()"></button>
      </template>
      <div class="modal-body">
        <form>           
            <input type="text" class="form-control" id="exampleFormControlInput5" placeholder="Enter Coupon Code" v-model="coupons.code">
        </form>
        <div class="coupon-inner">
            <p>Available Coupon</p>
            <div class="coupon-wrapper">
                <div class="copy-box">
                    <span v-for="(data, index) in couponData" :key="index" class="copy-coupon" @click="getCoupon(data)" >{{ data.code }}</span>
              </div> 
            </div>
            <div class="d-grid mt-5">
                <button type="submit" class="btn btn-primary btn-block" @click="addCoupon">Submit</button>
                <button type="submit" class="btn btn-outline-primary btn-cancel btn-block mt-4">Cancel</button>
            </div>     
        </div>
      </div>    
  </b-modal>
   <login-component ref="openModal" />
</div>
</template>
<script>
import { displayMessage } from "../../messages";
import {  required} from "vuelidate/lib/validators";
import {post} from '../../request'
import { mapGetters } from "vuex";

export default {
  name: "Detail",
  data() {
    return {
      serviceData: [],
      couponData: [],
      provider: [],
      ratingData:[],
      booking: {},
      submitted:false,
      show: false,
      coupons: {
        id: "",
        value: "",
        type: "",
        code: "",
      },
    };
  },
  validations: {
    booking: {
      address: {
        required,
      },
    }
  },
  mounted() {
    this.booking = this.defaultBookingRequest();
    this.getServiceData();
  },
  methods: {
      defaultBookingRequest: function () {
        return {
          id: "",
          customer_id: "",
          service_id: "",
          provider_id: "",
          date: new Date(),
          quantity: 1,
          amount: "",
          discount: "",
          coupon_id: "",
          status: "pending",
          booking_address_id: "",
          address: "",
          description: "",
          total_amount: "",
        };
      },
    getServiceData() {
        post("service-detail", {
          service_id: this.$route.params.service_id,
        })
        .then((response) => {
          this.serviceData = response.data.service_detail;
          this.couponData = response.data.coupon_data;
          this.provider = response.data.provider;
          this.ratingData = response.data.rating_data
          this.calcualtePrice();
        });
    },
    getBookingAddress(address_id) {
      this.booking.booking_address_id = address_id;
    },
    addCoupon() {
      this.show = true;
      this.calcualtePrice();
      this.$refs["modal"].hide();
    },
    getCoupon(code) {
      this.coupons.id = code.id;
      this.coupons.value = code.discount;
      this.coupons.type = code.discount_type;
      this.coupons.code = code.code;
      this.booking.coupon_id = code.code;
    },
    increment() {
      this.booking.quantity++;
      this.calcualtePrice(this.booking.quantity);
    },
    decrement() {
      if (this.booking.quantity != 1) {
        this.booking.quantity--;
        this.calcualtePrice(this.booking.quantity);
      }
    },
    calcualtePrice(qty = 1, dis = "", couponcode = "") {
      var qty = qty;
      var dis = this.serviceData.discount;
      var coupon_value = this.coupons.value;

      var amount = this.serviceData.price * qty;
      var total_amount = amount - (amount * dis) / 100;
      if (this.coupons.type == "percentage") {
        total_amount = total_amount - (total_amount * coupon_value) / 100;
      }
      total_amount = total_amount - coupon_value;
      this.booking.total_amount = total_amount;
    },
    saveBooking() {
      if (!this.$store.state.auth) {
        this.$refs.openModal.show();
      } else {
        this.submitted = true;
        this.$v.$touch();
        if (this.$v.booking.$invalid) {
          this.loading = false;
          window.scrollTo(0,0)
          return;
        }
        this.booking.service_id = this.serviceData.id;
        this.booking.provider_id = this.serviceData.provider_id;
        this.booking.customer_id =
          this.$store.state.user != null ? this.$store.state.user.id : 0;
        this.booking.discount = this.serviceData.discount;
        this.booking.amount = this.serviceData.price;
         post("booking-save", this.booking).then((response) => {
          this.defaultBookingRequest();
          displayMessage(response.data.message);
          this.$router.push("/booking-list");
        });
      }
    },
    cancel(){
      this.booking = this.defaultBookingRequest()
    }
  },
  computed: {
    ...mapGetters(['currencySymobl','discountType']),
  },
};
</script>
