import React, { useEffect, useState } from "react";
import { useHistory, Link} from "react-router-dom";
import Loaderdiv from "../StepWizard/Loader";
import {useDispatch, useSelector } from "react-redux";
import {loginUser} from "../../store/actions/Auth";
import {authError} from "../../store/actions/AuthAction";
import {setAlertMssg,updateForm} from "../../store/actions/StepWizardAction";
import './index.css'

const Login = (props) => {


    let history = useHistory();
    const dispatch = useDispatch();
    
    const authData = useSelector((state) => state.user);
    const redux_state = useSelector((state) => state.StepWizardReducer);

    const [inputs , setInputs] = useState({
        email: "",
        password: ""
    });

    const  handleChange = (e) => {
        setInputs({ ...inputs, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (inputs.email && inputs.password) {
           
            const userPost = {...inputs}; 
            dispatch(updateForm('showLoader',true));
            dispatch(loginUser(userPost , history))  
        } 
    };

    setTimeout(() => {
        
        const successObjEmpty = {
          alertMssg:'',
          alertColor:'',
          alertShow:false
        }
        if (redux_state.alertShow) {
          dispatch(setAlertMssg(successObjEmpty));
        }
      }, 4000);
      
    useEffect(() => {
       
        if (authData.isAuthenticated) {
            history.push("/");  
        } 
       
        dispatch(authError(''));
        document.body.style.background = `#dee1f3`;
    },[])

    useEffect(() => {
       
        if (authData.isAuthenticated) {
            history.replace("/");  
        } 
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
                            {redux_state.alertShow && 
                                    <div className={`alert alert-${redux_state.alertColor === undefined ? 'success' : redux_state.alertColor}`} role="alert" style={{display:`block`,width:'max-content',margin: "10px auto"}}>
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
                                    <input type="submit" className="btnSubmit btn-lg" value="Login"/>
                                </div>
                                { authData.authError ? <span style={{color: "red"}}>{authData.authError}</span> : ''}
                                <div className="form-group forgot-password-section">
                                    
                                    <Link to="/forgot_password" className="ForgetPwd" title="Forgot Password">Or Forgot Password</Link>
                                    
                                </div>
                                </form>
                            </div>

                            <hr className="hr1"/>

                            <div className="register-from-wrapper">                                    
                                <h4 className="text-left main-header-title">
                                    Register New account
                                </h4>
                                <form>
                                <div className="form-group">
                                    <Link to="/register">
                                        <input type="submit" className="btnSubmit register-now btn-lg" title="Register Now" value="Register Now"/>
                                    </Link>

                                    <a href="https://diverace.chillybin.biz/" className="btnSubmit back_to_site btn btn-sm btn-lg">Back</a>
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </React.Fragment>
        );
};

export default Login;
