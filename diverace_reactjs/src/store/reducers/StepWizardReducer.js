import {
    SET_STEP,
    UPDATE_FORM,
    SET_VESSEL_DATA,
    SET_FALSE,
    SET_DEFALT_ITEM,
    SET_COUNTRY_DATA,
    SET_ITINERARY_DATA,
    SET_TRIPDATE_DATA,
    SET_CABIN_DATA,
    SET_COURSES_DATA,
    SET_RENTAL_EQUIPMENT,
    UPDATE_COURSE,
    UPDATE_COURSE_PRICES,
    UPDATE_TOTAL_COURSE_PRICE,
    UPDATE_RENTAL_EQUIPMENT,
    UPDATE_AMOUNT,
    SHOW_MESSAGE,
    SET_DISCOUNT_AMOUNT,
    SET_COUPON_CODE,
    SET_AGENT_DISCOUNT_AMOUNT,
    SET_AGENT_CODE,
    SET_BOOKED_DATA,
    ORDER_BOOKED_SUCCESS,
    ORDER_BOOKED_ERROR,
    SET_TRANSACTION_DETAILS,
    SET_ALL_BOOKED_TRIP_DATA_BY_USER, SET_PREVENT_COURSES,
    SET_ALERT_MESSAGE, SET_INITIAL_STATE, SET_INITIAL_PAX,
    SET_ORDER_BOOKED_SUMMARY
} from "../actions/StepWizardAction";

const initialState = {
    step: 1,
    user_id: 0,
    user_role: '',
    message: 'none',
    vessel_type: 0,
    country_type: 0,
    itineraryArea_type: 0,
    tripDate_type: 0,
    tripDate: '',
    vessel_list: [],
    country_list: [],
    itineraryArea_list: [],
    tripDate_list: [],

    pax: [{
        person_id: undefined, solo: true, two: false, type: "solo", gender: ['male'], is_used: true, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
        cabin_list: [], cabin_id: '', cabin_type: '', cabin_title: '', cabin_price: 0,
        course_id: 0, course_title: '', course_price: 0, course_used: true,
        equipments: [], equipment_used: true,
    }],

    pax_cabin: [
        {
            solo: true, two: false, type: "solo", gender: ['male'],cabin_type:'shared', 
            updated: false
        }
    ],

    course_pax_options: [],
    rentals_pax_options: [],
    new_courses_price: 0,
    new_equipments_price: 0,

    passenger: 1,
    cabin_type: 0,
/* new */  cabin_types: [],
    cabins_list: [],
    cabin_price: 0,
    courses_price: [],
    total_course_price: 0,
    step3_price: 0,
    step4_price: 0,

    payble_amount: 0,
    discount_amount: 0,
    agent_discount_amount: 0,
    final_payble_amount: 0,

    courses_list: [],
    courses_types: [],
    rental_equipment_list: [],
    rental_equipment_types: [],
    coupon_id: 0,
    coupon_code: '',
    agent_id: 0,
    agent_code: '',
    transaction_data: {},
    orderBooked: {},
    order_booking_mssg: '',

    booked_trip_byuser: [],

    order_booked_courses: [],
    prevent_courses_one_nishit: [],

    order_booked_equipments: [],
    prevent_rental_equipment_two: [],

    list: [],

    order_booked_summary: {},
    update_addons_amount: 0,
    final_pay_amount: 0,
    updated_final_amount: 0,
    partial_amount: 0,
    partial_amount_type: 100,
    updated_booking_data: {},

    order_updated_success: 'none',

    user_credit: 0,

    alertMssg: '',
    alertColor: '',
    alertShow: false,
    showLoader: false,

    final_total_amount: 0,

    detination_area_list: [],
    temp_selected_cabins: [],

    default_currency: 'SGD',
    default_currency_code: 'S$',
    total_cost_amount: 0,

    updated_pax_flag:0,

}
const StepWizardReducer = (state = initialState, action) => {

    switch (action.type) {

        case SET_STEP:
            return {
                ...state,
                step: action.payload
            }
        case SET_INITIAL_PAX:
            return {
                ...state,
                pax: [{
                    person_id: undefined, solo: true, two: false, type: "solo", gender: ['male'], is_used: false, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
                    cabin_list: [], cabin_id: '', cabin_type: '', cabin_title: '', cabin_price: 0,
                    course_id: 0, course_title: '', course_price: 0, course_used: true,
                    equipments: [], equipment_used: true,
                }],
                pax_cabin: [
                    {
                        solo: true, two: false, type: "solo", gender: ['male'],cabin_type:'shared'
                    }
                ],
            }
        case SET_INITIAL_STATE:

            return initialState;   //Always return the initial state

        case SET_ALERT_MESSAGE:
            return {
                ...state,
                alertMssg: action.payload.alertMssg,
                alertColor: action.payload.alertColor,
                alertShow: action.payload.alertShow
            }
        case SHOW_MESSAGE:
            return {
                ...state,
                message: action.payload
            }
        case SET_FALSE:
            return {
                ...state,
                isActive: false
            }
        case SET_DEFALT_ITEM:
            return {
                ...state,
                vessel_type: action.payload
            }
        case UPDATE_FORM:
            return {
                ...state,
                // vessel_list: state.vessel_list.map((v,i) => ({...v, isActive: v.id==action.payload.value ? true : false})),
                [action.payload.stateType]: action.payload.value
            }
        case SET_VESSEL_DATA:
            return {
                ...state,
                vessel_list: action.payload
            }
        case SET_CABIN_DATA:
            return {
                ...state,
                cabins_list: action.payload
            }
        case SET_COUNTRY_DATA:
            return {
                ...state,
                country_list: action.payload
            }
        case SET_ITINERARY_DATA:
            return {
                ...state,
                itineraryArea_list: action.payload
            }
        case SET_TRIPDATE_DATA:
            return {
                ...state,
                tripDate_list: action.payload
            }
        case SET_COURSES_DATA:
            return {
                ...state,
                courses_list: action.payload
            }
        case SET_RENTAL_EQUIPMENT:
            return {
                ...state,
                rental_equipment_list: action.payload
            }
        case UPDATE_COURSE:
            return {
                ...state,
                courses_types: action.payload
            }
        case UPDATE_COURSE_PRICES:
            return {
                ...state,
                courses_price: action.payload,
                // total_course_price: state.courses_price.length === 0 ? 0 : state.courses_price.length > 1 ? state.courses_price[0] : state.courses_price.reduce((a, b) => a + b)
            }
        case UPDATE_TOTAL_COURSE_PRICE:
            return {
                ...state,
                total_course_price: action.payload
            }
        case UPDATE_RENTAL_EQUIPMENT:
            return {
                ...state,
                rental_equipment_types: action.payload
            }
        case UPDATE_AMOUNT:
            return {
                ...state,
                payble_amount: action.payload
            }
        case SET_DISCOUNT_AMOUNT:
            return {
                ...state,
                // payble_amount:state.payble_amount ,
                discount_amount: state.final_payble_amount ? state.final_payble_amount * action.payload / 100 : state.final_payble_amount,
                final_payble_amount: state.final_payble_amount - state.final_payble_amount * action.payload / 100 <= 0 ? 0.00 : state.final_payble_amount - state.final_payble_amount * action.payload / 100,
            }
        case SET_COUPON_CODE:
            return {
                ...state,
                coupon_code: action.payload
            }
        case SET_AGENT_CODE:
            return {
                ...state,
                agent_code: action.payload
            }
        case SET_AGENT_DISCOUNT_AMOUNT:
            return {
                ...state,
                agent_discount_amount: state.final_payble_amount ? state.final_payble_amount * action.payload / 100 : state.final_payble_amount,
                final_payble_amount: state.final_payble_amount - state.final_payble_amount * action.payload / 100,
            }
        case SET_BOOKED_DATA:
            return {
                ...state,
                orderBooked: action.payload
            }
        case ORDER_BOOKED_SUCCESS:
            return {
                ...state,
                order_booking_mssg: action.payload
            }
        case ORDER_BOOKED_ERROR:
            return {
                ...state,
                order_booking_mssg: action.payload
            }
        case SET_TRANSACTION_DETAILS:
            return {
                ...state,
                transaction_data: action.payload
            }
        case SET_ALL_BOOKED_TRIP_DATA_BY_USER:
            return {
                ...state,
                booked_trip_byuser: action.payload
            }
        case SET_ORDER_BOOKED_SUMMARY:
            return {
                ...state,
                order_booked_summary: action.payload
            }
        default:
            return state;
    }
}

export default StepWizardReducer;




