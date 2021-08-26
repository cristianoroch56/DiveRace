import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, Link, useLocation } from "react-router-dom";
import Select from "react-select";
import Loaderdiv from "./StepWizard/Loader";
import { options, defaults } from '../config/options'
import PopUpLogin from './Login/PopUpLogin'
import PopUpRegister from './Register/PopUpRegister'
import PopUpForgotPassword from './ForgotPassword/PopUpForgotPassword'
import EditProfile from "./Profile/EditProfile"
import ChangePassword from "./Profile/ChangePassword"
import { setStep, setInitialState, setInitialPax, setSummaryInitialState, updateForm } from "../store/actions/StepWizardAction"
import { logoutUser } from "../store/actions/AuthAction";
import classNames from 'classnames';

const Header = () => {

  const redux_state = useSelector((state) => state.StepWizardReducer);
  const isAuthenticated = useSelector((state) => state.user.isAuthenticated);
  const role = useSelector((state) => state.StepWizardReducer.message);
  const step = useSelector((state) => state.StepWizardReducer.step);
  const defaultCurrency = useSelector((state) => state.StepWizardReducer.default_currency);

  const dispatch = useDispatch();
  let history = useHistory();
  const location = useLocation();
  const [currency, setCurrency] = useState('');

  const [isOpenProfile, setIsOpenProfile] = useState(false);


  useEffect(() => {

    
    const defaultCurrencyOption = options.systemCurrency.filter((currency) => currency.value === defaultCurrency)
    setCurrency(defaultCurrencyOption)
  }, [])



  const updateCurrency = (option) => {
    const currencyIcon = option !== null ? option.icon : 'S$';
    const currencyCode = option !== null ? option.value : 'SGD';
    dispatch(updateForm('default_currency', currencyCode))
    dispatch(updateForm('default_currency_code', currencyIcon))
    setCurrency(option)
  }

  const handleBackClick = () => {
    dispatch(setStep(1))
    history.replace('/');

  }

  const customSelectStyles = {
    // For the select it self, not the options of the select
    control: (styles, { isDisabled }) => {
      return {
        ...styles,
        backgroundColor: isDisabled && "#white",
        borderColor: isDisabled ? "#d9d9d9" : "#d9d9d9",
        cursor: "pointer"
      };
    },
    option: (styles, { }) => {
      return {
        ...styles,
        fontSize: 15,
        textAlign: "left",
        cursor: "pointer"
      };
    },
    menuList: (styles, { }) => {
      return {
        ...styles,
        "::-webkit-scrollbar": { width: "2 !important" },
        scrollbarWidth: "none",
        maxHeight: 200,
        overflowY: "auto",
        overflowX: "hidden",
      };
    },
  };

  const navigateToBookedTrip = () => {

    if (isAuthenticated) {
      history.push('/order_details');
    }
  }

  const handleLogout = () => {
    if (isAuthenticated) {

      dispatch(logoutUser());


      // dispatch(setStep(1))

      dispatch(setInitialState());
      dispatch(setSummaryInitialState());
      dispatch(setInitialPax());
      const successObj = {
        alertMssg: 'You Are Successfully Logged Out',
        alertColor: 'success',
        alertShow: true
      }
      //dispatch(setAlertMssg(successObj));
      window.$.toast({
        heading: "Logged Out",
        text: "Successfully Logged Out",
        position: 'bottom-right',
        icon: 'info',
        hideAfter: 2000,
        loaderBg: '#ffffff',
      });
      history.replace('/');
      //return <Redirect to="/"></Redirect>

    }
  }

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

  const closeProfileModal = () => {
    setEditProfileModal(false)
  }

  const closeChangePasswordModal = () => {
    setChangePasswordModal(false)
  }

  const [toggle, setToggle] = React.useState(false)
  const [loginModal, setLoginModal] = React.useState(false)
  const [registerModal, setRegisterModal] = React.useState(false)
  const [forgotPassordModal, setForgotPassordModal] = React.useState(false)
  const [editProfileModal, setEditProfileModal] = React.useState(false)
  const [changePasswordModal, setChangePasswordModal] = React.useState(false)


  return (
    <React.Fragment>
      <div className="container-fluid custom-fullwidth-header-hr main-header-wrapper">
        {/* Login Modal */}
        {loginModal && <PopUpLogin isShow={loginModal} openRegisterPopup={openRegisterPopup} openForgotPopup={openForgotPopup} closeLoginPopup={closeLoginPopup} ></PopUpLogin>}
        {/* Register Modal */}
        {registerModal && <PopUpRegister isShow={registerModal} openLoginPopup={handleLogIn} closeRegisterPopup={closeRegisterPopup}></PopUpRegister>}
        {/* Register Modal */}
        {/* Forgot password Modal */}
        {forgotPassordModal && <PopUpForgotPassword isShow={forgotPassordModal} openLoginPopup={handleLogIn} closeForgotPopup={closeForgotPopup}></PopUpForgotPassword>}
        {/* Forgot password Modal */}
        {/* Edit Profile */}
        {editProfileModal && <EditProfile isShow={editProfileModal} closeModal={closeProfileModal}></EditProfile>}
        {/* Edit Profile */}
        {/* Change password */}
        {changePasswordModal && <ChangePassword isShow={changePasswordModal} closeModal={closeChangePasswordModal}></ChangePassword>}
        {/* Change password */}

        {/* Login Modal */}
        <div className="container">
          {redux_state.showLoader &&
            <div className="parentDisable">
              <div className='overlay-box'>
                <Loaderdiv isShow={redux_state.showLoader}></Loaderdiv>
              </div>
            </div>
          }
          <div className="row">
            <div className="top-header-mobile col-12 col-md-12 d-flex justify-content-center align-items-center">
              <div className="main-site-title">
                <h4>Book Your Trip</h4>
              </div>
            </div>
          </div>
          <div className="top-part row">

            <div className={`top-bar no-gutters ${toggle ? 'm-toogle-show' : ''}`}>
              <div className="col-md-4">
                <div className="pointer">
                  <a href="https://diverace.chillybin.biz/">
                    <img
                      src={process.env.PUBLIC_URL + "/assets/logo@2x.png"}
                      className="/*card-img-top*/ img-responsive"
                      alt="cabins" height="" width="74" title="diverace"
                    />
                  </a>
                </div>
              </div>

              <div className="top-header col-md-4 d-flex justify-content-center align-items-center">
                <div className="main-site-title">
                  <h4>Book Your Trip</h4>
                </div>
              </div>

              <button type="button" onClick={() => setToggle(!toggle)} className="m-toggle-menu btn btn-primary">
                <span className="menu-line"></span>
                <span className="menu-line"></span>
                <span className="menu-line"></span>
              </button>


              <div className={`top-btn col-md-4 ${toggle ? 'mobiletogglemenu' : ''}`} id="topmenubutton">
                <div className="topmenubutton d-flex justify-content-end align-items-center header-menu-btn-wrappper">

                  {step !== 1 || location.pathname == "/order_details" || location.pathname == "/order_summary" ?

                    <div className="" >
                      <button type="button" title="Back to Start" className="btn btn-primary btn-lg btn-back-to-home" onClick={handleBackClick}>
                        Back to Start
                      </button>
                    </div> : (
                      <div className="" >
                        <a href="https://diverace.chillybin.biz/" title="Back to Site" className="btn btn-primary btn-lg btn-back-to-home"> Back </a>
                      </div>
                    )

                  }

                  <div className="ml-3" style={{ width: 100 }}>
                    <Select
                      value={currency}
                      onChange={updateCurrency}
                      options={options.systemCurrency}
                      isSearchable={false}
                      styles={customSelectStyles}
                      components={{ DropdownIndicator: () => null, IndicatorSeparator: () => null }}
                    />
                  </div>

                  {isAuthenticated ?
                    <React.Fragment>
                      {' '}
                      <div className="ml-15" >
                        <button type="button" title="Logout" className="btn btn-primary btn-lg btn-back-to-home d-none" onClick={handleLogout}>
                          Logout
                        </button>

                        <div className="collapse navbar-collapse d-block custom-profile-dropdown">
                          <ul className="navbar-nav">
                            <li className="nav-item dropdown">
                              <a className="nav-link dropdown-toggle user_img" onClick={() => {setIsOpenProfile(!isOpenProfile)}} id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img
                                  src={process.env.PUBLIC_URL + "/assets/user_profile.png"}
                                  className="/*card-img-top*/ img-responsive"
                                  alt="cabins" height="" width="74" title="diverace"
                                />
                              </a>
                              
                    
                              <div
                                className={classNames(
                                  'dropdown-menu',
                                  {
                                    'show_user_toogle': isOpenProfile
                                  }
                                )}
                                aria-labelledby="navbarDropdownMenuLink"
                              >
                                <a className="dropdown-item" onClick={() => setEditProfileModal(true)} style={{ cursor: 'pointer' }}>  <img className="pr-2" width="25px" src={process.env.PUBLIC_URL + "/assets/user.svg"} />Edit Profile</a>
                                <a className={`dropdown-item ${location.pathname == '/order_details' && 'is-active'}`} onClick={navigateToBookedTrip} style={{ cursor: 'pointer' }}><i className="fa fa-ship pr-2" ></i>My Trips</a>
                                <a className="dropdown-item" onClick={() => setChangePasswordModal(true)} style={{ cursor: 'pointer' }}> <img className="pr-2" width="25px" src={process.env.PUBLIC_URL + "/assets/key.svg"} />Change Password</a>
                                <a className="dropdown-item" onClick={handleLogout} style={{ cursor: 'pointer' }}> <img className="pr-2" width="23px" src={process.env.PUBLIC_URL + "/assets/logout.png"} />Log Out</a>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>

                    </React.Fragment>
                    : (
                      <div className="ml-15" >
                        <button type="button" title="Login" className="btn btn-primary btn-lg btn-back-to-home" onClick={handleLogIn}>
                          Login
                            </button>
                      </div>
                    )


                  }
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </React.Fragment>
  );
};

export default Header;
