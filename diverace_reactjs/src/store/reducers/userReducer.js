import { SET_CURRENT_USER, LOGOUT, AUTH_ERROR, UPDATE_USER_PROFILE } from "../actions/AuthAction";

import { UPDATE_USER_CREDIT } from "../actions/StepWizardAction";

const user = (state = {}, action) => {
  switch (action.type) {
    case SET_CURRENT_USER:
      return {
        isAuthenticated: true,
        profile: action.payload,
        authError:''
      };
    case LOGOUT:
      return {
        isAuthenticated: false,
        profile: {},
        authError:''
      };
    case UPDATE_USER_CREDIT:
      return {
        ...state,
        profile:{
          ...state.profile,
          user_credit:action.payload
        }
      };
    case AUTH_ERROR:
      return {
        ...state,
        profile:{
          ...state.profile
        },
        authError: action.payload,
      };
    case UPDATE_USER_PROFILE:
        return {
          ...state,
          profile:action.payload
    };
    default:
      return state;
  }
};
export default user;
