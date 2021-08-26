
export const SET_CURRENT_USER = "SET_CURRENT_USER";
export const REGISTER_SUCCESS = "REGISTER_SUCCESS";
export const LOGOUT = "LOGOUT";
export const AUTH_ERROR = "AUTH_ERROR";
export const UPDATE_USER_PROFILE =  "UPDATE_USER_PROFILE";


export const setCurrentUser = (data) => {
  return {
    type: SET_CURRENT_USER,
    payload: data,
  };
};

export function registerSuccess() {
  return {
    type: REGISTER_SUCCESS,
  };
}

export function logoutUser() {
  return {
    type: LOGOUT,
  };
}

export const authError = (errors) => {
  return {
    type: AUTH_ERROR,
    payload: errors,
  };

};

export const updateUser = (profile) => {
  return {
    type: UPDATE_USER_PROFILE,
    payload: profile,
  };

};




