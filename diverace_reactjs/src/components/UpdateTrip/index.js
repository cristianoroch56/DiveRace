import React, {useEffect} from "react";
import Header from "../Header";
import Footer from "../Footer";
import PaymentCheckout from "../PaymentCheckout";
import OrderBookedSummary from "../Summary/OrderBookedSummary";
import {setCoursData, setRentalEquipmentData
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";
import Courses from './Course'
import Equipments from './Equipment';
import ApiServices from "../../services/index";


const api_response = [
    {
        ID: 1,
        course_id: '624',
        course_title: 'Padi divemaster course (DM)',
        course_price: '800',
        equipments: [
            {
                id: 937,
                size: '',
                title: 'Dive Torch',
                price: '10'
            }
        ],
    },
    {
        ID: 2,
        course_id: 0,
        course_title: '',
        course_price: 0,
        equipments: [
            {
                id: 937,
                size: '',
                title: 'Dive Torch',
                price: '10'
            },
            {
                id: 925,
                size: 'M',
                title: 'Wetsuit',
                price: '10'
            },
        ],
    },
    {
        ID: 3,
        course_id: '623',
        course_title: 'Padi diver propulsion vehicle specialty course (DPV)',
        course_price: '500',
        equipments: [
            {
                id: 937,
                size: '',
                title: 'Dive Torch',
                price: '10'
            },
            {
                id: 930,
                size: '',
                title: 'Regulator',
                price: '10'
            },
            {
                id: 927,
                size: 'L',
                title: 'Buoyancy Control Device (BCD)',
                price: '10'
            }
        ],
    },
    {
        ID: 4,
        course_id: '623',
        course_title: 'Padi diver propulsion vehicle specialty course (DPV)',
        course_price: '500',
        equipments: []
    },
];

const EditAddOnsNew = (props) => {

    const { match: { params } } = props;
    const dispatch = useDispatch();

    const [orderId, setOrderId] = React.useState(params.order_id);
    const userLoginData = useSelector((state) => state.user.profile);


    const [isLoadingCourses, setIsLoadingCourses] = React.useState(false)
    const [isLoadingRentals, setIsLoadingRentals] = React.useState(false)


    const fetchCoursesApi = async () => {
        setIsLoadingCourses(true)
        try {
            const response = await ApiServices.getCourses()
            const resCourseData = response.data.data;
            await dispatch(setCoursData(resCourseData))
        } catch (error) {
            console.log(error.message);
        } finally {
            setIsLoadingCourses(false)
        }
    }

    const fetchRentalsApi = async () => {
        setIsLoadingRentals(true)
        try {
            const response = await ApiServices.getRentalEquipment()
            const resRentalData = response.data.data;
            await dispatch(setRentalEquipmentData(resRentalData))
        } catch (error) {
            console.log(error.message);
        } finally {
            setIsLoadingRentals(false)
        }
    }


    useEffect(() => {

        window.scrollTo(0, 0);
        fetchCoursesApi();
        fetchRentalsApi();
    
      }, []);

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
                                                {isLoadingCourses ? (
                                                    <div className="spinner-border text-primary" role="status">
                                                    </div>
                                                ) : <Courses pax_state={api_response}></Courses>}
                                            </div>

                                            <div className="select-area row row-equipment">
                                                <div className="col-sm-12 col-md-6">
                                                    <h4 className="heading">Select Rental Equipment</h4>
                                                </div>
                                                {isLoadingRentals ? (
                                                    <div className="spinner-border text-primary" role="status">
                                                    </div>
                                                ) : <Equipments pax_state={api_response}></Equipments>}
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