import React, { useEffect, useState } from "react";
// import Loaderdiv from "./Loader";
import { useDispatch, useSelector } from "react-redux";   //shallowEqual
import { useHistory } from "react-router-dom";
import BackNextButton from "./BackNextButton";
import { updateForm, updateSummary, setStep, setInitialPax, setInitialState, setSummaryInitialState } from "../../store/actions/StepWizardAction";
import { fetchVessels, fetchBookedTripSummary } from "../../store/actions/StepWizardAction";  // setDefaltItem 
// import { loginUser } from "../../store/actions/Auth";

const SelectVessel = (props) => {

  const [isLoading, setisLoading] = useState(true);
  const userLoginData = useSelector((state) => state.user);
  const vessel_id = useSelector((state) => state.StepWizardReducer.vessel_type);
  const vessel_list = useSelector((state) => state.StepWizardReducer.vessel_list);
  const dispatch = useDispatch();
  let history = useHistory();

  const changeVesselField = (event) => {

    event.preventDefault();

    let vesselType = event.currentTarget.getAttribute("data-vessel");
    let vesselTitle = event.currentTarget.getAttribute("data-vesseltitle");
    let vesselImage = event.currentTarget.getAttribute("data-vesselimage");
    let stateType = event.currentTarget.getAttribute("data-statetype");

    dispatch(updateForm(stateType, Number(vesselType)));
    dispatch(updateSummary('vessel_image', vesselImage));
    dispatch(updateSummary(stateType, vesselTitle))

    dispatch(setStep(2))

  };

  useEffect(() => {


    let prev_path = window.localStorage.getItem('prev_path');
    if (prev_path == '/order_summary') {

      dispatch(setInitialState());
      dispatch(setSummaryInitialState())
      dispatch(setInitialPax());
      dispatch(updateForm("user_id", Number(userLoginData.profile.ID)));
      window.localStorage.removeItem('prev_path');
    }

    window.scrollTo(0, 0);
    dispatch(fetchVessels(Number(vessel_id)));

    if (vessel_list.length > 0 && props.vessel) {

      const VESSEL_ID = props.vessel;
     
      let obj = vessel_list.find(v => v.id == VESSEL_ID);
      dispatch(updateForm('vessel_type', Number(VESSEL_ID)));
      dispatch(updateSummary('vessel_type', obj.title));
      dispatch(setStep(2));
      history.replace('/');

    }

  }, [vessel_id]);


  return (
    <React.Fragment>

      <div id="step-1" className="container content-center main-vessel-wrapper step-main-wrapper" style={{ display: 'block' }}>


        <div className="img-box row">
          <div className="col-md-12">
            <h2 className="Step-header">
              Selection of Vessel
            </h2>
          </div>

          <div className="col-md-10 mx-auto">
            <div className="card-deck">
              
          
              {vessel_list.length === 0 ? (<div className="text-center w-100">NO RECORD FOUND</div>) : vessel_list.map((vessel, index) => {
                return (
                  <div className="col-md-6 single-listing-wrapper" key={index}>
                    <div
                      className={`card ${vessel.id == vessel_id ? "cls-border" : ""}`}
                    >
                      <div className="pdf-thumb-box"
                        style={{
                          display: "block",
                          margin: "inherit",
                          textAlign: "center",
                          backgroundColor: "#f0f0f0",
                          backgroundImage: `url(${vessel.featured_image})`

                        }}
                      >
                        <a
                          className=""
                          href="#"
                          data-vessel={vessel.id}
                          data-vesseltitle={vessel.title}
                          data-vesselimage={vessel.featured_image}
                          data-statetype={`vessel_type`}
                          onClick={changeVesselField}
                        >
                          <div className="pdf-thumb-box-overlay">
                            <div className="card-icon">
                              <img
                                src={vessel.vessels_icons}
                                className="card-img-top"
                                alt="cabins"
                              />
                            </div>
                          </div>
                        </a>
                      </div>
                      <div className="card-body">
                        <h5 className="card-title">{vessel.title}</h5>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
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
    </React.Fragment>
  );
};

export default SelectVessel;
