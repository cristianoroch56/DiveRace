import React, { useEffect, useState } from 'react';
import ReactModal from 'react-modal';
import { useHistory, Link } from "react-router-dom";
import Loaderdiv from "../StepWizard/Loader";
import { useDispatch, useSelector } from "react-redux";
import { loginUser } from "../../store/actions/Auth";
import { authError } from "../../store/actions/AuthAction";
import { setAlertMssg, updateForm } from "../../store/actions/StepWizardAction";
import './index.css'

const PopUpLogin = (props) => {

    let history = useHistory();
    const dispatch = useDispatch();

    const authData = useSelector((state) => state.user);
    const redux_state = useSelector((state) => state.StepWizardReducer);

    const [forgotPassordModal, setForgotPassordModal] = React.useState(false)
    const [registerModal, setRegisterModal] = React.useState(false)

    const [inputs, setInputs] = useState({
        email: "",
        password: ""
    });

    const handleChange = (e) => {
        setInputs({ ...inputs, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        if (inputs.email && inputs.password) {
 
            const userPost = { ...inputs };
            dispatch(updateForm('showLoader', true));
            dispatch(loginUser(userPost, history))

        }
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
        if (authData.isAuthenticated) {
            window.$.toast({
                heading: "Login",
                text: "Successfully Logged In",
                position: 'bottom-right',
                icon: 'info',
                hideAfter: 2000,
                loaderBg: '#ffffff',
            });
            props.closeLoginPopup();
            window.scrollTo(0, 0)
            //history.replace("/");
        } else {
            return;
        }
        ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
    }, [authData.isAuthenticated])

    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Login Modal"

        >

            <React.Fragment>

                <button onClick={props.closeLoginPopup} className="custom-popup-close">X</button>

                <div className="login-main-wrapper login-form-1">
                    <div className="login-from-wrapper">
                        {redux_state.alertShow &&
                            <div className={`alert alert-${redux_state.alertColor === undefined ? 'success' : redux_state.alertColor}`} role="alert" style={{ display: `block`, width: 'max-content', margin: "10px auto" }}>
                                {redux_state.alertMssg ? redux_state.alertMssg : ''}

                            </div>
                        }

                        <h4 className="text-left main-header-title">
                            Login to member account
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
                                <input
                                    type="password"
                                    name="password"
                                    className="form-control"
                                    placeholder="Password"
                                    defaultValue={inputs.password}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                            <div className="form-group">
                                <input type="submit" className="btnSubmit btn-lg" value="Login" />
                            </div>
                            {authData.authError ? <span style={{ color: "red" }}>{authData.authError}</span> : ''}
                            <div className="form-group forgot-password-section">
                                <a href="#" className="" title="Forgot Password " onClick={() => {props.closeLoginPopup(); props.openForgotPopup(); }}>Or Forgot Password</a>

                                {/*  <Link to="/forgot_password" className="ForgetPwd" title="Forgot Password">Or Forgot Password</Link> */}

                            </div>
                        </form>
                    </div>

                    <hr className="hr1" />

                    <div className="register-from-wrapper">
                        <h4 className="text-left main-header-title">
                            Register New account
                                </h4>
                        <form>
                            <div className="form-group">
                                {/* <Link to="/register"> */}
                                <button type="button" onClick={() => { props.closeLoginPopup(); props.openRegisterPopup(); }} className="btnSubmit register-now btn-lg" title="Register Now" value="Register Now" >Register Now</button>
                                {/* </Link> */}
                            </div>
                        </form>
                    </div>
                </div>


            </React.Fragment>


        </ReactModal>
    );
}

export default PopUpLogin;