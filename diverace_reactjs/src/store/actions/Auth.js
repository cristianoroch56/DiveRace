import AuthService from "../../services/AuthService";
import {
  setCurrentUser,
  registerSuccess,
  authError
} from "./AuthAction";

import { updateForm,setAlertMssg } from "./StepWizardAction";

/**
 * Login user action
*/

export const loginUser = (state, history) => {
  return (dispatch) => {
    AuthService.login(state.email, state.password)
      .then((resp) => {
        dispatch(updateForm('showLoader',false));
        if (resp.data.status) {
          
          dispatch(setCurrentUser(resp.data.user.data));
          dispatch(updateForm("user_id",Number(resp.data.user.data.ID)));
          dispatch(updateForm("user_role",resp.data.user.roles[0]));
          // AuthService.saveToken(resp.data.token);
          // history.replace('/confirm_payment');
          const successObj = {
            alertMssg:'Log In Successful',
            alertColor:'success',
            alertShow:true
          }
          //dispatch(setAlertMssg(successObj));
          return true;
        } else {
          dispatch(authError(resp.data.message));
          return false;
        }
        
      })
      .catch((err) => {
        dispatch(updateForm('showLoader',false));
        return false;
        window.location.reload();
        console.error(err);
        // dispatch(authError(err.message));
      });
  };
};

/**
 * Register user action
*/
export const registerUser = (data, history) => {
  return (dispatch) => {
    AuthService.register(data)
      .then((resp) => {
        dispatch(updateForm('showLoader',false));
        if (resp.data.status) {
         
          dispatch(registerSuccess());
          dispatch(setCurrentUser(resp.data.user.data));
          dispatch(updateForm("user_id",Number(resp.data.user.data.ID)));
          dispatch(updateForm("user_role",resp.data.user.roles[0]));
          const successObj = {
            alertMssg:'Sign In Successful',
            alertColor:'success',
            alertShow:true
          }
          //dispatch(setAlertMssg(successObj));
          return true;
          history.replace("/");
        } else {
          dispatch(authError(resp.data.message));
          return false;
        }

      })
      .catch((err) => {
        dispatch(updateForm('showLoader',false));
        return false;
        window.location.reload();
        console.error(err);
        dispatch(authError(err.message));
      });
  };
};

/* 
 * Forgot password
*/
export const forgotPassword = (email, history) => {
  return (dispatch) => {
    AuthService.forgotPassword(email)
      .then((resp) => {
        dispatch(updateForm('showLoader',false));
        if (resp.data.status) {
         
          const successObj = {
            alertMssg:'Reset Password link has been sent to your email',
            alertColor:'success',
            alertShow:true
          }
          dispatch(setAlertMssg(successObj));
          return true;
          history.push("/login");
        } else {
          dispatch(authError(resp.data.message));
          return false;
        }
        
      })
      .catch((err) => {
        dispatch(updateForm('showLoader',false));
        console.error(err);
        return false;

      });
  };
};
