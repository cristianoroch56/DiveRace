import React from "react";
import Header from "../Header";
import Footer from "../Footer";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import PaxDetailsModal from './PaxDetailsModal';
import InfoModal from '../StepWizard/InfoModal'
import ApiServices from "../../services/index"
import { fetchAllBookedTripByUser, updateForm, fetchUserBalance, setAllBookedTripByUser } from "../../store/actions/StepWizardAction";

const BookedTrip = () => {

    const dispatch = useDispatch();

    const bookedData = useSelector((state) => state.StepWizardReducer.booked_trip_byuser);
    const order_updated = useSelector((state) => state.StepWizardReducer.order_updated_success);
    const userLoginData = useSelector((state) => state.user.profile);
    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);
    const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);
    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);
    const [isLoading, setIsLoading] = React.useState(false);
    const [isInfoOpen, setIsInfoOpen] = React.useState(false);


    const [paxDetails, setPaxDetails] = React.useState({
        isShow: false,
        data: []
    });

    const openPopup = (pax_data) => {

        setPaxDetails({
            //data: [...pax_data],
            isShow: true
        })
    }

    const togglePopup = () => {
        setPaxDetails({
            ...paxDetails,
            isShow: !paxDetails.isShow
        })
    }

    const closeAlert = () => {
        dispatch(updateForm('order_updated_success', 'none'))
    }

    if (order_updated == 'block') {
        setTimeout(() => {
            dispatch(updateForm('order_updated_success', 'none'))
        }, 3000);
    }

    const fetchUserTrips = async (user_id) => {
        setIsLoading(true)
        try {
            const response = await ApiServices.getBookedTripData(user_id);
            const resBookedData = response.data.data;
            await dispatch(setAllBookedTripByUser(resBookedData))
        } catch (error) {
            console.log(error.message);
        } finally {
            setIsLoading(false)
        }

    }

    React.useEffect(() => {
        if (isAuthenticated) {
            const USER_ID = userLoginData.ID;
            dispatch(fetchUserBalance(Number(USER_ID)))
        }
    }, []);

    React.useEffect(() => {
        window.scrollTo(0, 0);
        document.body.style.background = `#ffffff`;

        if (isAuthenticated) {
            const USER_ID = userLoginData.ID;
            fetchUserTrips(Number(USER_ID))
        }

    }, [userLoginData]);

    const sortTripDate = () => {

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

    const TripTableUi = ({bookedData}) => {
        
        if(bookedData.length === 0) {
            return <div className="d-flex justify-content-center align-items-center" style={{ textAlign: "center", minHeight: 200 }}><span className="">NO RECORDS FOUND</span></div>;
        }
        return (
            <>
                <table className="table table-bordered table-striped">
                    {<thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col" style={{ cursor: "pointer" }} onClick={sortTripDate}>Trip Details    <i className="fa fa-sort ml-2" style={{ color: "#000" }} aria-hidden="true"></i></th>
                            <th scope="col">Payment Details</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>}
                    <tbody className="table-hover">
                        {bookedData.map((obj, i) => {
                            return (
                                <tr key={i}>
                                    <th scope="row">Trip Order ID: <br /><span>{obj.order_title}</span></th>
                                    <td>
                                        <b>Vessel:</b> <span>{obj.vessel_title}</span><br />
                                        <b>Location:     </b> <span>{obj.itinery_title}</span><br />
                                        <b>Trip dates: </b> <span>{`${obj.itternary_start_date} - ${obj.itternary_end_date} (${obj.itternary_total_DN})`}</span>
                                    </td>
                                    <td><b>Payment:</b> <span>{obj.payble_amount} SGD</span><br /><b>Applied Coupon Code:</b> <span>{obj.coupon_data_coupon_code ? obj.coupon_data_coupon_code : 'NA'}</span><br /><b>Applied Agent Code:</b> <span>{obj.agent_data_agent_code ? obj.agent_data_agent_code : 'NA'}</span></td>

                                    <td>

                                        <Link to={`/update_order/${obj.id}`}>
                                            <button type="button" title="edit trip" className="btn btn-primary">Edit Trip</button>
                                        </Link>

                                        <button type="button" title="view Trip" onClick={() => { openPopup(obj.cabin_data); }} className="btn btn-primary btn-circle ml-2">View Trip</button>
                                        <button type="button" title="Order Summary" onClick={() => setIsInfoOpen(true)} className="btn btn-primary btn-circle ml-2">Order Summary</button>
                                    </td>

                                </tr>
                            )
                        })
                        }

                    </tbody>
                </table>
            </>
        )
    }

    return (
        <React.Fragment>
            <Header></Header>
            <div className="container-fluid">
                {isInfoOpen && (<InfoModal isOpen={isInfoOpen} closePopup={() => setIsInfoOpen(false)}></InfoModal>)}

                <div className="row table-responsive custom-table" style={{ paddingLeft: 80, paddingRight: 80, paddingTop: 50, paddingBottom: 50 }}>
                    <p className="font-weight-bold">Your Credit: {(userLoginData && userLoginData !== undefined && userLoginData.user_credit > 0) ?  priceSpan(userLoginData.user_credit) : 0} {defaultCurrencyCode}</p>

                    {isLoading ? (<div className="spinner-border text-primary" role="status"> </div>) : <TripTableUi bookedData={bookedData}></TripTableUi>}

                </div>
            </div>
            {paxDetails.isShow &&
                <PaxDetailsModal modalShow={paxDetails.isShow} closePopup={togglePopup} person={paxDetails.data}></PaxDetailsModal>
            }
            <Footer className=""></Footer>
        </React.Fragment>

    );
};

export default BookedTrip;



