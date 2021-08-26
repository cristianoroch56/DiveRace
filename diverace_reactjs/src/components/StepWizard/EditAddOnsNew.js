import React from "react";
import Header from "../Header";
import Footer from "../Footer";
import PaymentCheckout from "../PaymentCheckout";
import OrderBookedSummary from "../Summary/OrderBookedSummary";
import {
    fetchBookedRentalEquipment,
    fetchBookedCourses, updateForm, getBookedCourses
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";

const EditAddOnsNew = (props) => {

    const { match: { params } } = props;

    return (
        <>
            <Header></Header>
            <div className="top">
                <div className="area">
                    <div className="main-wrapper-section">
                        <div className="container-fluid" id="smartwizard">

                            <div className="pt-4 main-bg-clr container-fluid main-step-section-contents">
                                <div
                                    id="step-4"
                                    className="pt-4 container content-center addons-wrapper"
                                    style={{ display: "block" }}
                                >
                                    <div className="row">
                                        <div className="col-md-7 pb-15">
                                            <h4 className="heading display-course-title">
                                                Select Any Course</h4>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="body-left col-md-7">
                                            <div className="select-area">
                                                <h4 className="heading hidden-course-title">
                                                    Select Any Course</h4>
                                                {/*  */}
                                            </div>

                                            <div className="select-area row row-equipment">
                                                <div className="col-sm-12 col-md-6">
                                                    <h4 className="heading">Select Rental Equipment</h4>
                                                </div>
                                                {/*  */}
                                            </div>
                                        </div>
                                        <OrderBookedSummary order_id={Number(orderId)} user_id={Number(userLoginData.ID)}></OrderBookedSummary>
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
                                <div className="container" style={{ paddingBottom: 30, display: "block", textAlign: "center", margin: "0 auto" }}>
                                    <div className="row button-section-wrapper pl-15" >
                                        <PaymentCheckout />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Footer></Footer>
        </>
    )

}

export default EditAddOnsNew;