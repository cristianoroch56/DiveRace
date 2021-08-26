import React, {useEffect} from 'react';
import ReactModal from 'react-modal';
import { useSelector } from "react-redux";

const PopupTripDates = (props) => {

  const tripDate_list = useSelector((state) => state.StepWizardReducer.tripDate_list);
  const tripDateId = useSelector((state) => state.StepWizardReducer.tripDate_type);

    const customStyles = {
      content : {
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
      ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
    },[])

    return ( 
        <ReactModal 
          isOpen={props.showPopupState}
          style={customStyles}
          contentLabel="TripDates Modal"
          onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button> 
            {tripDate_list.length !== 0 && <h4 className="popup-heading heading">Select Trip Date</h4>} 
            <div className="popup-hr"></div>
            <div className="">
                  {/* <div className="date-area border-rd"> */}
                     <div className="row date-area border-rd date-wrapper-section" > 

                      {tripDate_list.length === 0 ? (<div className="text-center w-100">NO RECORD FOUND</div>) : tripDate_list.map((trip_obj,t) => {

                        return (
                          <div key={t} className="border-right col-md-6">
                            <div className={`date-box ${trip_obj.id === tripDateId ? "cls-border avoid-clicks": null} pointer`}
                                data-tripdate={trip_obj.id}
                                data-tripstartdate={trip_obj.dive_start_date_year}
                                data-tripdatetitle={trip_obj.summary}
                                data-tripdateprice={trip_obj.diverace_itinerary_price}
                                onClick={props.updateTripDate}
                                >
                                  <div className="date-icon-wrapper">
                                    <p className="date-with-icon">
                                      <i
                                        className="fa fa-calendar fa-lg"
                                        aria-hidden="true"
                                        style={{ color: "#3396d8" }}
                                      ></i>   
                                    </p>  
                                  </div>
                                  <div className="date-content-wrapper">
                                    <h6>                                    
                                      {`${trip_obj.dive_start_date} to ${trip_obj.dive_end_date}`}
                                      {/* 12 April to 14 April */}
                                    </h6>
                                    <p>{trip_obj.dive_total_days_nights} </p>
                                    <p className="date-price-info">
                                      <span className="summary-text-info">from S$ {trip_obj.diverace_itinerary_price} </span>
                                      <i className="fa fa-angle-right date-right-icon" aria-hidden="true"></i>
                                    </p>
                                  </div>  
                            </div>
                          </div>
                        )
                      })}
                      {/* Custom */}
                    </div>

                    {/* <div className="date-right col-md-6">
                      
                    </div> */}
                  </div>
                {/* </div> */}
        </ReactModal>
     );
}
 
export default PopupTripDates;