import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useHistory,  Link } from "react-router-dom";
import Loaderdiv from "../StepWizard/Loader";
import { registerUser } from "../../store/actions/Auth";
import {authError} from "../../store/actions/AuthAction";
import {updateForm} from "../../store/actions/StepWizardAction";
import '../Login/index.css';

const Register = (props) => {
    
    let history = useHistory();
    const dispatch = useDispatch();
    const authData = useSelector((state) => state.user);
    const redux_state = useSelector((state) => state.StepWizardReducer);
    const [inputs , setInputs] = useState({
        firstname:"",
        lastname:"",
        //username:"",
        email: "",
        password: "",
        confirm_password:"",
        confirm_password_validate:""
    });


    const  handleChange = (e) => {
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
            
            const userRegisterPost = {...inputs}; 
            dispatch(updateForm('showLoader',true));
            dispatch(registerUser(userRegisterPost , history))  
        } 
    };

    useEffect(() => {
        dispatch(authError(''));
        if (authData.isAuthenticated) {
            history.push("/");  
        }
        document.body.style.background = `#dee1f3`;
    },[authData.isAuthenticated])

    return (
       <React.Fragment>
           <div className="container login-container">
                <div className="row row no-gutters">
                    {redux_state.showLoader && 
                        <div className="parentDisable">
                            <div className='overlay-box'>
                            <Loaderdiv isShow={redux_state.showLoader}></Loaderdiv>
                            </div>
                        </div>
                    } 
                    <div className="col col-sm-9 col-md-7 col-lg-5 mx-auto login-form-1">
                        
                        <div className="login-main-wrapper">    
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
                                {/* <div className="form-group">
                                    <input
                                    type="text"
                                    name="username"
                                    className="form-control"
                                    placeholder="User Name"
                                    defaultValue={inputs.username}
                                    onChange={handleChange}
                                    required
                                    />
                                </div> */}
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
                                { inputs.confirm_password_validate ? <span style={{color: "red"}}>{inputs.confirm_password_validate}</span> : ''}
                                <div className="form-group">
                                    <input type="submit" className="btnSubmit btn-lg" value="Register" />
                                </div>
                                { authData.authError ? <span style={{color: "red"}}>{authData.authError}</span> : ''}
                                </form>
                            </div>
                            <hr className="hr1"/>

                            <div className="register-from-wrapper">                                    
                                <h4 className="text-left main-header-title">
                                    Back to Login
                                </h4>
                                <form>
                                <div className="form-group">
                                    
                                    <Link to="/login">
                                        <input type="submit" className="btnSubmit login-back btn-lg" title="Login" value="Login"/>
                                    </Link>

                                    <a href="https://diverace.chillybin.biz/" className="btnSubmit back_to_site btn btn-sm">Back</a>
                                  
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </React.Fragment>
    )
}

export default Register;