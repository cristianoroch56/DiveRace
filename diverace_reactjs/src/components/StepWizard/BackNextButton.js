import React, { useEffect } from 'react';
import classNames from 'classnames';
import { useDispatch, useSelector } from "react-redux";
import { useHistory } from "react-router-dom";
import Checkout from "../Checkout";
import PopUpLogin from '../Login/PopUpLogin'
import PopUpRegister from '../Register/PopUpRegister'
import PopUpForgotPassword from '../ForgotPassword/PopUpForgotPassword'
import { setStep } from "../../store/actions/StepWizardAction";

const BackNextButton = (props) => {

    const step = useSelector((state) => state.StepWizardReducer.step);
    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);

    const [loginModal, setLoginModal] = React.useState(false)
    const [registerModal, setRegisterModal] = React.useState(false)
    const [forgotPassordModal, setForgotPassordModal] = React.useState(false)

    const dispatch = useDispatch()


    const handleLogIn = () => {
        setLoginModal(true);
    }
    const closeLoginPopup = () => {
        setLoginModal(false)
    }
    const openRegisterPopup = () => {
        setRegisterModal(true)
    }
    const closeRegisterPopup = () => {
        setRegisterModal(false)
    }
    const openForgotPopup = () => {
        setForgotPassordModal(true)
    }
    const closeForgotPopup = () => {
        setForgotPassordModal(false)
    }

    const checkRoute = () => {
        if (props.next == 5 && !isAuthenticated) {
            setLoginModal(true);
            return false;
        } else {
            dispatch(setStep(props.next))
        }

        dispatch(setStep(props.next))
    }

    return (
        <React.Fragment>

            {/* Login Modal */}
            {loginModal && <PopUpLogin isShow={loginModal} openRegisterPopup={openRegisterPopup} openForgotPopup={openForgotPopup} closeLoginPopup={closeLoginPopup}></PopUpLogin>}
            {/* Login Modal */}
            {registerModal && <PopUpRegister isShow={registerModal} openLoginPopup={handleLogIn} closeRegisterPopup={closeRegisterPopup}></PopUpRegister>}
            {/* Register Modal */}
            {/* Forgot password Modal */}
            {forgotPassordModal && <PopUpForgotPassword isShow={forgotPassordModal} openLoginPopup={handleLogIn} closeForgotPopup={closeForgotPopup}></PopUpForgotPassword>}
            {/* Forgot password Modal */}

            <div className="container" style={{ paddingBottom: 30, display: "block", textAlign: "center", margin: "0 auto" }}>
                <div className="row button-section-wrapper" >

                    <div
                        className=
                        {`${step === 5 ? "btn-step-5 col-md-9 col-9-btn-wrapper" : `btn-step-${step} col-6-btn-wrapper`} ${props.next === 2 ? "mx-auto" : ""}`
                        }
                        className={classNames(
                            {
                              'col-md-6': step !== 2,
                              'col-md-12': step === 2,
                            }
                          )}
                        >

                        <button className={`btn btn-muted btn-lg pull-left`} onClick={() => dispatch(setStep(props.back))} disabled={props.next === 2 ? true : false} style={{ marginRight: 20, backgroundColor: "white", color: "#3396D8" }}>{step == 5 ? 'Back' : 'Back'}</button>
                        {props.back == 4 ? (props.disabled ?  <button className="btn btn-primary btn-lg" onClick={props.onclick}>{props.label}</button> : <Checkout disabled={props.disabled} /> ): <button className={`btn btn-primary btn-lg pull-right`} onClick={() => checkRoute()} disabled={props.disabled}>{props.label}</button>}
                    </div>

                </div>
            </div>

        </React.Fragment>
    );
}

export default BackNextButton;