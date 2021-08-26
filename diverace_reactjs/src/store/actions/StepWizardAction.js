import ApiServices from "../../services/index";


    
export const  SET_STEP = "SET_STEP";
export const  UPDATE_FORM = "UPDATE_FORM";
export const UPDATE_SUMMARY = "UPDATE_SUMMARY"
export const  SET_DEFALT_ITEM = "SET_DEFALT_ITEM";
export const SET_VESSEL_DATA = "SET_VESSEL_DATA";
export const SET_COUNTRY_DATA = "SET_COUNTRY_DATA";
export const SET_ITINERARY_DATA = "SET_ITINERARY_DATA";
export const SET_TRIPDATE_DATA = "SET_TRIPDATE_DATA";
export const  SET_FALSE = "SET_FALSE";
export const SET_PAX = "SET_PAX";
export const SET_CABIN_DATA = "SET_CABIN_DATA";
export const SET_COURSES_DATA = "SET_COURSES_DATA";
export const SET_RENTAL_EQUIPMENT = "SET_RENTAL_EQUIPMENT";
export const UPDATE_RENTAL_EQUIPMENT = "UPDATE_RENTAL_EQUIPMENT";
export const UPDATE_RENTAL_EQUIPMENT_summary = "UPDATE_RENTAL_EQUIPMENT_summary";
export const UPDATE_COURSE = "UPDATE_COURSE";
export const UPDATE_COURSE_SUMMARY = "UPDATE_COURSE_SUMMARY";
export const UPDATE_AMOUNT = "UPDATE_AMOUNT";
export const SHOW_MESSAGE = "SHOW_MESSAGE";
export const UPDATE_COURSE_PRICES = "UPDATE_COURSE_PRICES";

export const SET_COUPON_CODE = "SET_COUPON_CODE";
export const APPLIED_COUPON_CODE_SUCCESS = "APPLIED_COUPON_CODE_SUCCESS";
export const APPLIED_COUPON_CODE_FAILED = "APPLIED_COUPON_CODE_FAILED";
export const APPLIED_COUPON_CODE_STATUS = "APPLIED_COUPON_CODE_STATUS";
export const SET_DISCOUNT_AMOUNT = "SET_DISCOUNT_AMOUNT";

export const SET_AGENT_CODE = "SET_AGENT_CODE";
export const APPLIED_AGENT_CODE_SUCCESS = "APPLIED_AGENT_CODE_SUCCESS";
export const APPLIED_AGENT_CODE_FAILED = "APPLIED_AGENT_CODE_FAILED";
export const APPLIED_AGENT_CODE_STATUS = "APPLIED_AGENT_CODE_STATUS";
export const SET_AGENT_DISCOUNT_AMOUNT = "SET_AGENT_DISCOUNT_AMOUNT";

export const UPDATE_TOTAL_COURSE_PRICE = "UPDATE_TOTAL_COURSE_PRICE";
export const SET_PASSENGER = "SET_PASSENGER";

export const SET_TRANSACTION_DETAILS = "SET_TRANSACTION_DETAILS";
export const SET_BOOKED_DATA = "SET_BOOKED_DATA";
export const ORDER_BOOKED_SUCCESS = "ORDER_BOOKED_SUCCESS";
export const ORDER_BOOKED_ERROR = "ORDER_BOOKED_ERROR";

export const SET_ALL_BOOKED_TRIP_DATA_BY_USER = "SET_ALL_BOOKED_TRIP_DATA_BY_USER";

export const SET_ORDER_BOOKED_SUMMARY = "SET_ORDER_BOOKED_SUMMARY";


export const SET_PREVENT_COURSES = "SET_PREVENT_COURSES";
export const SET_ALERT_MESSAGE = "SET_ALERT_MESSAGE";

export const SET_INITIAL_STATE = "SET_INITIAL_STATE";
export const SET_INITIAL_PAX = "SET_INITIAL_PAX";
export const SET_SUMMARY_INITIAL_STATE = "SET_SUMMARY_INITIAL_STATE";

export const GET_USER_BALANCE = "GET_USER_BALANCE";


export const UPDATE_USER_CREDIT = "UPDATE_USER_CREDIT";


export const setStep = (step) => {
    return {
        type: SET_STEP,
        payload:step
    }
}

export const getUserBalance = (userId) => {
    return {
        type: SET_STEP,
        payload:GET_USER_BALANCE
    }
}

export const setInitialState = () => {
    return {
        type: SET_INITIAL_STATE
    }
}

export const setInitialPax = () => {
    return {
        type: SET_INITIAL_PAX
    }
}

export const setSummaryInitialState = () => {
    return {
        type: SET_SUMMARY_INITIAL_STATE
    }
}


export const setAlertMssg = (data) => {
    return {
      type: SET_ALERT_MESSAGE,
      payload: data,
    };
    
  };

export const setPreventCourses = (data) => {
    return {
        type: SET_PREVENT_COURSES,
        payload:data
    }
}


export const setPassenger = (number) => {
    return {
        type: SET_PASSENGER,
        payload:number
    }
}


export const showMessage = (val) => {
    return {
        type: SHOW_MESSAGE,
        payload:val
    }
}

export const updateAmount = (amount) => {
    return {
        type: UPDATE_AMOUNT,
        payload:amount
    }
}

export const setDefaltItem = (id) => {
    return {
        type: SET_DEFALT_ITEM,
        payload:id
    }
}

export const setFalse = () => {
    return {
        type: SET_FALSE
    }
}

export const updateForm = (stateType,value) => {
    
    return {
        type: UPDATE_FORM,
        payload: {
            stateType,
            value
        }
    }
}

export const updateSummary = (stateType,value) => {
    return {
        type: UPDATE_SUMMARY,
        payload: {
            stateType,
            value
        }
    }
}

export const setVesselData = (data) => {
    return {
        type: SET_VESSEL_DATA,
        payload: data
    }
}


export const setCountryData = (data) => {
    return {
        type: SET_COUNTRY_DATA,
        payload: data
    }
}

export const setItineraryData = (data) => {
    return {
        type: SET_ITINERARY_DATA,
        payload: data
    }
}

export const setTripDateData = (data) => {
    return {
        type: SET_TRIPDATE_DATA,
        payload: data
    }
}

export const setPax = (data) => {
    return {
        type: SET_PAX,
        payload:data
    }
}

export const setCabinData = (data) => {
    return {
        type: SET_CABIN_DATA,
        payload: data
    }
}
export const setCoursData = (data) => {
    return {
        type: SET_COURSES_DATA,
        payload: data
    }
}

export const updateCourse = (data) => {
    return {
        type: UPDATE_COURSE,
        payload: data
    }
}

export const updateCoursePrices = (data) => {
    return {
        type: UPDATE_COURSE_PRICES,
        payload: data
    }
}

export const updateCourseTotalPrice = (price) => {
    return {
        type: UPDATE_TOTAL_COURSE_PRICE,
        payload:price
    }
}

export const updateCourseSummary = (data) => {
    return {
        type: UPDATE_COURSE_SUMMARY,
        payload: data
    }
}

export const setRentalEquipmentData = (data) => {
    return {
        type: SET_RENTAL_EQUIPMENT,
        payload: data
    }
}

export const updateRentalEquipment = (data) => {
    return {
        type: UPDATE_RENTAL_EQUIPMENT,
        payload: data
    }
}

export const updateRentalEquipmentSummary = (data) => {
    return {
        type: UPDATE_RENTAL_EQUIPMENT_summary,
        payload: data
    }
}

export const appliedCouponCodeSuccess = (percentage) => {
    return {
        type: APPLIED_COUPON_CODE_SUCCESS,
        payload:percentage
    }
}

export const appliedCouponCodeFailed = (message) => {
    return {
        type: APPLIED_COUPON_CODE_FAILED,
        payload:message
    }
}

export const appliedCouponCodeStatus= (status) => {
    return {
        type: APPLIED_COUPON_CODE_STATUS,
        payload:status
    }
}

export const setCouponCode = (code) => {
    return {
        type: SET_COUPON_CODE,
        payload:code
    }
}

export const setDiscount = (percentage) => {
    return {
        type: SET_DISCOUNT_AMOUNT,
        payload:percentage
    }
}

export const setAgentCode = (code) => {
    return {
        type: SET_AGENT_CODE,
        payload:code
    }
}

export const setAgentDiscount = (percentage) => {
    return {
        type: SET_AGENT_DISCOUNT_AMOUNT,
        payload:percentage
    }
}

export const appliedAgentCodeSuccess = (percentage) => {
    return {
        type: APPLIED_AGENT_CODE_SUCCESS,
        payload:percentage
    }
}

export const appliedAgentCodeFailed = (message) => {
    return {
        type: APPLIED_AGENT_CODE_FAILED,
        payload:message
    }
}

export const appliedAgentCodeStatus= (status) => {
    return {
        type: APPLIED_AGENT_CODE_STATUS,
        payload:status
    }
}


export const setBookingData = (data) => {
    return {
        type: SET_BOOKED_DATA,
        payload: data
    }
}

export const setBookingSuccess = (mssg) => {
    return {
        type: ORDER_BOOKED_SUCCESS,
        payload: mssg
    }
}

export const setBookingError = (mssg) => {
    return {
        type: ORDER_BOOKED_ERROR,
        payload: mssg
    }
}

export const setTransactionDetails = (data) => {
    return {
        type: SET_TRANSACTION_DETAILS,
        payload:data
    }
}

export const setAllBookedTripByUser = (data) => {
    return {
        type: SET_ALL_BOOKED_TRIP_DATA_BY_USER,
        payload: data
    }
}

export const setOrderBookedSummary = (data) => {
    return {
        type: SET_ORDER_BOOKED_SUMMARY,
        payload:data
    }
}


export const updateUserCredit = (credit) => {
    return {
      type: UPDATE_USER_CREDIT,
      payload: credit,
    };
  
};



// ---------------------------Step-1 Vessels-------------------------//
export const fetchVessels = (vessel_id = 0) => {
    return (dispatch) => {
        ApiServices.getVessels()
        .then(resp => {
            if (resp.data.status) {
            
                const newResp =  resp.data.data
                dispatch(setVesselData(newResp))
                if (vessel_id === 0) {
                    if(newResp.length === 1) {
                        dispatch(setStep(2));
                    }
                    dispatch(updateForm('vessel_type',resp.data.data[0].id))
                    dispatch(updateSummary('vessel_type',resp.data.data[0].title))
                    dispatch(updateSummary('vessel_image',resp.data.data[0].featured_image));
                }
                
            }
        }).catch(error => {
            console.log(error);
        })
    
    }
}

//---------------------------- Step-2 Async-------------------------//

export function fetchCountries() {

    return async (dispatch) => {

        try {
            let resp = await ApiServices.getCountry()
            var resCountry =  resp.data.data
            if (resp.data.status) { 
                dispatch(setCountryData(resCountry))
            } else {
                dispatch(setCountryData(resCountry))
                dispatch(updateForm('country_type',0))
                dispatch(updateSummary('country_type',''))
            }
        } catch (error) {
            console.log(error.message);
        }
    }
    
}

export function fetchItineraryAreaByCountry(country_id = 0) {

    return async (dispatch) => {

        try {
            let resp = await ApiServices.getItineraryArea(country_id)
            var rseArea =  resp.data.data
            if (resp.data.status) {
                dispatch(setItineraryData(rseArea))
            } else {
                dispatch(setItineraryData(rseArea))
                dispatch(updateForm('itineraryArea_type',0))
                dispatch(updateSummary('itineraryArea_type',''))
            }
        } catch (error) {
            console.log(error.message);
        }
    }
    
}

export function fetchTripDatesByItinerary(itinerary_id = 0) {

    return async (dispatch) => {

        try {
            let resp = await ApiServices.getAllTripDates(itinerary_id)
            var resTripDates =  resp.data.data
            if (resp.data.status) {
                dispatch(setTripDateData(resTripDates))
            } else {
                dispatch(setTripDateData(resTripDates))
                dispatch(updateForm('tripDate_type',0))
                dispatch(updateSummary('tripDate_type',''))
            }
        } catch (error) {
            console.log(error.message);
        }
    }
    
}


// ---------------------------Step-3 Cabins -------------------------//
export const fetchCabins = (vessel_id = 0,tripDate_id=0) => {

    return  async (dispatch) => {
        dispatch(setCabinData([]));
        try {
            const response = await ApiServices.getCabins(vessel_id,tripDate_id);
            const resCabin =  response.data.data;
            await dispatch(setCabinData([]));
            
        } catch (error) {
            console.log(error.message);
        } finally {
            return true;
        }
        
    }

}

// ---------------------------Step-4 Rental Equipment -------------------------//
export const fetchRentalEquipment = () => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getRentalEquipment()
            const resRentalData = response.data.data;
            await dispatch(setRentalEquipmentData(resRentalData))
        } catch (error) {
            console.log(error.message);
        }
    }
}

// ---------------------------Step-4 Courses -------------------------//
export const fetchCourses = () => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getCourses()
            const resCourseData = response.data.data;
            await dispatch(setCoursData(resCourseData))
        } catch (error) {
            console.log(error.message);
        }
    }
}

//-------------------------------- Step-5 Coupon code ----------------------------//
export const applyCouponCode = (code='',user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.appliedCouponCode(code,user_id)
            const resCouponData = response.data.data;
            
            if (resCouponData.coupon_status) {
                await dispatch(appliedCouponCodeSuccess(resCouponData.coupon_discount_percentage))
                await dispatch(appliedCouponCodeStatus(1))
                await dispatch(updateForm('coupon_id',resCouponData.id))
                await dispatch(setDiscount(Number(resCouponData.coupon_discount_percentage)))
            } else{
                await dispatch(appliedCouponCodeStatus(0))
            }
            await dispatch(appliedCouponCodeFailed(resCouponData.message))
            
        } catch (error) {
            console.log(error.message);
        }
    }
}

export const applyAgentCode = (code='',user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.appliedAgentCode(code,user_id)
            const resAgentCouponData = response.data.data;
            
            if (resAgentCouponData.agent_status) {
                await dispatch(appliedAgentCodeSuccess(resAgentCouponData.agent_discount_percentage))
                await dispatch(appliedAgentCodeStatus(1))
                await dispatch(updateForm('agent_id',resAgentCouponData.id))
                await dispatch(setAgentDiscount(Number(resAgentCouponData.agent_discount_percentage)))
            } else{
                await dispatch(appliedAgentCodeStatus(0))
            }
            await dispatch(appliedAgentCodeFailed(resAgentCouponData.message))
            
        } catch (error) {
            console.log(error.message);
        }
    }
}

//-------------------------------- step-5 booked trip ----------------------------//
export const bookedTrip = (booking_data,history) => {
    return async (dispatch) => {
        await dispatch(updateForm('showLoader',false));
        try {
           
            const response = await ApiServices.bookingTrip(booking_data)
            if (response.data.status) {
                
                await dispatch(setBookingData(response.data.data));
                await dispatch(setBookingSuccess(response.data.message));
                await dispatch(setStep(1));
                dispatch(updateForm("user_id",Number(response.data.data.user_id)));
                history.replace("/order_summary");
                
            } else{
                await dispatch(setBookingError(response.data.message));
                history.replace("/order_fail");
            }
        } catch (error) {
            console.log(error.message);
        }
    }
}

//---------------------------- get all booked trip list by user id-----//
export const fetchAllBookedTripByUser = (user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getBookedTripData(user_id)
            const resBookedData = response.data.data;
            await dispatch(setAllBookedTripByUser(resBookedData))
        } catch (error) {
            console.log(error.message);
        }
    }
}

// ---------------------------fetch booked courses -------------------------//
export const fetchBookedCourses = (order_id,user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getBookedCourses(order_id,user_id)
            const resCourseData = response.data.data;
            /* const setCourses = {
                [courses] : resCourseData
            }; */
            
            window.localStorage.setItem('prev_courses_data', JSON.stringify(resCourseData));
            
            await dispatch(updateForm('order_booked_courses',resCourseData));
            // await dispatch(setPreventCourses(resCourseData))
            // await dispatch(updateForm('prevent_courses_one_nishit',resCourseData));
            // await dispatch(updateForm('updated_booking_data',setCourses));
        } catch (error) {
            console.log(error.message);
        }
    }
}

// ---------------------------fetch booked rental-equipments ------- ------------------//
export const fetchBookedRentalEquipment = (order_id,user_id) => {
    
    return async (dispatch) => {
        try {
            const response = await ApiServices.getBookedRentalEquipment(order_id,user_id)
            const resRentalData = response.data.data;

            await dispatch(updateForm('order_booked_equipments',resRentalData))

            window.localStorage.setItem('prev_rental_data', JSON.stringify(resRentalData));
            // await dispatch(updateForm('prevent_rental_equipment_two',resRentalData))
        } catch (error) {
            console.log(error.message);
        }
    }
}

// ---------------------------fetch booked trip summary -------------------------//
export const fetchBookedTripSummary = (order_id,user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getBookedTripSummary(order_id,user_id)
            const resSummaryData = response.data.data;
            let setResponse = resSummaryData.length > 0 ? resSummaryData[0] : [];
            await dispatch(setOrderBookedSummary(setResponse))
        } catch (error) {
            console.log(error.message);
        }
    }
}

//----------------------------------- Order updated -----------------------------//
export const orderUpdatedByUser = (updated_data,history) => {
    return async (dispatch) => {
        await dispatch(updateForm('showLoader',false));
        try {
           
            const response = await ApiServices.orderUpdated(updated_data)
            if (response.data.status) {
                
                await dispatch(setBookingSuccess(response.data.message));
                await dispatch(updateForm('order_updated_success','block'))
               
                history.replace("/order_details");
            } else{
                await dispatch(setBookingError(response.data.message));
            }
        } catch (error) {
            console.log(error.message);
        }
    }
}

//------------------- get user balance-----------------------------//
export const fetchUserBalance = (user_id) => {
    return async (dispatch) => {
        try {
            const response = await ApiServices.getUserBalance(user_id)
            const resUserCredit = response.data.user_credit;
            await dispatch(updateUserCredit(resUserCredit))
        } catch (error) {
            console.log(error.message);
        }
    }
}





