import React, { useEffect, useState, useRef } from "react";
import { useHistory, Link } from "react-router-dom";
import Loaderdiv from "../StepWizard/Loader";
import '../Login/index.css';
import ReactModal from 'react-modal';
import { useSelector } from "react-redux";
import ApiServices from "../../services/index"

const ChangePassword = (props) => {

    const userLoginData = useSelector((state) => state.user.profile);

    const [inputs, setInputs] = useState({
        current_password: "",
        new_password: "",
        confirm_new_password: "",

    });
    const [apis, setApis] = useState({
        error_msg: ''
    })

    const [passshow, setpassshow] = useState(false);
    const [confirmpassshow, setConfirmpassshow] = useState(false);
    const [isSubmitted, setIsSubmitted] = useState(false);

    const password = useRef();
    const confirm_password = useRef();

    const showpassword = () => {
        setpassshow(!passshow)
        password.current.type = passshow ? 'password' : 'text';
    }
    const showConfirmpassword = () => {
        setConfirmpassshow(!confirmpassshow)
        confirm_password.current.type = confirmpassshow ? 'password' : 'text';
    }

    const handleSubmit = async (e) => {
        e.preventDefault();

        setIsSubmitted(true);

        var formData = new FormData(document.getElementById("user_change_password_form"));
        const USER_ID = userLoginData.ID;
        formData.append(
            "user_id", USER_ID
        );
        let resp = await ApiServices.changePassword(formData);
        setIsSubmitted(false);
        if (resp.data.status) {
            window.$.toast({
                heading: "Change Password",
                text: "Password changed Successfully.",
                position: 'bottom-right',
                icon: 'info',
                hideAfter: 2000,
                loaderBg: '#ffffff',
            });
            props.closeModal();
        } else {
            setApis({ error_msg: resp.data.message })
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
    }, [])

    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Change Modal"

        >
            <React.Fragment>
                <button onClick={props.closeModal} className="custom-popup-close">X</button>
                <div className="login-main-wrapper login-form-1">
                    <div className="login-from-wrapper">

                        <h4 className="text-left main-header-title">
                            Change Password
                                </h4>
                        <form onSubmit={handleSubmit} id="user_change_password_form">
                            <div className="form-group">
                                <label htmlFor="exampleInputEmail1">Current Password</label>
                                <input
                                    type="password"
                                    name="current_password"
                                    className="form-control"
                                    placeholder="Current Password"
                                    defaultValue={inputs.current_password}

                                    required
                                />
                            </div>
                            <div className="form-group first-example">
                                <label htmlFor="exampleInputEmail1">New Password</label>
                                <input
                                    type="password"
                                    name="new_password"
                                    className="form-control"
                                    placeholder="New Password"
                                    defaultValue={inputs.new_password}
                                    ref={password}
                                    required
                                /> <span onClick={showpassword} style={{ cursor: 'pointer' }}><i className={`${passshow ? 'fa fa-eye' : 'fa fa-eye-slash'} pass-icon`}></i></span>
                            </div>
                            <div className="form-group first-example">
                                <label htmlFor="exampleInputEmail1">Confirm New Password</label>
                                <input
                                    type="password"
                                    name="confirm_new_password"
                                    className="form-control"
                                    placeholder="Confirm New Password"
                                    defaultValue={inputs.confirm_new_password}
                                    ref={confirm_password}
                                    required
                                />
                                <span onClick={showConfirmpassword} style={{ cursor: 'pointer' }}> <i className={`${confirmpassshow ? 'fa fa-eye' : 'fa fa-eye-slash'} pass-icon`} aria-hidden="true"></i></span>
                            </div>
                            {apis.error_msg ? <span style={{ color: "red" }}>{apis.error_msg}</span> : ''}
                            <button type="submit" className="btn btn-primary btnSubmit" disabled={isSubmitted} style={{display: "block"}}>
                                {isSubmitted && (<span className="spinner-border spinner-border-sm"></span>)} Save
                                </button>
                            
                        </form>
                    </div>

                </div>

            </React.Fragment>
        </ReactModal>
    );
};

export default ChangePassword;
