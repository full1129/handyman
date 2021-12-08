<template>
<div>
  <breadcrumb-component  :sectionName="'Booking'" :homeName="this.$route.meta.label" />
  <div class="service-detail mar-top mar-bot">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="service-detail-box">
            <div class="service-image">
                <img :src="service.attchments[0]" alt="image" class="img-fluid rounded">
            </div>
            <div class="service-detail-info">
              <div class="service-heading-part">
                <div class="service-title">
                  <h3 class="service-title-main">{{bookingDetail.service_name}}</h3>
                  <div class="price d-flex align-items-center">
                      <h4 class="primary-color">{{bookingDetail.price_format}}</h4> <span v-if="bookingDetail.discount" class="ps-2">{{bookingDetail.discount}}{{discountType}} {{__('messages.off')}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="service-meta">
              <ul class="list-inline d-flex flex-wrap align-items-center">
                <li>
                   <span class="list-label heading-color heading-weight">Booking Status :</span><span class="list-value"> {{bookingDetail.status}}</span>
                </li>
                <li>
                  <span class="list-label heading-color heading-weight">Payment Status :</span><span class="list-value"> {{bookingDetail.payment_status ? bookingDetail.payment_status : __('messages.pending')}}</span>
                </li>
                <li class="d-flex align-items-center">
                    <span class="list-label heading-color heading-weight">{{__('messages.rating')}}</span><span class="list-value"> 
                        <div class="d-flex align-items-center text-primary px-2">                               
                          <rating-component :readonly = true :showrating ="false" :ratingvalue="service.total_rating" />
                        </div>                                            
                    </span>
                    <span>{{bookingDetail.rating}}</span>
                </li>
              </ul>
            </div>
            <div v-if="bookingDetail.description" class="service-description">
              <div class="header-title-inner-page">
                  <h3>{{__('messages.description')}}</h3>                    
              </div> 
              <div class="service-details">
                <p>{{bookingDetail.description}}</p>
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
                                <label>{{__('messages.from')}}</label>
                                <p class="mb-0">{{provider.address}}</p>
                            </li>
                            <li class="col">
                                <label>{{__('messages.contact_number')}}</label>
                                <p class="mb-0">{{provider.contact_number}}</p>
                            </li>
                            <li class="col">                                                       
                                <label>{{__('messages.rating')}}</label>  
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
            <div v-if="assignHandyman.length > 0" class="service-provider-info">
              <div class="header-title-inner-page">
                    <h3>{{__('messages.handyman')}}</h3>                    
              </div> 
              <ul class="list-inline">
                <li v-for="(item, index) in assignHandyman" :key="index" class="provider-card">
                  <div class="author-wrapper">
                    <div class="autho-img">
                        <img :src="item.profile_image" alt="image" class="img-fluid rounded-circle">
                    </div>
                    <div class="autho-detail">
                        <h5>{{item.display_name}}</h5>
                        <p class="author-description">{{item.description}}</p>
                        <ul class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 list-inline">
                            <li class="col">
                                <label>{{__('messages.email')}}</label>
                                <p class="mb-0">{{item.email}}</p>
                            </li>
                            <li class="col">
                                <label>{{__('messages.address')}}</label>
                                <p class="mb-0">{{item.address}}</p>
                            </li>
                            <li class="col">
                                <label>{{__('messages.contact_number')}}</label>
                                <p class="mb-0">{{item.contact_number}}</p>
                            </li>
                            <li class="col">                                                       
                                <label>{{__('messages.rating')}}</label>  
                                <div class="d-flex align-items-center">                                                     
                                    <span class="list-value"> 
                                        <div class="d-flex align-items-center text-primary pe-2">                               
                                          <rating-component :readonly = true :showrating ="false" :ratingvalue="item.providers_service_rating" />
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
            <booking-rating 
              :ratingData="ratingData"
              :service_id="bookingDetail.service_id"
              :booking_id="bookingDetail.id"
              @booking-detail="getBookingDetail"
              :bookingDetail="bookingDetail"
              :customerReview="customerReview"
            />
          </div>
        </div>
        <div class="col-lg-4 mt-lg-0 mt-5 serivce-sidebar">                    
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{__('messages.payment_summary')}}</h4>
                    <div class="payment-info">
                        <div class="bill-box">
                            <span>{{__('messages.payment_status')}}</span>
                            <span class="hightlight-text">{{ bookingDetail.payment_status ? bookingDetail.payment_status : 'Pending' }}</span>
                        </div>                      
                  
                        <div class="bill-box">
                            <span>{{__('messages.payment_method')}}</span>
                            <span class="hightlight-text">{{
                              bookingDetail.payment_method
                                ? bookingDetail.payment_method
                                : "Cash on Delivery"
                            }}</span>
                        </div>                     
                    
                        <div v-if="bookingDetail.discount" class="bill-box">
                            <span>{{__('messages.discount')}}</span>
                            <span class="hightlight-text">{{ bookingDetail.discount }} {{discountType}}</span>
                        </div>                               
                        <div class="separator"></div>
                        <div class="bill-box">
                            <h6>{{__('messages.total_amount')}}</h6>
                            <h5 class="primary-color"> {{ bookingDetail.total_amount }} {{currencySymobl}}</h5>
                        </div>
                        <div v-if="bookingDetail.payment_id == null" class="d-grid mt-5">
                            <button  @click="savePayment()" type="submit" class="btn btn-primary btn-block">{{__('messages.pay_now')}}</button>
                        </div>                                
                    </div>                            
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import BookingRating from "../../components/detail-components/BookingRating.vue";
import { displayMessage } from "../../messages.js";
import {post} from '../../request'
import { mapGetters } from "vuex";

export default {
  name: "Detail",
  data() {
    return {
      bookingDetail: [],
      service: [],
      ratingData: [],
      assignHandyman: [],
      provider: [],
      customerReview:[],
      payment: {},
    };
  },
  components: {
    BookingRating,
  },
  mounted() {
    this.payment = this.defaultPaymentRequest();
    this.getBookingDetail();
  },
  computed: {
    ...mapGetters(['discountType','currencySymobl']),
  },
  methods: {
    defaultPaymentRequest: function () {
      return {
        id: "",
        customer_id: "",
        booking_id: "",
        provider_id: "",
        datetime: new Date(),
        discount: 1,
        total_amount: "",
        payment_type: "cash",
        payment_status: "pending",
      };
    },
    getBookingDetail() {
        post("booking-detail", {
          booking_id: this.$route.params.booking_id,
          customer_id :this.$store.state.user != null ? this.$store.state.user.id : 0
        })
        .then((response) => {
          this.bookingDetail = response.data.booking_detail;
          this.service = response.data.service;
          this.ratingData = response.data.rating_data;
          this.customerReview = response.data.customer_review
          this.assignHandyman = response.data.handyman_data;
          this.provider = response.data.provider_data;
        });
    },
    savePayment() {
      this.payment.customer_id = this.$store.state.user != null ? this.$store.state.user.id : 0;
      this.payment.booking_id = this.bookingDetail.id;
      this.payment.provider_id = this.bookingDetail.provider_id;
      this.payment.discount = this.bookingDetail.discount;
      this.payment.total_amount = this.bookingDetail.total_amount;
      if(confirm("Are you sure you want to pay?")){
        post("save-payment", this.payment).then((response) => {
          this.getBookingDetail();
          displayMessage(response.data.message);
          this.$router.push("/booking-list");
        });
      }
    },
  },
};
</script>