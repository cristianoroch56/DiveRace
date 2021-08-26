import React, { useEffect, useState } from 'react';
import ReactModal from 'react-modal';
import { useDispatch, useSelector } from "react-redux";
import { useHistory, Link } from "react-router-dom";
import { registerUser } from "../../store/actions/Auth";
import { authError } from "../../store/actions/AuthAction";
import { updateForm } from "../../store/actions/StepWizardAction";
import Loaderdiv from "../StepWizard/Loader";
import '../Login/index.css';

const PopUpRegister = (props) => {


    let history = useHistory();
    const dispatch = useDispatch();
    const authData = useSelector((state) => state.user);
    const redux_state = useSelector((state) => state.StepWizardReducer);

    const [inputs, setInputs] = useState({
        firstname: "",
        lastname: "",
        //username:"",
        email: "",
        password: "",
        confirm_password: "",
        confirm_password_validate: ""
    });


    const handleChange = (e) => {
        if (e.target.name === 'email') {

            const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!emailRegex.test(e.target.value)) {
                e.target.setCustomValidity("Please Enter a Valid Email Address");
            } else {
                e.target.setCustomValidity("");
            }

        }

        setInputs({ ...inputs, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {

        e.preventDefault();
        if (inputs.password !== inputs.confirm_password) {
            setInputs({ ...inputs, confirm_password_validate: "Confirm Password does not match!" });
            return;
        }
        setInputs({ ...inputs, confirm_password_validate: "" });
        if (inputs.email && inputs.password) {

            const userRegisterPost = { ...inputs };
            dispatch(updateForm('showLoader', true));
            dispatch(registerUser(userRegisterPost, history))
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
        window.scrollTo(0, 0)
        dispatch(authError(''));
        if (authData.isAuthenticated) {
            window.$.toast({
                heading: "Register",
                text: "Successfully Signed In",
                position: 'bottom-right',
                icon: 'info',
                hideAfter: 2000,
                loaderBg: '#ffffff',
            });
            props.closeRegisterPopup();
            //history.replace("/");  
        } 
        ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
    }, [authData.isAuthenticated])



    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Register Modal"

        >


            <button onClick={props.closeRegisterPopup} className="custom-popup-close">X</button>
            <div className="login-main-wrapper login-form-1">
                <div className="login-from-wrapper">

                    <h4 className="text-left main-header-title">
                        Create new account
                                </h4>
                    <form onSubmit={handleSubmit}>
                        <div className="form-group">
                            <input
                                type="text"
                                name="firstname"
                                className="form-control"
                                placeholder="First Name"
                                defaultValue={inputs.firstname}
                                onChange={handleChange}
                                required
                            />
                        </div>
                        <div className="form-group">
                            <input
                                type="text"
                                name="lastname"
                                className="form-control"
                                placeholder="Last Name"
                                defaultValue={inputs.lastname}
                                onChange={handleChange}
                                required
                            />
                        </div>

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
                                minLength="8"
                                className="form-control"
                                placeholder="Password"
                                defaultValue={inputs.password}
                                onChange={handleChange}
                                required
                            />
                        </div>
                        <div className="form-group">
                            <input
                                type="password"
                                minLength="8"
                                name="confirm_password"
                                className="form-control"
                                placeholder="Confirm Password"
                                defaultValue={inputs.confirm_password}
                                onChange={handleChange}
                                required
                            />
                        </div>
                        {inputs.confirm_password_validate ? <span style={{ color: "red" }}>{inputs.confirm_password_validate}</span> : ''}
                        <div className="form-group">
                            <input type="submit" className="btnSubmit btn-lg" value="Register" />
                        </div>
                        {authData.authError ? <span style={{ color: "red" }}>{authData.authError}</span> : ''}
                    </form>
                </div>
                <hr className="hr1" />

                <div className="register-from-wrapper">
                    <h4 className="text-left main-header-title">
                        Back to Login
                                </h4>
                    <form>
                        <div className="form-group">

                            {/* <Link to="/login"> */}
                            <button type="submit" onClick={() => { props.closeRegisterPopup(); props.openLoginPopup(); }} className="btnSubmit login-back btn-lg" title="Login" value="Login" >Login</button>
                            {/* </Link> */}

                        </div>
                    </form>
                </div>
            </div>





        </ReactModal>
    );
}

export default PopUpRegister;