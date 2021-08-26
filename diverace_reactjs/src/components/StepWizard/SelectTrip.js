import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { updateForm, updateSummary, updateAmount, fetchCountries, setTripDateData, fetchItineraryAreaByCountry, fetchTripDatesByItinerary } from "../../store/actions/StepWizardAction";
import BackNextButton from "./BackNextButton";
import Summary from "../Summary/index";
import PopupTripDates from "./PopupTripDates";
import LoadingLoader from '../LoadingLoader'

const SelectTrip = () => {

  const stateTrip = useSelector((state) => state.StepWizardReducer);

  //listing-selector 
  const country_list = useSelector((state) => state.StepWizardReducer.country_list);
  const itineraryArea_list = useSelector((state) => state.StepWizardReducer.itineraryArea_list);
  const tripDate_list = useSelector((state) => state.StepWizardReducer.tripDate_list);


  //Id-selector
  const countryId = useSelector((state) => state.StepWizardReducer.country_type);
  const itineraryAreaId = useSelector((state) => state.StepWizardReducer.itineraryArea_type);
  const tripDateId = useSelector((state) => state.StepWizardReducer.tripDate_type);

  const dispatch = useDispatch();

  //local State
  const [countryType, setCountryType] = useState(countryId);
  const [itineraryAreaType, setItineraryAreaType] = useState(itineraryAreaId);
  const [dateType, setDateType] = useState(tripDateId);
  const [showPopup, setShowPopup] = useState(false);

  //Show-PopUp
  const togglePopup = () => {
    setShowPopup(!showPopup)
  }

  //Update-Country
  const changeCountryField = (event) => {

    event.preventDefault();

    let countryDataType = event.currentTarget.getAttribute("data-country");
    let countryDataTitle = event.currentTarget.getAttribute("data-countrytitle");
    let stateType = event.currentTarget.getAttribute("data-statetype");

    dispatch(updateForm(stateType, Number(countryDataType)));
    dispatch(updateSummary(stateType, countryDataTitle));
    setCountryType(Number(countryDataType));

    dispatch(setTripDateData([]))
    dispatch(updateForm('tripDate_type', 0))
    dispatch(updateSummary('tripDate_type', ''))
    dispatch(updateForm('itineraryArea_type', 0));
    dispatch(updateSummary('itineraryArea_type', ''));
    dispatch(fetchItineraryAreaByCountry(Number(countryDataType)))

  };

  //Update-Itinerary-Area
  const changeAreaField = (event) => {

    let areaType = event.currentTarget.getAttribute("data-area");
    let areaTitle = event.currentTarget.getAttribute("data-areatitle");
    let stateType = "itineraryArea_type";

    dispatch(updateForm(stateType, Number(areaType)));
    dispatch(updateSummary(stateType, areaTitle));
    setItineraryAreaType(Number(areaType));
    dispatch(updateForm('tripDate_type', 0));
    dispatch(updateSummary('tripDate_type', ''));
    dispatch(fetchTripDatesByItinerary(Number(areaType)))
  }

  //Update-Trip
  const changeTripDateField = (event) => {

    let tripDateType = event.currentTarget.getAttribute("data-tripdate");
    let tripDateTitle = event.currentTarget.getAttribute("data-tripdatetitle");
    let tripDatePrice = event.currentTarget.getAttribute("data-tripdateprice");
    let stateType = "tripDate_type";

    let tripstartdate = event.currentTarget.getAttribute("data-tripstartdate");
    dispatch(updateForm('tripDate', tripstartdate));

    dispatch(updateForm(stateType, Number(tripDateType)));
    dispatch(updateSummary(stateType, tripDateTitle));

    // dispatch(updateAmount(tripDatePrice));
    setDateType(Number(tripDateType));
  }


  useEffect(() => {
    window.scrollTo(0, 0);
    dispatch(fetchCountries());
    // if (tripDate_list.length === 0) {
    // dispatch(asynCountryItineraryTripDate(Number(countryType) , Number(itineraryAreaType),  Number(dateType) ))
    // }
  }, []);


  return (
    <React.Fragment>

      <div id="step-2" className="container content-center step-main-wrapper" style={{ display: 'block' }}>
        <div className="row">
          <div className="body-left col-md-7">

            <div className="select-area rm-pl-0">
              <h4 className="heading select-country-title">Select Country</h4>
              <LoadingLoader isShow="false"></LoadingLoader>
              <section className="p-2 row">
                {/* <div className="btn-group" data-toggle="buttons"> */}
                <div className="col-md-12 country-wrapper">
                  {country_list.length === 0 ? (<div className="text-center w-100">NO RECORD FOUND</div>) : country_list.map((c_obj, c) => {
                    return (
                      <div key={c} className={`single-country cls ${c_obj.id == countryId ? "cls-border" : "border-rd"} col-md-5`}
                        data-country={c_obj.id}
                        data-countrytitle={c_obj.title}
                        data-statetype={`country_type`}
                        onClick={changeCountryField}
                      >
                        <img
                          src={c_obj.country_img ? c_obj.country_img : process.env.PUBLIC_URL + "/assets/malaysia.png"}
                          alt="malasiya"
                          className="country-icon img-fluid"
                        /* width={30} */
                        />{" "}
                        <span className="country-title ellipsis-title">{c_obj.title}</span>
                      </div>
                    )
                  })}

                </div>
                {/* </div> */}
              </section>
            </div>

            {/* {itineraryArea_list.length !== 0 && */}
            <div className="select-area rm-pl-0">
              <h4 className="heading select-itinerary-title">Select Itinerary Area</h4>
              <LoadingLoader isShow="false"></LoadingLoader>
              <section className="p-2 row">
             
                {/* <div className="btn-group" data-toggle="buttons"> */}

                {/* <div className="col-md-11 itinerary-wrapper" style="margin-left: 15px;padding-right: 50px;">
                  <div className="row">
                    <div className="col-md-12">
                      <div className="text-center bg-white-section">
                        NO RECORD FOUND
                      </div>
                    </div>
                  </div>
                </div> */}

                <div className="col-md-12 itinerary-wrapper">
                  {itineraryArea_list.length === 0 ? (<div className="row">
                    <div className="col-md-11"><div className="text-center bg-white-section">NO RECORD FOUND</div></div>
                  </div>) : itineraryArea_list.map((area_obj, i) => {
                    return (
                      <div key={i} className={`single-itinerary cls ${area_obj.id == itineraryAreaId ? "cls-border" : "border-rd"} col-md-5`}
                        data-area={area_obj.id}
                        data-areatitle={area_obj.title}
                        onClick={changeAreaField}>
                        <img
                          src={process.env.PUBLIC_URL + "/assets/timeline-icon-ship@2x.png"}
                          alt="Itinerary Icon"
                          width={30}
                          className="itinerary-icon"
                        />
                        <span className="itinerary-title ellipsis-title">{area_obj.title}</span>
                      </div>
                    )
                  })}
                </div>

                {/* </div> */}
              </section>
            </div>
            {/* } */}

            {/* { tripDate_list.length !== 0 && */}
            <div className="select-area main-date-wrapper">
              <div className="row row-datetitle-wrapper">
                <div className="col-md-12">
                  <div className="row">
                    <div className="col-12 col-sm-6 col-md-7">
                      <h4 className="heading select-date-title">Select Date </h4>
                    </div>
                    <div className="col-12 col-sm-6 col-md-5 col-pad-rm">
                      <div className="select-date-trip">
                        <div className="date-link-trip">
                          <span className="summary-text-info" onClick={togglePopup} style={{ cursor: "pointer" }}>See all Trips</span>
                          {/* <a href="#">See all Trips</a> */}
                          <PopupTripDates showPopupState={showPopup} dateTypePopup={dateType} updateTripDate={changeTripDateField} closePopup={togglePopup} />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <section className="border row row-datedetails-wrapper">
                {/* <div className="row row-datedetails-wrapper">*/}
                <div className="col-md-11">
                  <LoadingLoader isShow="false"></LoadingLoader>
                  <div className="date-area border-rd date-wrapper-section flex-trip">

                    {tripDate_list.length === 0 ? (<div className="text-center w-100 not-found-data">NO RECORD FOUND</div>) : tripDate_list.slice(0, 10).map((trip_obj, t) => {

                      return (
                        <div key={t} className="border-right col-12 col-sm-6 col-md-6">
                          <div className={`date-box ${trip_obj.id === tripDateId ? "cls-border avoid-clicks" : null} pointer`}
                            data-tripdate={trip_obj.id}
                            data-tripstartdate={trip_obj.dive_start_date_year}
                            data-tripdatetitle={trip_obj.summary}
                            data-tripdateprice={trip_obj.diverace_itinerary_price}
                            onClick={changeTripDateField}
                          >
                            <div className="date-icon-wrapper">
                              <p className="date-with-icon">
                                <img
                                  src={process.env.PUBLIC_URL + "/assets/calendar.png"}
                                  alt="Calendar Icon"
                                  className="calendar-icon"
                                />
                              </p>
                            </div>
                            <div className="date-content-wrapper">
                              <h6>
                                {`${trip_obj.dive_start_date} to ${trip_obj.dive_end_date}`}
                                {/* 12 April to 14 April */}
                              </h6>
                              <p>{trip_obj.dive_total_days_nights}</p>
                              <p className="date-price-info">
                                <span className="summary-text-info">from S$ {trip_obj.diverace_itinerary_price} </span>
                                <i className="fa fa-angle-right date-right-icon" aria-hidden="true"></i>
                              </p>
                            </div>
                          </div>
                        </div>
                      )
                    })}
                  </div>
                </div>
                {/* </div> */}
              </section>
            </div>

            {/* } */}

          </div>

          {/* Summary */}
          {/* summary-wrappper */}
          <Summary step={2}></Summary>
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
      <BackNextButton
        back={1}
        next={3}
        label={`Next`}
        disabled={countryId > 0 && itineraryAreaId > 0 && tripDateId > 0 ? false : true}
      ></BackNextButton>

    </React.Fragment>
  );
};

export default SelectTrip;
