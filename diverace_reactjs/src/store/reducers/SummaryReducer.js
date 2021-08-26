import { UPDATE_SUMMARY ,
    UPDATE_COURSE_SUMMARY ,
    UPDATE_RENTAL_EQUIPMENT_summary,
    APPLIED_COUPON_CODE_SUCCESS,
    APPLIED_COUPON_CODE_FAILED,
    APPLIED_COUPON_CODE_STATUS,
    SET_PASSENGER ,
    APPLIED_AGENT_CODE_SUCCESS,
    APPLIED_AGENT_CODE_FAILED,SET_SUMMARY_INITIAL_STATE,
    APPLIED_AGENT_CODE_STATUS} from "../actions/StepWizardAction";
    

const initialState = {
  vessel_type: "",
  vessel_image:'',

  country_type: "",
  itineraryArea_type: "",
  tripDate_type: "",
  
  pax: [],
  passenger:0,
  cabin_type: "",

  courses_types: [],
  rental_equipment_types: [],
  coupon_code_percentage:0,
  coupon_code_status:0,

  agent_code_percentage:0,
  agent_code_status:0
};


const SummaryReducer = (state = initialState, action) => {
  switch (action.type) {
    case UPDATE_SUMMARY:
      return {
        ...state,
        [action.payload.stateType]: action.payload.value,
      }

    case SET_SUMMARY_INITIAL_STATE:
            
        return initialState;   //Always return the initial state

    case UPDATE_COURSE_SUMMARY:
        return {
            ...state,
            courses_types:action.payload
        } 
    case UPDATE_COURSE_SUMMARY:
        return {
            ...state,
            courses_types:action.payload
    } 
    case UPDATE_RENTAL_EQUIPMENT_summary:
        return {
            ...state,
            rental_equipment_types:action.payload
        } 
    case APPLIED_COUPON_CODE_SUCCESS:
        return {
            ...state,
            coupon_code_percentage:action.payload
        }
    case APPLIED_COUPON_CODE_FAILED:
        return {
            ...state,
            coupon_code_err_message:action.payload
        }
    case APPLIED_COUPON_CODE_STATUS:
      return {
          ...state,
          coupon_code_status:action.payload
      }
    case SET_PASSENGER:
      return {
          ...state,
          passenger:action.payload
      }
      case APPLIED_AGENT_CODE_SUCCESS:
        return {
            ...state,
            agent_code_percentage:action.payload
        }
    case APPLIED_AGENT_CODE_FAILED:
        return {
            ...state,
            agent_code_err_message:action.payload
        }
    case APPLIED_AGENT_CODE_STATUS:
      return {
          ...state,
          agent_code_status:action.payload
      }
    default:
      return state;
  }
};

export default SummaryReducer;
