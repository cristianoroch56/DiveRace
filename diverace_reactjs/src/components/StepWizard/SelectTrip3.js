import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import moment from 'moment';
import classNames from 'classnames';
import * as twix from 'twix';
import {
    updateForm,
    updateSummary,
    setInitialPax,
    updateAmount
} from "../../store/actions/StepWizardAction";
import BackNextButton from "./BackNextButton";
import WaitListModal from "./WaitListModal"
import ApiServices from "../../services/index"
import InputDateRange from '../../ReusableComponents/DateRange'

const SelectTrip3 = () => {


    const dispatch = useDispatch();
    const vessel_id = useSelector((state) => state.StepWizardReducer.vessel_type);
    const pax_array = useSelector((state) => state.StepWizardReducer.pax);
    const itineraryAreaId = useSelector((state) => state.StepWizardReducer.itineraryArea_type);
    const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);
    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);
    


    const [filterDates, setFilterDates] = useState({
        startDate: moment(),
        endDate: null
    })

    const [destinations, setDestinations] = useState([]);
    const [showWaitList, setShowWaitList] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [waitingListProps, setWaitingListProps] = useState({});
    const [highlightdates, setHighlightdates] = useState([])


    const toggleWaitlist = (event) => {
        let destinationId = event.currentTarget.getAttribute("data-destinationid");
        let itinerary_id = event.currentTarget.getAttribute("data-itinerary_id");
        setWaitingListProps({
            vessel_id: vessel_id,
            destination_id: destinationId,
            trip_date_id: itinerary_id
        })
        setShowWaitList(!showWaitList)
    }

    const changeTripDateField = (event) => {

        let destinationId = event.currentTarget.getAttribute("data-destinationid");
        let itinerary_id = event.currentTarget.getAttribute("data-itinerary_id");
        let itinerary_price = event.currentTarget.getAttribute("data-itinerary_price");
        let currencyPrice = Math.round(itinerary_price);

        let destination_trip_title = event.currentTarget.getAttribute("data-destinationtriptitle");
        let destination_title = event.currentTarget.getAttribute("data-destinationtitle");

        dispatch(updateForm('itineraryArea_type', Number(destinationId)));
        dispatch(updateForm('tripDate_type', Number(itinerary_id)));

        dispatch(updateSummary('itineraryArea_type', destination_trip_title));
        dispatch(updateSummary('tripDate_type', destination_title));

        dispatch(updateAmount(currencyPrice));

        let pax_list = [...pax_array];
        const updated_pax_array = pax_list.map(pax_obj => ({ ...pax_obj, cabin_list: [] }));
        dispatch(updateForm('pax', updated_pax_array));
        dispatch(setInitialPax());

    }

    const convertCurrencyPrice = (price) => {
        let changedPrice;
        if(default_currency === 'THB') {
            changedPrice = price*23.46;
        } else if(default_currency === 'USD') {
            changedPrice = price*0.75;
        } else {
            changedPrice = price;
        }
        return changedPrice;
    }

    const PriceSpan =  ({price}) => {
        let changedPrice;
        if(default_currency === 'THB') {
            changedPrice = price*23.46;
        } else if(default_currency === 'USD') {
            changedPrice = price*0.75;
        } else {
            changedPrice = price;
        }
        return (
            <span>{defaultCurrencyCode} {Math.round(changedPrice)}</span>
        )
    }

    const DestinationUi = (props) => {
        const meta_info = props.meta;
        return (
            <React.Fragment>

                { props.destinations.map((dest_obj, i) => {
                    return (

                        <div key={i} 
                        className="col-12 col-sm-12 col-md-12 wrapper-sold-out-trip"
                        className={classNames(
                            {
                              'odd': i%2 !== 0,
                              'even': i%2 === 0,
                            }
                          )}
                        >

                            <div
                                className={`date-box ${dest_obj.destination_id == itineraryAreaId ? "cls-border" : "border-rd"} pointer`}
                                data-destinationid={dest_obj.destination_id}
                                data-itinerary_id={meta_info.itinerary_id}
                                data-destinationtriptitle={meta_info.destination_title}
                                data-destinationtitle={dest_obj.destination_title}
                                data-itinerary_price={meta_info.itinerary_price}
                                onClick={dest_obj.is_booked == 'yes' ? undefined : changeTripDateField}>
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
                                        <p>{`${meta_info.dive_start_date} - ${meta_info.dive_end_date} (${meta_info.itinerary_total_days_nights})`}</p>
                                        <span className="available_spot">{`${dest_obj.remaining_seats} spots available`} <span className="remaining_spots">( 3 solo | 13 2pax )</span> </span>
                                        <p className="date-price-info">
                                            <span className="summary-text-info">from <PriceSpan price={meta_info.itinerary_price}/> </span>
                                            {dest_obj.is_booked == 'yes' && (
                                                <div className="book-trip-wrapper">
                                                    <button type="button" className="btn btn-default waiting-list-btn"
                                                        data-destinationid={dest_obj.destination_id}
                                                        data-itinerary_id={meta_info.itinerary_id} onClick={toggleWaitlist}><span>Join waiting list</span></button>
                                                    <div className="sold-out-trip">sold out</div>
                                                </div>
                                            )}

                                        </p>
                                    </div>

                                    {/* <span className="trip_arrow_left"><i className="fa fa-chevron-right"></i></span> */}
                                </div>
                            </div>
                        </div>
                    )
                })}

            </React.Fragment>
        )

    }

    const getDestinations = async (dates) => {
        setIsLoading(true)
        setDestinations([]);
        var postData = { vessel_id: vessel_id, trip_start_date: '', trip_end_date: '' }
        if (dates) {
            var postData = { vessel_id: vessel_id, trip_start_date: dates.start_date, trip_end_date: dates.end_date }
        }
        let resp = await ApiServices.getItineraryAreaFromVessel(postData);
        setIsLoading(false)
        if (resp.data.status) {
            let itineraries = resp.data.data[0].itineraries_data;
            setDestinations(itineraries);
        }
    }


    useEffect(() => {
        window.scrollTo(0, 0);
        var dates = '';
        getDestinations(dates);
    }, []);


    const onDatesChange = ({ startDate, endDate }) => {

        setFilterDates({
            startDate,
            endDate
        })
    }

    useEffect(() => {

        var dates = '';
        if (filterDates.startDate && filterDates.endDate) {
            var dates = { start_date: moment(filterDates.startDate).format('YYYY-MM-DD'), end_date: moment(filterDates.endDate).format('YYYY-MM-DD') }
            getDestinations(dates);
        } else if (!filterDates.startDate && !filterDates.endDate) {
            getDestinations('');
        }

    }, [filterDates]);

    const getDatesBetweenDates = (startDate, endDate) => {
        var itr = moment.twix(new Date(startDate), new Date(endDate)).iterate("days");
        var range = [];
        while (itr.hasNext()) {
            range.push(itr.next().format("YYYY-M-D"))
        }
        return range;
    }

    useEffect(() => {
        var dummy_highlight_dates = [];
        destinations.forEach(date_obj => {
            let getDates = getDatesBetweenDates(date_obj.dive_start_date_year, date_obj.dive_end_date_year)
            dummy_highlight_dates = [...dummy_highlight_dates, ...getDates];
        });
        setHighlightdates([...dummy_highlight_dates])
    }, [destinations])


    return (
        <React.Fragment >
            <div id="step-2"
                className="container content-center step-main-wrapper"
                style={
                    { display: "block" }} >

                <section className="row row-datedetails-wrapper custom-datedetails-wrapper mb-5">


                    {showWaitList &&   

                        <WaitListModal isShow={showWaitList} closePopup={() => setShowWaitList(false)} waitingProps={waitingListProps} />}

                    <div className="col-md-12 text-center mb-4">
                        <InputDateRange
                            startDate={filterDates.startDate}
                            endDate={filterDates.endDate}
                            onChange={onDatesChange}
                            onClose={undefined}
                            highlightedDates={highlightdates}
                        />
                    </div>

                    <div className="col-md-12">
                        <div className="row date-area border-rd custom-date-wrapper date-wrapper-section">
                            <div className="trip_date_">


                                {isLoading ? (
                                    <div className="spinner-border text-primary" role="status">
                                    </div>) : (
                                    destinations.length > 0 ? (
                                        <>
                                            <div className="trip_dates_wrapper">
                                                {destinations.map((obj, i) => {
                                                    return (
                                                        <DestinationUi key={i} destinations={obj.destination_data} meta={obj}></DestinationUi>
                                                    )
                                                })}
                                            </div>
                                            <button type="button" title="View all" className="mt-5 btn btn-primary btn-lg btn-back-to-home view_all_trip">View all</button>
                                        </>
                                    ) : 'No Records Found'
                                )}

                            </div>
                        </div>
                    </div>
                </section>

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
                disabled={itineraryAreaId ? false : true}
            ></BackNextButton>
        </React.Fragment>
    )
}

export default SelectTrip3


