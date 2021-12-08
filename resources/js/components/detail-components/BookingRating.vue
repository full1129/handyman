<template>
    <div>
        <div class="service-review-form">
            <div class="header-title-inner-page">
                <h3>{{__('messages.add_review')}}</h3>                    
            </div> 
            <div class="review-form" ref="scrollReview">
                <form>
                    <div class="ratting-box" >
                        <div class="d-flex align-items-center">
                            <span class="list-label">{{__('messages.rating')}} :</span>
                            <span class="list-value"> 
                                <div class="d-flex align-items-center ps-2">                               
                                    <rating-component  @add-service-rating="addServiceRating" :ratingvalue="customerReview!= null ? customerReview.rating: 0 " />
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="comment-form-comment">
                        <textarea id="comment" name="comment" aria-required="true" :placeholder="__('messages.message')" v-model=" rating.review"></textarea>
                    </div>
                    <button @click="saveRatingReview" type="submit" class="btn btn-primary mt-4">{{__('messages.submit')}}</button>
                </form>
            </div>
        </div>
        <div class="service-review-data" v-if="ratingData.length > 0">
            <div class="header-title-inner-page mb-0">
                <h3>{{__('messages.review')}}</h3>                    
            </div> 
            <div class="review-details">
                <ul class="list-inline">
                    <li class="review-card" v-for="(rating,index) in ratingData" :key="index">
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
                                         <rating-component :readonly = true :showrating ="false" :ratingvalue="rating.rating"  />
                                    </div>
                                </div>
                                
                                <p class="review-description mb-0">{{rating.review}}</p>
                                
                            </div>                                            
                        </div>
                        <div class="edit-button">
                            <a v-if="bookingDetail.payment_id !== null && bookingDetail.status == 'completed'" @click="editRating(rating)"> {{__('messages.edit')}} </a> 
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import {displayMessage} from '../../messages.js';
import {post} from '../../request'
export default {
    name: 'BookingRating',
    components: {},
    data(){
        return{
            rating:{},
            showTextbox: false,
            ratingText: "Rating Now",
        }
    },
    mounted(){
        this.rating = this.defaultRatingRequest()
        if(this.customerReview == null || this.customerReview != '' || this.customerReview != {} || this.customerReview.length == []){
            this.showTextbox = true
        }
    },
    props:{
        ratingData:{
            type: Array,
            default: []
        },
        service_id:{
            type:Number,
            default: 0
        },
        booking_id:{
            type:Number,
            default: 0
        },
        bookingDetail:{
            type: [Object, Array],
            default: []
        },
        customerReview:{
            type: [Object, Array],
            default: null
        }
    },
    methods:{
        defaultRatingRequest: function () {
            return {
                id: '',
                booking_id: '',
                service_id:'',
                customer_id: '',
                rating: '',
                review:''
            }
        },
        addServiceRating(rating) {
           this.rating.rating = rating
        },
        saveRatingReview(){
            this.rating.service_id = this.service_id
            this.rating.booking_id = this.booking_id
            this.rating.customer_id = this.$store.state.user != null ? this.$store.state.user.id : 0
            post("save-booking-rating",this.rating).then(response => {
               displayMessage(response.data.message)
               this.showTextbox = false
               this.rating = this.defaultRatingRequest()
               this.$emit('booking-detail')
            });
        },
        editRating(ratingdata){
            var element = this.$refs['scrollReview'];
            var top = element.offsetTop;
            window.scrollTo(0, top);
            this.ratingText ="Edit Rating"
            this.showTextbox = true
            this.rating.id=  ratingdata.id
            this.rating.booking_id= ratingdata.booking_id
            this.rating.service_id= ratingdata.service_id
            this.rating.customer_id= ratingdata.customer_id
            this.rating.rating= ratingdata.rating
            this.rating.review= ratingdata.review
        }
    }
}
</script>