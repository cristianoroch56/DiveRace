import React, { useEffect, useState } from 'react';
import ReactModal from 'react-modal';
import { useSelector, useDispatch } from "react-redux";
import ApiServices from "../../services/index"
import {
    updateForm,
    updateSummary,
} from "../../store/actions/StepWizardAction";

const PopupTripDates2 = (props) => {

    const dispatch = useDispatch();
    const vessel_id = useSelector((state) => state.StepWizardReducer.vessel_type);
    const itineraryAreaId = useSelector((state) => state.StepWizardReducer.itineraryArea_type);
    const [destinations, setDestinations] = useState([]);

    const [isLoading, setIsLoading] = useState(false);

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

    const getDestinations = async (dates) => {
        setIsLoading(true)
        setDestinations([]);
        var postData = { vessel_id: vessel_id, trip_start_date: '', trip_end_date: '' }
        let resp = await ApiServices.getItineraryAreaFromVessel(postData);
        setIsLoading(false)
        if (resp.data.status) {
            let itineraries = resp.data.data[0].itineraries_data;
            setDestinations(itineraries);
        }
    }

    const changeTripDateField = (event) => {

        let destinationId = event.currentTarget.getAttribute("data-destinationid");
        let itinerary_id = event.currentTarget.getAttribute("data-itinerary_id");

        let destination_trip_title = event.currentTarget.getAttribute("data-destinationtriptitle");
        let destination_title = event.currentTarget.getAttribute("data-destinationtitle");

        dispatch(updateForm('itineraryArea_type', Number(destinationId)));
        dispatch(updateForm('tripDate_type', Number(itinerary_id)));

        dispatch(updateSummary('tripDate_type', destination_title));
        dispatch(updateSummary('itineraryArea_type', destination_trip_title));

    }

    const DestinationUi = (props) => {
        const meta_info = props.meta;
        return (
            <React.Fragment>

                { props.destinations.map((dest_obj, i) => {
                    return (

                        <div key={i} className="col-12 col-sm-12 col-md-12">

                            <div
                                className={`date-box ${dest_obj.destination_id == itineraryAreaId ? "cls-border" : "border-rd"} pointer`}
                                data-destinationid={dest_obj.destination_id}
                                data-itinerary_id={meta_info.itinerary_id}
                                data-destinationtriptitle={meta_info.destination_title}
                                data-destinationtitle={dest_obj.destination_title}
                                onClick={dest_obj.is_booked == 'no' ? changeTripDateField : undefined}>
                                
                                <div className="date-content-wrapper">
                                    <p className="date-with-icon">
                                        <img
                                            src={process.env.PUBLIC_URL + "/assets/calendar.png"}
                                            alt="Calendar Icon"
                                            className="calendar-icon"
                                        />
                                    </p>
                                    <div className="custom-date-content-wrapper">
                                        <h6>{dest_obj.destination_title}</h6>
                                        <p>{`${meta_info.dive_start_date} ${meta_info.dive_end_date}(${meta_info.itinerary_total_days_nights}) - ${dest_obj.remaining_seats} Seats`}</p>
                                        <p className="date-price-info">
                                            <span className="summary-text-info">from S$ {meta_info.itinerary_price} </span>
                                            {/* {dest_obj.is_booked == 'yes' && (
                                                <button type="button" className="btn btn-default waiting-list-btn"><span>Join waiting list</span></button>
                                            )} */}
                                        </p>
                                    </div>
                                    {dest_obj.is_booked == 'yes' && (
                                        <div className="sold-out-trip">sold out</div>
                                    )}
                                    {/* <span className="trip_arrow_left"><i className="fa fa-chevron-right"></i></span> */}
                                </div>
                            </div>
                        </div>
                    )
                })}
            </React.Fragment>
        )

    }

    useEffect(() => {
        ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
        getDestinations();
    }, [])

    return (
        <ReactModal
            isOpen={props.showPopupState}
            style={customStyles}
            contentLabel="TripDates Modal"
            onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>

            <div className="row date-area border-rd custom-date-wrapper date-wrapper-section p-0">
                {isLoading ? (<div style={{ width: '100%'}}>
                    <div className="spinner-border text-primary" role="status">
                    </div>
                </div>) : (
                        <div className="trip__dates_modal" style={{ maxHeight: 500 }}>
                            {destinations.length > 0 ? (
                                destinations.map((obj, i) => {
                                    return (
                                        <DestinationUi key={i} destinations={obj.destination_data} meta={obj}></DestinationUi>
                                    )
                                })
                            ) : 'No Records Found'}
                        </div>
                    )}

            </div>


        </ReactModal>
    );
}

export default PopupTripDates2;