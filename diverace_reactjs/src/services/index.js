import axios from 'axios';

//const API_URL = 'http://68.183.80.245/dive_race/wp-json/custom-api/v1';
//const API_URL = 'https://diverace.wpengine.com/wp-json/custom-api/v1';
const API_URL = 'https://diverace.chillybin.biz/wp-json/custom-api/v1';

const ApiServices = {

    getVessels: function() {
        return axios.get(API_URL + '/get_vessel_data');  
    },
    getCountry: function() {
        return axios.post(API_URL + '/get_country_data');  
    },
    getItineraryArea: function(country_id) {
        return axios.post(API_URL + '/get_itinerary_areas_from_country_data',{country_id:country_id}); 
    },
    getAllTripDates: function(itinerary_id) {
        return axios.post(API_URL + '/get_date_from_itinerary_data',{destination_id:itinerary_id}); 
    },
    getCabins: function(vessel_id,tripDate_id) {
        return axios.post(API_URL + '/get_cabins_from_vessel_id_data',{vessel_id:vessel_id,tripDate_id:tripDate_id}); 
    },
    getCourses: function () { 
        return axios.get(API_URL + '/get_courses_data'); 
    },
    getRentalEquipment: function () {
        return axios.get(API_URL + '/get_rental_equipment_data');  
    },
    appliedCouponCode: function (coupon_code,user_id) {
        return axios.post(API_URL + '/get_coupon_data',{coupon_code:coupon_code,user_id:user_id});    
    },
    appliedAgentCode: function (agent_code,user_id) {
        return axios.post(API_URL + '/get_agent_data',{agent_code:agent_code,user_id:user_id});
    },
    bookingTrip : function (booking_data) {
        return axios.post(API_URL + '/add_order_data',booking_data);    
    },
    getBookedTripData: function (user_id) {
        return axios.post(API_URL + '/view_order_data_from_user_id',{user_id: user_id });   
    },
    getBookedCourses: function (order_id,user_id) {
        return axios.post(API_URL + '/edit_courses_data_from_order_id',{order_id: order_id, user_id: user_id });   
    },
    getBookedRentalEquipment: function (order_id,user_id) {
        return axios.post(API_URL + '/edit_rental_equipment_data_from_order_id',{order_id: order_id, user_id: user_id});   
    },
    getBookedTripSummary: function (order_id,user_id) {
        return axios.post(API_URL + '/order_summery_data_from_order_id',{order_id: order_id, user_id: user_id });   
    },
    orderUpdated : function (updated_data) {
        return axios.post(API_URL + '/save_order_data_from_user_id',updated_data);    
    },
    getUserBalance : function (user_id) {
        return axios.post(API_URL + '/wp_userbalance_api',{user_id:user_id});    
    },
    /* -------------------------------------New------------------------------ */
    getItineraryAreaFromVessel: function (post_data) {
        return axios.post(API_URL + '/get_itinerary_data_from_vessel_data',post_data);   
    },
    
    getProfileDetails: function (user_id) {
        return axios.post(API_URL + '/get_user_data',{user_id: user_id});   
    },
    profileUpdate: function (postdata) {
        return axios.post(API_URL + '/update_user_profile',postdata);   
    },
    changePassword: function (postdata) {
        return axios.post(API_URL + '/update_user_password',postdata );   
    },
    addWaitingList: function (postdata) {
        return axios.post(API_URL + '/add_waiting_list',postdata );   
    },

}

export default ApiServices;