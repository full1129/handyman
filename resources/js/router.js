import Vue from 'vue'
import VueRouter from 'vue-router'
import FrontendHome from './views/landingpage/Home.vue';
import Categories from './views/category/Categories.vue'
import Services from './views/service/Services.vue'
import Provider from './views/provider/Provider.vue'
import ProviderService from './views/provider/ProviderService.vue'
import ServiceDetail from './views/service/ServiceDetail.vue'
import CategoryService from './views/category/CategoryService.vue'
import Login from './views/auth/Login.vue'
import Register from './views/auth/Register.vue'
import BookingList from './views/booking/BookingList.vue'
import BookingDetail from './views/booking/Detail.vue'
import UserFavorite from './views/service/UserFavorite.vue'
import AboutUs from './views/AboutUs.vue'
import ContactUs from './views/ContactUs.vue'
import Privacy from './views/Privacy.vue'
import Term from './views/Term.vue'

import store from "./store";

Vue.use(VueRouter)
const routes = [
    { path: '/', name: 'frontend-home', component: FrontendHome, meta: { label: 'Home' } },
    { path: '/categories', name: 'categories', component: Categories, meta: { label: 'Category List' } },
    { path: '/services', name: 'services', component: Services, meta: { label: 'Service List' } },
    { path: '/favourite-service', name: 'user-favourite-service', component: UserFavorite, meta: { label: 'User Favorite' } },
    { path: '/provider', name: 'provider', component: Provider, meta: { label: 'Provider List' } },
    { path: '/booking-list', name: 'booking', component: BookingList, meta: { label: 'Booking List' } },
    { path: '/booking-detail/:booking_id', name: 'bookingdetail', component: BookingDetail, meta: { label: 'Booking Detail' } },
    { path: '/category-service/:category_id', name: 'category-service', component: CategoryService, meta: { label: 'Service Category List' } },
    { path: '/provider-service/:provider_id', name: 'provider-service', component: ProviderService, meta: { label: 'Provider Service List' } },
    { path: '/service-detail/:service_id', name: 'service-detail', component: ServiceDetail, meta: { label: 'Service Detail' } },
    { path: '/about-us', name: 'about-us', component: AboutUs, meta: { label: 'About Us' } },
    { path: '/contact-us', name: 'contact-us', component: ContactUs, meta: { label: 'Contatc Us' } },
    { path: '/privacy-policy', name: 'privacy-policy', component: Privacy, meta: { label: 'Privacy Policy' } },
    { path: '/term-conditions', name: 'term-conditions', component: Term, meta: { label: 'Term Conditions' } },
    { path: '/login', name: 'login', component: Login },
    {
        name: "register",
        path: "/register",
        component: Register,
    },
];
var router = new VueRouter({
    base: process.env.BASE_URL,
    routes: routes
})
router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user')
    window.scrollTo(0, 0);
    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login')
        return
    }
    next()
})
export default router
