import React, { useEffect } from "react";
import BackNextButton from "./BackNextButton";
import Summary from "../Summary/index";
import { setStep, updateForm, setCoursData, setRentalEquipmentData } from "../../store/actions/StepWizardAction"
import { useDispatch, useSelector } from "react-redux";
import Courses from './AddOns/Courses';
import Equipments from './AddOns/Equipments'
import PopUpLogin from '../Login/PopUpLogin';
import PopUpRegister from '../Register/PopUpRegister';
import PopUpForgotPassword from '../ForgotPassword/PopUpForgotPassword';
import ApiServices from "../../services/index";


const paxParams = {
  person_id: undefined, is_used: false, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
  cabin_type: '', course_id: 0, course_title: '', course_price: 0, course_used: true,
  equipments: [], equipment_used: true,
}

const SelectAddOnsNew = () => {


  const dispatch = useDispatch();
  const pax_cabin = useSelector((state) => state.StepWizardReducer.pax_cabin);
  const pax = useSelector((state) => state.StepWizardReducer.pax);
  const updated_pax_flag = useSelector((state) => state.StepWizardReducer.updated_pax_flag);
  const isAuthenticated = useSelector((state) => state.user.isAuthenticated);


  const [isLoadingCourses, setIsLoadingCourses] = React.useState(false)
  const [isLoadingRentals, setIsLoadingRentals] = React.useState(false)

  const [showCourses, setShowCourses] = React.useState(true)
  const [showRentals, setShowRentals] = React.useState(true)

  const [loginModal, setLoginModal] = React.useState(false)
  const [registerModal, setRegisterModal] = React.useState(false)
  const [forgotPassordModal, setForgotPassordModal] = React.useState(false)


  const skipnext = (e) => {
    e.preventDefault();
    dispatch(setStep(5));
  }

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

  const setPaxFormat = () => {
    const paxCabinArray = [...pax_cabin];
    const newPaxArray = [];
    const updated_pax_array = paxCabinArray.map((paxObj, i) => {
      if (paxObj.type === '2pax') {
        const firstObject = {
          ...paxParams,
          ...paxObj,
          updated: true,
          gender: [paxObj.gender[0]],

        }
        const secondObject = {
          ...paxParams,
          ...paxObj,
          updated: true,
          gender: [paxObj.gender[1]],

        }
        newPaxArray.push(firstObject, secondObject);
      } else {
        const firstObject = {
          ...paxParams,
          ...paxObj,
          updated: true,
        }
        newPaxArray.push(firstObject);
      }
    });

    return newPaxArray;


  }

  const offCoursesSection = () => {

    const paxArray = [...pax];
    const updated_person_array = paxArray.map((p) => ({ ...p, course_id: 0, course_title: '', course_price: 0 }));
    dispatch(updateForm('pax', [...updated_person_array]));
    setShowCourses(false)
  }

  const offRentalSection = () => {

    const paxArray = [...pax];
    const updated_person_array = paxArray.map((p) => ({ ...p, equipments: [] }));
    dispatch(updateForm('pax', [...updated_person_array]));
    setShowRentals(false)
  }


  useEffect(() => {

    window.scrollTo(0, 0);



    fetchCoursesApi();
    fetchRentalsApi();

  }, []);

  useEffect(() => {


    if (updated_pax_flag === 0) {
      const newPaxArray = setPaxFormat();
      const updated_pax_array = newPaxArray.map((pax_obj, i) => ({ ...pax_obj, ID: i + 1 }));
      dispatch(updateForm('pax', [...updated_pax_array]));
      dispatch(updateForm('updated_pax_flag', 1));
    }



  }, [])

  const handleLogIn = () => {
    if (isAuthenticated) {
      dispatch(setStep(5));
    } else {
      setLoginModal(true);
    }
  }

  const closeLoginPopup = () => {
    setLoginModal(false)
  }

  const openRegisterPopup = () => {
    setRegisterModal(true)
  }

  const closeRegisterPopup = () => {
    setRegisterModal(false)
  }

  const openForgotPopup = () => {
    setForgotPassordModal(true)
  }

  const closeForgotPopup = () => {
    setForgotPassordModal(false)
  }


  return (
    <React.Fragment>
      <div id="step-4" className="container content-center step-main-wrapper addons-wrapper" style={{ display: 'block' }}>

        {loginModal && <PopUpLogin isShow={loginModal} openRegisterPopup={openRegisterPopup} openForgotPopup={openForgotPopup} closeLoginPopup={closeLoginPopup} ></PopUpLogin>}
        {/* Register Modal */}
        {registerModal && <PopUpRegister isShow={registerModal} openLoginPopup={handleLogIn} closeRegisterPopup={closeRegisterPopup}></PopUpRegister>}
        {/* Register Modal */}
        {/* Forgot password Modal */}
        {forgotPassordModal && <PopUpForgotPassword isShow={forgotPassordModal} openLoginPopup={handleLogIn} closeForgotPopup={closeForgotPopup}></PopUpForgotPassword>}
        {/* Forgot password Modal */}
        <div className="row">
          <div className="col-12">
            <button type="button" className="btn btn-primary btn-lg btn-addons-skip" onClick={handleLogIn}>Skip to Next Step</button>
          </div>
        </div>
        <div className="row">
          <div className="body-left col-md-7">
            <div className="select-area">

              <h4 className="courses__qn_text">Are you interested in doing any SCUBA courses?</h4>
              <div className="custom_courses_qn" >

                <input
                  type="radio"
                  name="courses"
                  value="no"
                  id="courses_yes"
                  checked={showCourses === true}
                  onClick={(e) => setShowCourses(true)}
                  className="form-control"
                />
                <label htmlFor="courses_yes">Yes</label>

                <input
                  type="radio"
                  name="courses"
                  value="no"
                  id="courses_no"
                  checked={showCourses === false}
                  onClick={offCoursesSection}
                  className="form-control"

                />
                <label htmlFor="courses_no">No</label>
              </div>

              {showCourses && (
                isLoadingCourses ? (
                  <div className="spinner-border text-primary" role="status">
                  </div>
                ) : <><h4 className="heading display-course-title">Select Any Course</h4>
                  <h5 className="text-dark course-note">Note: You can also book courses and equipments on edit booking page.</h5>
                  <Courses></Courses>  </>
              )}
            </div>

            <div className="select-area row-equipment">

              <h4 className="courses__qn_text">Do you require any Rental Dive Equipment?</h4>
              <div className="custom_courses_qn" >

                <input
                  type="radio"
                  name="rentals"
                  value="no"
                  id="rental_yes"
                  checked={showRentals === true}
                  onClick={(e) => setShowRentals(true)}
                  className="form-control"
                />
                <label htmlFor="rental_yes">Yes</label>

                <input
                  type="radio"
                  name="rentals"
                  value="no"
                  id="rental_no"
                  checked={showRentals === false}
                  onClick={offRentalSection}
                  className="form-control"

                />
                <label htmlFor="rental_no">No</label>
              </div>


              {showRentals && (
                isLoadingRentals ? (
                  <div className="spinner-border text-primary" role="status">
                  </div>
                ) : <>  <div className="col-sm-12 col-md-12">
                  <h4 className="heading display-course-title">Select Rental Equipment</h4>
                </div><Equipments></Equipments> </>
              )}

            </div>
          </div>

          <Summary step={4}></Summary>
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
        back={3}
        next={5}
        label={`Proceed to Overview`}
        disabled={false}
      ></BackNextButton>
    </React.Fragment>
  );
};

export default SelectAddOnsNew;
