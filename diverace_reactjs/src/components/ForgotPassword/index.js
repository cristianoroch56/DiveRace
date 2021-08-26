import React, { useEffect, useState } from "react";
import { useHistory, Redirect, Link } from "react-router-dom";
import {useDispatch, useSelector , shallowEqual} from "react-redux";
import '../Login/index.css';
import {forgotPassword} from "../../store/actions/Auth";
import {authError} from "../../store/actions/AuthAction";

const ForgotPassword = (props) => {
    const [inputs , setInputs] = useState({
        username:"",
        email: "",
        password: "",
        disabled:true,
    });

    const authData = useSelector((state) => state.user);

    let history = useHistory();
    const dispatch = useDispatch();

    const  handleChange = (e) => {
        const disabled = inputs.username.length > 0 && inputs.email.length > 0 && inputs.password.length > 0 ? false : true;
        setInputs({ ...inputs, [e.target.name]: e.target.value , disabled });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const userEmail = inputs.email;
        dispatch(forgotPassword(userEmail , history))  
    };

    useEffect(() => {
        dispatch(authError(''));
        document.body.style.background = `#dee1f3`;
    },[])

    return (
       <React.Fragment>
           <div className="container login-container">
                <div className="row row no-gutters">
                    <div className="col col-sm-9 col-md-7 col-lg-5 mx-auto login-form-1">
                        
                        <div className="login-main-wrapper">    
                            <div className="login-from-wrapper">    
                            
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

                                    <a href="https://diverace.chillybin.biz/" className="btnSubmit btn btn-sm back_to_site btn-lg">Back</a>
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

export default ForgotPassword;