import React from 'react';
import { useDispatch, useSelector } from "react-redux";
import Header from "../Header";
import Footer from "../Footer";
import { useHistory, useLocation } from "react-router-dom";
import InfoModal from '../StepWizard/InfoModal'

const OrderSummary = () => {

    const summaryData = useSelector((state) => state.SummaryReducer);
    const formData = useSelector((state) => state.StepWizardReducer);
    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);
    const userLoginData = useSelector((state) => state.user.profile);

    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);
    const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);

    const [isInfoOpen, setIsInfoOpen] = React.useState(false);

    var BASE_URL = '';
    var url = window.location.href;
    var arr = url.split("/");
    var BASE_URL = arr[0] + "//" + arr[2];
    let history = useHistory();
    const location = useLocation();

    const navigateToBookedTrip = () => {
        if (isAuthenticated) {
            history.replace('/order_details');
        }
    }

    // setTimeout(() => {
    //     window.scrollTo(0, 0);      
    // }, 1000);

    React.useEffect(() => {
        window.localStorage.setItem('prev_path', location.pathname);

        if (!isAuthenticated || !userLoginData) {
            history.replace('/');
        }
        window.scrollTo(0, 0);
    }, []);
    return (
        <React.Fragment>
            <Header></Header>
            <section className="order-success-summary-wrapper mt-50 mb-50">
                <div className="container">
                    {isInfoOpen && (<InfoModal isOpen={isInfoOpen} closePopup={() => setIsInfoOpen(false)}></InfoModal>)}
                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">


                            <i className="fa fa-check-circle-o icon-check" aria-hidden="true"></i>
                            <h4>Thank You</h4>
                            <h5>Your Order Placed Successfully</h5>
                        </div>
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                            <button type="button" className="btn btn-primary btn-lg btn-back-to-home pull-right" onClick={navigateToBookedTrip}>
                                My Trips
                            </button>
                        </div>
                    </div>
                    <hr className="hr-blue-line"></hr>
                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-15 pb-15">
                            <h5 className="pb-15">Hi <span className="capital-letter">{userLoginData && userLoginData.display_name ? userLoginData.display_name : 'NA'}</span>,</h5>
                            <h5>Your Order #: <span className="order_number font-bold">{formData.orderBooked && formData.orderBooked.order_title ? formData.orderBooked.order_title : 'NA'}</span></h5>
                            <h5>Your order has been completed… The details of your booking are </h5>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-15 pb-15">
                            <h4 className="txt-blu-clr">Order summary</h4>
                        </div>
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-15 pb-15">
                            <div className="row">
                                <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Trip Details:</h5>
                                    <p><b>Vessel:</b> {summaryData.vessel_type ? summaryData.vessel_type : "NA"} </p>
                                    <p><b>Location:</b> {summaryData.itineraryArea_type}</p>
                                    <p><b>Trip date:</b> {summaryData.tripDate_type}</p>
                                </div>
                                <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Payment Details:</h5>
                                    <p><b>Payment:</b> {formData.final_payble_amount == 0 ? formData.final_total_amount : formData.final_payble_amount}{defaultCurrencyCode} </p>
                                    <p><b>Partial Payment:</b> {formData.partial_amount == 0 || formData.partial_amount == null ? 'NA' : `${Number(formData.partial_amount)}${defaultCurrencyCode} `} { }</p>
                                    <p><b>Applied Coupon: </b> {formData.coupon_code ? formData.coupon_code : 'NA'} </p>
                                    <p><b>Applied Agent:</b> {formData.agent_code ? formData.agent_code : 'NA'} </p>
                                </div>
                                {/*   <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Cabin Details:</h5>
                                    { formData.cabin_types.length > 0 ? formData.cabin_types.map((cabin_obj,i) => {
                                        return (
                                            <React.Fragment key={i}>
                                                <p><b>Cabins:</b> {`${cabin_obj.title}(${cabin_obj.type === "solo" ? "Solo" : (cabin_obj.seat === 'both' ? "2pax" : "Solo")})`}</p>
                                            </React.Fragment>
                                        )
                                        }) : "NA" }
                                </div> */}
                                <div>
                                    <button type="button" className="btn btn-primary btn-lg btn-back-to-home pull-right" onClick={() => setIsInfoOpen(true)} title="Information">Information <i className="fa fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                        {/* <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-15 pb-15">
                            <div className="row">
                                <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Cours Details:</h5>
                                    {formData.courses_types.length > 0 ? formData.courses_types.map((course_pbj,i) => {
                                        return (
                                            <React.Fragment key={i}>
                                                {`${course_pbj.title} x ${course_pbj.person}`} <br/>
                                            </React.Fragment>
                                        )
                                        }) : "No course selected"}                                    
                                </div>
                                <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Equipment Details:</h5>
                                    {formData.rental_equipment_types.length > 0 ? formData.rental_equipment_types.map((rental_pbj,i) => {
                                        return (
                                            <React.Fragment key={i}>
                                                {`${rental_pbj.title} x ${rental_pbj.person}`}<br/>
                                            </React.Fragment>
                                        )
                                        }) : "No Rental equipment selected"}<br/>
                                </div>
                            </div>    
                        </div>
                         */}
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-15 pb-15">
                            <div className="row">
                                {/* <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Person Details:</h5>
                                        <p><b>No. Of Pax:</b> {formData.passenger}</p>   
                                        {formData.pax.length > 0 ? formData.pax.map((pax_pbj,i) => {
                                        return (
                                            <React.Fragment key={i}>
                                            </React.Fragment>
                                        )
                                        }) : "No course selected"}                                 
                                </div> */}
                                {/* <div className="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h5 className="main-title">Payment Details:</h5>
                                    <p><b>Payment:</b> {formData.final_payble_amount == 0 ? formData.final_total_amount : formData.final_payble_amount}SGD </p>
                                    <p><b>Partial Payment:</b> {formData.partial_amount == 0 || formData.partial_amount == null ? 'NA' : `${Number(formData.partial_amount)}SGD `}</p>
                                    <p><b>Applied Coupon: </b> {formData.coupon_code ? formData.coupon_code : 'NA' } </p>
                                    <p><b>Applied Agent:</b> {formData.agent_code ? formData.agent_code : 'NA'} </p>
                                </div> */}
                            </div>
                        </div>
                    </div>

                    <hr className="hr-blue-line"></hr>

                    <div className="row">
                        <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h5>You will receive an email confirmation from us shortly, you can manage your booking by logging into our <a target="_blank" href={BASE_URL}>Website</a></h5>
                        </div>
                    </div>
                </div>
            </section>

            <Footer ></Footer>
        </React.Fragment>
    );
};
export default OrderSummary;