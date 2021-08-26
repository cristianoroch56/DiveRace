import React, { useEffect, useState } from 'react';
import ReactModal from 'react-modal';
import { useHistory, Redirect, Link } from "react-router-dom";
import { useDispatch, useSelector, shallowEqual } from "react-redux";
import '../Login/index.css'
import { forgotPassword } from "../../store/actions/Auth";
import { authError } from "../../store/actions/AuthAction";
import { updateForm } from "../../store/actions/StepWizardAction";
import PopUpLogin from '../Login/PopUpLogin'

const PopUpForgotPassword = (props) => {

    const [inputs, setInputs] = useState({
        username: "",
        email: "",
        password: "",
        disabled: true,
    });

    const authData = useSelector((state) => state.user);
    const redux_state = useSelector((state) => state.StepWizardReducer);

    let history = useHistory();
    const dispatch = useDispatch();

    const handleChange = (e) => {
        const disabled = inputs.username.length > 0 && inputs.email.length > 0 && inputs.password.length > 0 ? false : true;
        setInputs({ ...inputs, [e.target.name]: e.target.value, disabled });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const userEmail = inputs.email;
        dispatch(updateForm('showLoader', true));
        dispatch(forgotPassword(userEmail, history))
    };

    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "650px",
            maxWidth: "100%",
            borderRadius: '10px',
            borderWidth: '0px',
        }
    };


    useEffect(() => {
        dispatch(authError(''));
        ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
    }, [])

    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Forgot Password Modal"

        >
            <button onClick={props.closeForgotPopup} className="custom-popup-close">X</button> 
            <div className="login-main-wrapper">

            {redux_state.alertShow &&
              <div className={`alert alert-${redux_state.alertColor === undefined ? 'success' : redux_state.alertColor}`} role="alert" style={{ display: `block`, width: 'max-content', margin: "10px auto" }}>
                {redux_state.alertMssg ? redux_state.alertMssg : ''}
              </div>
            }
                
                <div className="login-from-wrapper login-form-1">

                    <h4 className="text-left main-header-title">
                        Forgot Password
                                </h4>
                    <form onSubmit={handleSubmit}>
                        <div className="form-group">
                            <input
                                type="email"
                                name="email"
                                className="form-control"
                                placeholder="Email"
                                defaultValue={inputs.email}
                                onChange={handleChange}
                                required
                            />
                        </div>
                        <div className="form-group">
                            <input type="submit" className="btnSubmit btn-lg" value="Request Password" />
                        </div>
                        {authData.authError ? <span style={{ color: "red" }}>{authData.authError}</span> : ''}
                    </form>
                </div>
                <hr className="hr1" />

                <div className="register-from-wrapper login-form-1">
                    <h4 className="text-left main-header-title">
                        Back to Login
                                </h4>
                    <form>
                        <div className="form-group">
                            {/* <Link to="/login"> */}
                            <button type="submit" onClick={() => {props.closeForgotPopup(); props.openLoginPopup();}} className="btnSubmit login-back btn-lg" title="Login" value="Login" >Login</button>
                            {/* </Link> */}
                        </div>
                    </form>
                </div>
            </div>





        </ReactModal>
    );
}

export default PopUpForgotPassword;