import React, { useEffect, useState } from "react";
import { useHistory } from "react-router-dom";
import BackNextButton from "./BackNextButton";
import Summary from "../Summary/index";
import PopUpLogin from '../Login/PopUpLogin'
import { useDispatch, useSelector } from "react-redux";
import { updateForm, fetchUserBalance } from "../../store/actions/StepWizardAction"
import PhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/lib/style.css'

const ConfirmPayment = () => {

  //Summary-Selector
  const redux_state = useSelector((state) => state.StepWizardReducer);
  const pax_state = useSelector((state) => state.StepWizardReducer.pax);
  const userdata = useSelector((state) => state.user.profile);
  const isAuthenticated = useSelector((state) => state.user.isAuthenticated);

  const new_courses_price = useSelector((state) => state.StepWizardReducer.new_courses_price);
  const new_equipments_price = useSelector((state) => state.StepWizardReducer.new_equipments_price);

  const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);
  const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);

  const regExpValidEmailID = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  const regExpValidPhoneNumber = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;

  const dispatch = useDispatch();
  let history = useHistory();


  const [loginModal, setLoginModal] = React.useState(false)

  const [values, setValues] = useState({
    paxData: pax_state
  });

  const [isDisabled, setIsDisabled] = useState(true);

  const checkValidation = (field, value) => {
    if (field === 'age') {
      return value > 0 && value < 100 ? false : true;
    } else if (field === 'email') {
      return !regExpValidEmailID.test(value)
    } /* else if (field === 'phone_number') {
      return !regExpValidPhoneNumber.test(value)
    } */

  }

  const autoPopulateLoggedUser = () => {
    let vals_pax = [...values.paxData];

    vals_pax[0]['name'] = userdata.display_name;
    vals_pax[0]['email'] = userdata.user_email;

    setValues({ paxData: vals_pax });
    dispatch(updateForm('pax', values.paxData));
  }

  const handleInputChange = (props, val) => {

    let field = props.field;
    let validfield = props.validfield;
    let vals_pax = [...values.paxData];
    let index = props.index;

    //check valid field
    if (val) {
      var isValid = checkValidation(field, val)
    } else {
      var isValid = false;
    }

    vals_pax[index][field] = val;
    vals_pax[index][validfield] = isValid;

    setValues({ paxData: vals_pax });
    dispatch(updateForm('pax', values.paxData));
  }

  const handleError = () => {
    window.$.toast({
      heading: "Validation Error",
      text: "Please provide pax details",
      position: 'top-right',
      icon: 'error',
      hideAfter: 5000,
      loaderBg: '#ffffff',
    });
  }

  const priceSpan = (price) => {
    let changedPrice;
    if (default_currency === 'THB') {
      changedPrice = price * 23.46;
    } else if (default_currency === 'USD') {
      changedPrice = price * 0.75;
    } else {
      changedPrice = price;
    }
    return Math.round(changedPrice);
  }

  useEffect(() => {
    let vals_pax = [...values.paxData];

    for (let i = 0; i < vals_pax.length; i++) {

      if (!vals_pax[i]['name'] || !vals_pax[i]['email'] || !vals_pax[i]['phone_number'] || !regExpValidEmailID.test(vals_pax[i]['email'])) {
        setIsDisabled(true)
        break;
      } else {
        setIsDisabled(false)
      }
    }

  }, [values])



  useEffect(() => {

    window.scrollTo(0, 0);
    if (!isAuthenticated) {
      history.replace('/login');
    }


    if (userdata && userdata.ID) {

      dispatch(fetchUserBalance(Number(userdata.ID)))
      const final_user_price = priceSpan(redux_state.payble_amount) + priceSpan(new_courses_price) + priceSpan(new_equipments_price);
      dispatch(updateForm('final_payble_amount', Number(final_user_price)))
      dispatch(updateForm('total_cost_amount', Number(final_user_price)))



      // if logged user is not agent then fill prson one details
      if (redux_state.user_role && redux_state.user_role == 'subscriber') {
        autoPopulateLoggedUser();
      }

      if (userdata && Number(userdata.user_credit) > 0) {

        if (Number(userdata.user_credit) >= Number(redux_state.payble_amount)) {

          var user_credit = redux_state.payble_amount;
          var diduct_amount = 0.00;

          dispatch(updateForm('final_total_amount', Number(user_credit)));

        } else {

          var user_credit = userdata.user_credit;
          var diduct_amount = Number(redux_state.payble_amount) - Number(userdata.user_credit);
        }

        dispatch(updateForm('user_credit', Number(user_credit)));
        dispatch(updateForm('final_payble_amount', Number(diduct_amount)));
        dispatch(updateForm('total_cost_amount', Number(diduct_amount)))

      }

    }


    // let courses_price = redux_state.step3_price+redux_state.step4_price
    // dispatch(updateAmount(courses_price))
  }, []);

  return (
    <React.Fragment>
      <div id="step-5" className="container content-center step-main-wrapper" style={{ display: 'block' }}>

        {/* Login Modal */}
        {loginModal && <PopUpLogin isShow={loginModal}></PopUpLogin>}
        {/* Login Modal */}

        <div className="row">
          <div className="col-md-12 text-center">
            <h2 className="heading pb-4 overview-heading">Overview</h2>
          </div>
          <div className="body-left col-md-7">
            <div className="row">


              <div className="col-sm-12 col-md-11">
                <div className="row">
                  <div className="area-left border-rd main-details-section">
                    <div className="box-heading">
                      <h5 className="detail-title">Reminder </h5>
                    </div>
                    <hr className="hr-line"></hr>
                    <div className="box col-sm-12 col-md-12">
                      <div className="box-content-desc">
                        <p>
                          Contrary to popular belief, Lorem Ipsum is not simply random
                          text. It has roots in a piece of classNameical Latin
                          literature from 45 BC, making it over 2000 years old.
                          Richard McClintock, a Latin professor at Hampden-Sydney
                          College in Virginia, looked up one of the more obscure Latin
                          words, consectetur,
                      </p>
                      </div>
                    </div>

                    <div className="box-heading col-md-12 member-area member-details-area">
                      <div className=" row">
                        <h5 className="col-md-12 member-title" style={{ fontWeight: 700 }}>
                          Member Details
                      </h5>
                      </div>
                      <div className="box row">
                        <div className="col-12 col-sm-6 col-md-6 over-text">
                          <h6 className="custom-title">
                            {" "}
                            <span style={{ color: "#3396d8" }}>Full Name: </span>
                            {(userdata && userdata.display_name) ? userdata.display_name : 'No USER FOUND'}

                          </h6>
                        </div>

                        <div className="col-12 col-sm-4 col-md-4 over-text total-people-info">
                          <h6 className="custom-title">
                            {" "}
                            <span style={{ color: "#3396d8" }}>Total People: </span> {redux_state.pax.length}
                          </h6>
                        </div>
                      </div>
                      <div className="box row">
                        <div className="col-12 col-sm-6 col-md-6 over-text">
                          <h6 className="custom-title">
                            {" "}
                            <span style={{ color: "#3396d8" }}>Email: </span>{" "}
                            {(userdata && userdata.user_email) ? userdata.user_email : 'No USER EMAIL FOUND'}

                          </h6>
                        </div>

                      </div>
                    </div>

                    <div className="box-heading col-md-12 member-area all-people-details">
                      <div className="row">
                        <h5 className="col-md-12 member-title" style={{ fontWeight: 700 }}>
                          Enter below details of {redux_state.pax.length} guest(s)
                      </h5>
                      </div>
                      <form className="common-form passenger-details" id="passenger-details">
                        {/* Pax Details  */}
                        <div className="row">
                          {
                            redux_state.pax.map((pss, i) => {
                              return (
                                <React.Fragment key={i}>

                                  <label className="custom-title" style={{ display: 'block', width: '100%', color: "#3396d8", paddingLeft: 15 }}>Person {i + 1} ({pss.type == "solo" ? "Solo Spot" : "2 pax cabin"}, {pss.gender.map((val, k) => { return (k ? ', ' : '') + (val == 'male' ? 'Male' : 'Female') })})</label>
                                  <div className="col-12 col-sm-6 col-md-6">
                                    <div className="form-group">
                                      <input type="text" autoComplete="name__field" data-index={i} value={pss.name} data-field="name" className="form-control" placeholder="Name" onChange={(e) => handleInputChange({ index: i, field: 'name', validfield: '' }, e.target.value)} required />
                                    </div>
                                  </div>
                                  <div className="col-12 col-sm-6 col-md-6">
                                    <div className="form-group">
                                      <input type="email" autoComplete="email__field" data-index={i} value={pss.email} data-field="email" data-validfield="emailValid" className="form-control" placeholder="Email" onChange={(e) => handleInputChange({ index: i, field: 'email', validfield: 'emailValid' }, e.target.value)} required />
                                      {pss.emailValid && <p className="text-danger">Invalid email</p>}
                                    </div>
                                  </div>
                                  <div className="col-12 col-sm-6 col-md-6">
                                    <div className="form-group">
                                      <PhoneInput
                                        country={'sg'}
                                        value={pss.phone_number}
                                        onChange={phone => handleInputChange({ index: i, field: 'phone_number', validfield: 'phoneValid' }, phone)}
                                        className="form-control"
                                      />
                                      {/* <input type="number" data-index={i} maxLength="12" value={pss.phone_number} data-field="phone_number" data-validfield="phoneValid" className="form-control" placeholder="Phone number" onChange={(e) => handleInputChange({index:i, field: 'phone_number',validfield:'phoneValid'}, e.target.value)} required /> */}
                                      {pss.phoneValid && <p className="text-danger">Invalid Phone Number</p>}

                                    </div>
                                  </div>
                                  {redux_state.user_role && redux_state.user_role == 'agent' && <div className="col-12 col-sm-2 col-md-2" >
                                    <div className="form-group">
                                      <input type="number" data-index={i} value={pss.age} data-field="age" data-validfield="ageValid" minLength="1" maxLength="2" className="form-control" placeholder="Age" onChange={(e) => handleInputChange({ index: i, field: 'age', validfield: 'ageValid' }, e.target.value)} required />
                                      {pss.ageValid && <p className="text-danger">Invalid Age</p>}

                                    </div>
                                  </div>}



                                </React.Fragment>
                              )
                            })
                          }
                        </div>


                        {/* Pax Details  */}
                      </form>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          {/* Summary */}
          {/* summary-wrappper */}
          <Summary step={5}></Summary>
          {/* Summary */}

        </div>
      </div>
      <div
        className="bottom-text pt-4"
        style={{
          display: "block",
          margin: "inherit",
          textAlign: "center",
        }}
      ></div>



      <BackNextButton disabled={isDisabled} onclick={handleError} back={4} label={`Proceed to Payment`}></BackNextButton>

    </React.Fragment>
  );
};

export default ConfirmPayment;
