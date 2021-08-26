import React, { useEffect } from "react";
import Header from "../Header";
import Footer from "../Footer";
import SelectVessel from "./SelectVessel";
import SelectTripNew from "./SelectTripNew";
import SelectTrip3 from "./SelectTrip3";
import SelectCabinsNew from "./SelectCabinsNew";
import SelectAddOnsNew from "./SelectAddOnsNew";
import ConfirmPayment from "./ConfirmPayment";
import Stepper from "./Stepper";
import { useSelector } from "react-redux";
import "react-loader-spinner/dist/loader/css/react-spinner-loader.css";

const StepWizard = (props) => {

  const { match: { params } } = props;
  
  const step = useSelector((state) => state.StepWizardReducer.step);
  
  const renderForm = (step) => {
   
    switch (step) {
      case 1:
        return <SelectVessel vessel={params.vessel}/>;
      case 2:
        //return <SelectTripNew />;
        return <SelectTrip3 />;
      case 3:
        return <SelectCabinsNew />;
      case 4:
        return <SelectAddOnsNew />;
      case 5:

        // return <Redirect to="login"/>;     //the user can't go back to the previous page <Redirect/>
        // history.push("/login");         //the user can go back to the previous page < histroy object >
        return <ConfirmPayment />;
    }
  };

  useEffect(() => {

    document.body.style.background = `#ffffff`;

    let wizard = document.getElementById("smartwizard");
    window.$(wizard).smartWizard({
      selected: 0,
      theme: "arrows",
      autoAdjustHeight: true,
      transitionEffect: "fade",
      showStepURLhash: false,
      toolbarSettings: {
        showNextButton: false, // show/hide a Next button
        showPreviousButton: false, // show/hide a Previous button
      },
    });
  }, []);

  return (
    <React.Fragment>
      <Header></Header>
      <div className="top">
        <div className="area">
          <div className="main-wrapper-section">
            <div className="container-fluid" id="smartwizard">
              <Stepper step={step} />
              <hr className="custom-hr" />
              <div className="main-bg-clr container-fluid main-step-section-contents">
                {renderForm(step)}
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer></Footer>
    </React.Fragment>
  );
};

export default StepWizard;
