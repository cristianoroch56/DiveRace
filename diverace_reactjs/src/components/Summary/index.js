import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import moment from "moment";
import {
  applyCouponCode,
  appliedCouponCodeFailed,
  updateForm,
  appliedCouponCodeStatus,
  setCouponCode,
  setAgentCode,
  applyAgentCode,
  appliedAgentCodeStatus,
  appliedAgentCodeFailed
} from "../../store/actions/StepWizardAction";


const Summary = (props) => {


  const today = moment().format("DD-MM-YYYY");


  const summaryData = useSelector((state) => state.SummaryReducer);
  const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);
  const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);
  const formData = useSelector((state) => state.StepWizardReducer);
  const person_list = useSelector((state) => state.StepWizardReducer.pax);
  const userLoginData = useSelector((state) => state.user);
  const final_payment_amount = useSelector((state) => state.StepWizardReducer.final_payble_amount);
  const dispatch = useDispatch();

  const [code, setCode] = useState(formData.coupon_code)
  const [codeAgent, setcodeAgent] = useState(formData.agent_code)


  const [partialAmountCheckBoxShow, setPartialAmountCheckBoxShow] = useState({
    'TEN_PERCENTAGE': false,
    'FIFTY_PERCENTAGE': false,
    'HUNDRED_PERCENTAGE': false,
  })

  const TRIP_DAYS_CONSTANT = {
    'SIX_MOUNTH': 180,
    'EIGHT_WEEKS': 56,
    'SIX_WEEKS': 42
  }


  const updateCourseRentalPrice = () => {

    dispatch(updateForm('new_courses_price', 0));
    const TOTAL_COURSE_PRICE = person_list.reduce((prev, next) => prev + Number(next.course_price), 0);

    dispatch(updateForm('new_courses_price', TOTAL_COURSE_PRICE));

    dispatch(updateForm('new_equipments_price', 0));
    var rentals_price = 0;
    for (let index = 0; index < person_list.length; index++) {
      if (person_list[index].equipments.length > 0) {
        var equipments_array = person_list[index].equipments;
        var total_price = equipments_array.reduce((prev, next) => prev + Number(next.price), 0);
        var rentals_price = rentals_price + total_price;
      }
    }
    dispatch(updateForm('new_equipments_price',rentals_price));

    const final_user_price = priceSpan(formData.payble_amount) + priceSpan(TOTAL_COURSE_PRICE) + priceSpan(rentals_price);
    dispatch(updateForm('final_payble_amount', final_user_price))
    dispatch(updateForm('total_cost_amount', final_user_price))
  }

  useEffect(() => {
    window.scrollTo(0, 0);
    setCode('')
    dispatch(setCouponCode(''))
    dispatch(appliedCouponCodeFailed(''))
    dispatch(appliedCouponCodeStatus(0))


    dispatch(updateForm('coupon_id', 0))
    dispatch(updateForm('discount_amount', 0))

    dispatch(updateForm('partial_amount_type', 100))
    dispatch(updateForm('partial_amount', 0))

    finDays();

  }, [default_currency]);

  useEffect(() => {
    updateCourseRentalPrice();
  }, [person_list, default_currency])

  //FIND DAYS BEWEEN TRIP DATE
  const finDays = () => {
    const TRIP_START = formData.tripDate;
    var startDate = moment(new Date(), 'YYYY-MM-DD');
    var endDate = moment(TRIP_START, 'YYYY-MM-DD');
    let days = endDate.diff(startDate, 'days')
    if (TRIP_DAYS_CONSTANT.SIX_MOUNTH <= days) {
      setPartialAmountCheckBoxShow({
        ...partialAmountCheckBoxShow,
        'TEN_PERCENTAGE': true
      })
    } else if (TRIP_DAYS_CONSTANT.EIGHT_WEEKS <= days) {
      setPartialAmountCheckBoxShow({
        ...partialAmountCheckBoxShow,
        'FIFTY_PERCENTAGE': true
      })
    } else {
      setPartialAmountCheckBoxShow({
        ...partialAmountCheckBoxShow,
        'HUNDRED_PERCENTAGE': true
      })
    }
  }
  //discount code
  const handleChange = (e) => {
    dispatch(appliedCouponCodeFailed(''))
    dispatch(appliedCouponCodeStatus(0))
    dispatch(updateForm('discount_amount', 0))
    // dispatch(updateForm('final_payble_amount',0))

    dispatch(setCouponCode(e.target.value,))
    setCode(e.target.value);
  };

  //discount code 
  const handleSubmit = (e) => {
    e.preventDefault();
    let userId = Number(userLoginData.profile.ID);
    dispatch(applyCouponCode(code, userId))
  }

  //agent code
  const handleAgentChange = (e) => {
    dispatch(appliedAgentCodeFailed(''))
    dispatch(appliedAgentCodeStatus(0))


    dispatch(setAgentCode(e.target.value))
    setcodeAgent(e.target.value);
  };

  //agent code
  const handleAgentSubmit = (e) => {
    e.preventDefault();
    let userId = Number(userLoginData.profile.ID);
    dispatch(applyAgentCode(codeAgent, userId))
  }

  //set partial amount
  const partialAmount = (percentage) => {
    const partial_deduct_amount = Number(formData.final_payble_amount) * Number(percentage) / 100;
    return String(partial_deduct_amount).replace(/(.)(?=(\d{3})+$)/g, '$1,')
  }

  //partial amount
  const changePartialAmount = (percentage) => {
    const partial_amount = Number(formData.final_payble_amount) * Number(percentage) / 100;
    dispatch(updateForm('partial_amount_type', percentage))
    dispatch(updateForm('partial_amount', Number(partial_amount)))
    //dispatch(updateForm('final_payble_amount', Number(partial_amount)))
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

  const PriceSpanUI = ({ price }) => {
    let changedPrice;
    if (default_currency === 'THB') {
      changedPrice = price * 23.46;
    } else if (default_currency === 'USD') {
      changedPrice = price * 0.75;
    } else {
      changedPrice = price;
    }
    return (
      <span>{defaultCurrencyCode} {Math.round(changedPrice)}</span>
    )
  }

  const showStepFourPrice = () => {


    const totalPrice = priceSpan(formData.payble_amount) + priceSpan(formData.new_courses_price) + priceSpan(formData.new_equipments_price)
    return totalPrice;
  }

  const summaryUIEquipment = (id) => {
    const str = `<i class="fa fa-arrow-right" aria-hidden="true"></i><span style={{ color: '#0000'}}> Person${id} </span><button type="button" class="btn btn-sm text-danger">Remove</button>`
    return (
      <>
        <div dangerouslySetInnerHTML={{ __html: str }} />
      </>
    )
  }

  const summaryRentalRemove = () => {
    const str = `<a className="" href="#" onClick={undefined}  >
    <img
        src=${process.env.PUBLIC_URL}/assets/crose-icon.svg
        alt="Remove Icon"
        width="12px"
        className=""
    />
    </a>`
    return (
      <>
        <div dangerouslySetInnerHTML={{ __html: str }} />
      </>
    )
  }

  const removeCourse = (personID) => {

    const local_pax = [...person_list];
    const course_object = { course_id: 0, course_title: '', course_price: 0 };
    const updated_pax = local_pax.map(pax => pax.ID == personID ? ({ ...pax, ...course_object }) : (pax));
    dispatch(updateForm('pax', [...updated_pax]));

  }

  const removeRental = (personID, rentalId) => {
    const local_pax = [...person_list];

    const findPerson = local_pax.find((pax) => {
      return pax.ID == personID;
    });
    if (findPerson && findPerson.equipments != undefined && findPerson.equipments.length > 0) {
      const filteredEquipments = findPerson.equipments.filter(p => p.id != rentalId);
      const rental_object = { equipments: filteredEquipments };
      const updated_pax = local_pax.map(pax => pax.ID == personID ? ({ ...pax, ...rental_object }) : (pax));
      dispatch(updateForm('pax', [...updated_pax]));
    }


  }

  const removePaxAddons = (personID) => {

    const local_pax = [...person_list];
    const course_rental_object = { course_id: 0, course_title: '', course_price: 0, equipments: [] };
    const updated_pax = local_pax.map(pax => pax.ID == personID ? ({ ...pax, ...course_rental_object }) : (pax));
    dispatch(updateForm('pax', [...updated_pax]));

  }


  return (
    <React.Fragment>
      <div className="body-right col-md-5 col-summarydetails-section">

        <div className="area-right cust-box-shadow summary-details-wrapper">
          <div className="box-heading">
            <h5 style={{ fontWeight: 700 }}>Summary</h5>
          </div>


          <hr className="hr-line summary-hr-line"></hr>

          {/* Step-1 */}
          <div className="box col-md-12 box-step1" style={{ display: props.step > 1 ? '' : 'none' }}>
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{ backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})` }}>
                1
                  </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Vessel</h6>
              <span className="summary-text-info">{summaryData.vessel_type ? summaryData.vessel_type : "No vessel selected"}</span>
            </div>
            <div className="box-check col-2 col-md-2">
              <img
                src={process.env.PUBLIC_URL + "/assets/icon-tick@2x.png"}
                className="check-uncheck-icon"
                alt="check"
              />
            </div>
          </div>

          {/* Step-2 */}
          <div className="box box-color col-md-12 box-step2" style={{ display: props.step > 1 ? '' : 'none' }}>
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{ backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})` }}>
                2
                  </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Trip</h6>

              <span className="summary-text-info">
                {`${summaryData.tripDate_type ? summaryData.tripDate_type : "No date selected"}, `}<br />
                {`${summaryData.itineraryArea_type ? summaryData.itineraryArea_type : "No Itinerary area selected"}`} {summaryData.itineraryArea_type  && <PriceSpanUI price={formData.payble_amount} />}
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <img
                src={process.env.PUBLIC_URL + "/assets/icon-tick@2x.png"}
                className="check-uncheck-icon"
                alt="check"
              />
            </div>
          </div>

          {/* Step-3 */}
          <div className="box box-area col-md-12 box-step3" style={{ display: props.step > 2 ? '' : 'none' }}>
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{ backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})` }}>
                3
                  </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Cabins</h6>
              <span className="summary-text-info">
                {summaryData.passenger} Pax
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <img
                src={process.env.PUBLIC_URL + "/assets/icon-tick@2x.png"}
                className="check-uncheck-icon"
                alt="check"
              />
            </div>
          </div>

          {/* Step-4 */}
          <div className="box box-color col-md-12 box-step4" style={{ display: props.step > 3 ? '' : 'none' }}>
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{ backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})` }}>
                4
                  </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of add ons</h6>
              <span className="summary-text-info">

                {person_list.filter((p_filter) => p_filter.course_id > 0 || p_filter.equipments.length > 0)?.map((p_obj, i) => {

                  return (
                    <React.Fragment key={i}>
                      <span ><i className="fa fa-arrow-right" aria-hidden="true"></i> Person{p_obj.ID}</span>  <button type="button" class="btn btn-sm text-danger" title="remove" onClick={() => removePaxAddons(p_obj.ID)}>Remove</button> <br />
                      {p_obj.course_id > 0 &&
                        (<><span>{`${p_obj.course_title} `}<PriceSpanUI price={p_obj.course_price}/> </span><span className="course__remove" title="remove" onClick={() => removeCourse(p_obj.ID)}  >
                          <img
                            src={process.env.PUBLIC_URL + "/assets/crose-icon.svg"}
                            alt="Remove Icon"
                            width="12px"
                            className=""
                          />
                        </span><br /></>)}
                      {p_obj.equipments.length > 0 && (
                        p_obj.equipments.filter((eq_filter) => eq_filter.id > 0)?.map((eq_obj, i) => {
                          return (
                            <React.Fragment key={`equ_${i}`}>
                              <span> {`${eq_obj.title} ${eq_obj.size ? (` (${eq_obj.size})`) : ''} `}<PriceSpanUI price={eq_obj.price}/></span><span className="rental__remove" title="remove" onClick={() => removeRental(p_obj.ID, eq_obj.id)}  >
                                <img
                                  src={process.env.PUBLIC_URL + "/assets/crose-icon.svg"}
                                  alt="Remove Icon"
                                  width="12px"
                                  className=""
                                />
                              </span><br />
                            </React.Fragment>
                          )
                        })
                      )}
                      <br />
                    </React.Fragment>
                  )
                })}<br />

                {" "}
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <img
                src={process.env.PUBLIC_URL + "/assets/icon-tick@2x.png"}
                className="check-uncheck-icon"
                alt="check"
              />
            </div>
          </div>

          <div className="box box-area col-md-12 box-step5" style={{ display: props.step == 4 ? '' : 'none' }}>
            <div className="text col-3 col-md-7">
              <h6>Total Cost:</h6>
            </div>
            <div className="col-9 col-md-5">
              <h4 className="font-w-500 wb-all" style={{ color: "#3396d8" }}> {defaultCurrencyCode} {formData.payble_amount > 0.00 ? String(showStepFourPrice()).replace(/(.)(?=(\d{3})+$)/g, '$1,')  : 0}</h4>
            </div>
          </div>

          {/* Step-5 */}

          <div style={{ display: props.step > 4 ? '' : 'none' }}>


            <div className="box box-area col-md-12">
              <form
                onSubmit={handleSubmit}
                className="apply-coupon-code"
                style={{ width: "100%" }}
              >
                <div className="custom-form-control">
                  <div className="ginput_container ginput_container_text">
                    <input
                      type="text"
                      id="coupon-code"
                      className="cls cls-border col-md-8 border-rd discount-code-input form-control"
                      placeholder="Discount or Referral or Agent Code"
                      name="search"
                      value={code}
                      onChange={handleChange}
                      readOnly={(userLoginData.profile && userLoginData.profile.user_credit !== undefined && Number(userLoginData.profile.user_credit) >= Number(formData.final_payble_amount)) || summaryData.coupon_code_status == 1 ? true : false}
                      required
                    />
                    <button
                      style={{ backgroundColor: '#4596d8' }}
                      type="submit"
                      className="btnSubmit btn btn-primary btn-lg btn-coupon-apply col-md-4"
                      disabled={!code || summaryData.coupon_code_status == 1 ? true : false}
                    >
                      Apply Now
                           {/* <span className="spinner-border spinner-border-sm"></span> */}
                    </button>
                  </div>
                </div>
              </form>

            </div>
            <div style={{ textAlign: 'center' }}>
              {summaryData.coupon_code_err_message ? <span style={{ color: `${summaryData.coupon_code_status == 1 ? "green" : "red"}` }}>{summaryData.coupon_code_err_message}</span> : ''}
            </div>
            <hr className="hr2" />

            <div className="box box-area col-md-12 rm-p-tb">
              <div className="text col-3 col-md-7">
                <h6 className="amount-title">Total Cost:</h6>
              </div>
              <div className="col-9 col-md-5">
                <span>
                  <h4 className="font-w-500 wb-all" style={{ color: "#3396d8" }}> {defaultCurrencyCode} {String(formData.total_cost_amount).replace(/(.)(?=(\d{3})+$)/g, '$1,')} </h4>
                </span>

                {/* <h6>{`($S ${formData.cabin_price} x ${summaryData.passenger}) ${formData.step4_price > 0.00 ? `+ $S ${formData.total_course_price} x ${summaryData.passenger}(course)` : ''}`}</h6> */}
              </div>
            </div>
            {/* Discount */}
            <div className="box box-area col-md-12 rm-p-tb" style={{ display: `${(formData.discount_amount || formData.agent_discount_amount || (userLoginData.profile && userLoginData.profile.user_credit)) > 0 ? " " : "none"}` }}>
              <div className="text col-3 col-md-7">
                <h6 className="amount-title">Discount:</h6>

              </div>
              <div className="col-9 col-md-5" >
                {userLoginData.profile &&
                  <p style={{ display: `${Number(userLoginData.profile.user_credit && userLoginData.profile.user_credit !== undefined && userLoginData.profile.user_credit) > 0 ? " " : "none"}` }}>-Credit {`S$ ${formData.user_credit}`}</p>
                }

                <p className="amout-text" style={{ display: `${formData.discount_amount > 0 ? " " : "none"}` }}>-{`${formData.default_currency_code} ${String(formData.discount_amount).replace(/(.)(?=(\d{3})+$)/g, '$1,')} `} <span style={{ color: '#9DA0A3' }} >{`(${summaryData.coupon_code_percentage}%)`}</span> Coupon Code </p>
              </div>

            </div>
            {/* Discount */}
            <div className="box col-md-12 rm-p-tb" style={{ display: `${(formData.discount_amount || formData.agent_discount_amount || (userLoginData.profile && userLoginData.profile.user_credit && userLoginData.profile.user_credit !== undefined && userLoginData.profile.user_credit)) > 0 ? " " : "none"}` }}>
              <div className="col-3 text col-md-7">
                <h6 className="amount-title">Total Cost:</h6>
              </div>
              <div className="col-9 col-md-5">
                <span>
                  <h4 className="font-w-500 wb-all" style={{ color: "#3396d8" }}> {defaultCurrencyCode} {String(formData.final_payble_amount).replace(/(.)(?=(\d{3})+$)/g, '$1,')} </h4>
                </span>
              </div>
            </div>
          </div>

        </div>

        {final_payment_amount > 0 &&
          <div style={{ display: props.step > 4 ? '' : 'none' }}>
            <div className="area-box text-center">
              <div className="col-md-12">
                <h5> PAYMENT INSTALMENTS</h5>
              </div>
            </div>

            <div className="area-right cust-box-shadow">
              {partialAmountCheckBoxShow.TEN_PERCENTAGE &&
                <div className="box col-md-12 extrat-pedding-top">
                  <div className="text col-7 col-md-7">
                    <h5>
                      {" "}
                      <span
                        style={{
                          color: "#3396d8",
                          fontWeight: 700,
                        }}
                      >
                        10%
                </span>{" "}
                (Pay now)
              </h5>
                  </div>
                  <div className="col-5 col-md-5">
                    <span>

                      <label className="filter-payment-radio"> <h4 style={{ color: "#3396d8" }}> {formData.default_currency_code} {partialAmount(10)} </h4>
                        <input type="radio" name="radio" checked={formData.partial_amount_type == 10} value="{partialAmount(10)}" onClick={() => changePartialAmount(10)} />
                        <span className="checkmark"></span>
                      </label>

                    </span>

                  </div>
                </div>
              }

              {partialAmountCheckBoxShow.FIFTY_PERCENTAGE &&
                <div className="box col-md-12">
                  <div className="text col-md-7">
                    <h5>
                      {" "}
                      <span
                        style={{
                          color: "#3396d8",
                          fontWeight: 700,
                        }}
                      >
                        50%
                 </span>{" "}
                 (Pay now)
               </h5>
                  </div>
                  <div className="col-5 col-md-5">
                    <span>
                      <label className="filter-payment-radio"> <h4 style={{ color: "#3396d8" }}> {formData.default_currency_code} {partialAmount(50)} </h4>
                        <input type="radio" name="radio" checked={formData.partial_amount_type == 50} value="{partialAmount(50)}" onClick={() => changePartialAmount(50)} />
                        <span className="checkmark"></span>
                      </label>
                    </span>
                  </div>
                </div>
              }


              <div className="box col-md-12">
                <div className="text col-md-7">
                  <h5>
                    {" "}
                    <span
                      style={{
                        color: "#3396d8",
                        fontWeight: 700,
                      }}
                    >
                      100%
                 </span>{" "}
                 (Pay now)
               </h5>
                  <small>Note: Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature.</small>
                </div>
                <div className="col-5 col-md-5">
                  <span>
                    <label className="filter-payment-radio"> <h4 style={{ color: "#3396d8" }}> {formData.default_currency_code} {partialAmount(100)} </h4>
                      <input type="radio" name="radio" checked={formData.partial_amount_type == 100} value="{partialAmount(100)}" onClick={() => changePartialAmount(100)} />
                      <span className="checkmark"></span>
                    </label>
                  </span>
                  <small className="ml-2">({today})</small>
                </div>

              </div>


            </div>
          </div>
        }

      </div>
    </React.Fragment>
  );
};

export default Summary;
